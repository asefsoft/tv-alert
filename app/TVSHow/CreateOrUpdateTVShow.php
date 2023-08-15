<?php

namespace App\TVSHow;

use App\Data\TVShowData;
use App\Events\TVShowCreated;
use App\Events\TVShowUpdated;
use App\Models\TVShow;
use Carbon\Carbon;
use Str;

class CreateOrUpdateTVShow
{
    protected CreateOrUpdateStatus $status;
    private $showOnDB;

    public function __construct(protected array | TVShowData $tvShowInfo) {

        if(is_array($this->tvShowInfo)) {
            // parse array into tvshow data
            try {
                $tvShowInfo = TVShowData::from($this->tvShowInfo);
            } catch (\Exception $e) {
                $this->status = CreateOrUpdateStatus::InvalidData;
                return;
            }
        }

        $this->createOrUpdate($tvShowInfo);
    }

    private function createOrUpdate(TVShowData $showData) {

        $this->updateNextEpisodeDate($showData);
        $this->updateLastAiredEpisode($showData);

        // update last_check_date
        $showDataTobeSaved = array_merge($showData->toArray(), ['last_check_date' => now()]);

        $this->prepareDataBeforeSave($showDataTobeSaved);

        $this->showOnDB = TVShow::updateOrCreate(['permalink' => $showData->permalink], $showDataTobeSaved);

        // created
        if($this->showOnDB->wasRecentlyCreated) {
            TVShowCreated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Created;
        }
        else {
            // updated
            TVShowUpdated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Updated;
        }
    }

    // truncate extra data of fields
    private function prepareDataBeforeSave(&$showDataTobeSaved): void {
        $maxLenRules = ['name' => 150, 'network' => 30, 'country' => 30, 'permalink' => 150,
            'description' => 2500, 'status' => 30, 'thumb_url' => 255, 'image_url' => 255];

        foreach($maxLenRules as $field => $maxLen) {
            if(isset($showDataTobeSaved[$field]) && Str::length($showDataTobeSaved[$field]) > $maxLen) {
                $showDataTobeSaved[$field] = Str::limit($showDataTobeSaved[$field], $maxLen, "");
            }
        }
    }

    public function getCreationStatus(): CreateOrUpdateStatus {
        return $this->status;
    }

    public function getShowOnDB() : TVShow {
        return $this->showOnDB;
    }

    // get info of next ep date and put it on next_ep_date field
    private function updateNextEpisodeDate(TVShowData &$showData) {
        /// first we use `next_ep` field if it is filled with data
        if(isset($showData->next_ep?->air_date) && $showData->next_ep->air_date instanceof Carbon){
            $showData->next_ep_date = $showData->next_ep->air_date;
        }
        //then we try to find next ep data in `episodes` filed if there is any
        elseif(isset($showData->episodes) && count($showData->episodes)){
            $next_ep = $showData->episodes->toCollection()->firstWhere("air_date",">=", now()->startOfDay());
            if(!empty($next_ep)) {
                $showData->next_ep = $next_ep;
                $showData->next_ep_date = $next_ep->air_date;
            }
        }
    }

    // get info of last ep and put it on last_aired_ep and last_ep_date field
    private function updateLastAiredEpisode(TVShowData &$showData) {
        if(isset($showData->episodes) && count($showData->episodes)){
            $last_aired_ep = $showData->episodes->toCollection()->reverse()->firstWhere("air_date","<=", now()->endOfDay());
            if(!empty($last_aired_ep)) {
                $showData->last_aired_ep = $last_aired_ep;
                $showData->last_ep_date = $last_aired_ep->air_date;
            }
        }
    }
}

Enum CreateOrUpdateStatus {
    case Created;
    case Updated;
    case InvalidData;
}
