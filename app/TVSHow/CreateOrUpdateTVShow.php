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
        if(isset($showData->next_ep?->air_date) && $showData->next_ep->air_date instanceof Carbon){
            $showData->next_ep_date = $showData->next_ep->air_date;
        }
    }
}

Enum CreateOrUpdateStatus {
    case Created;
    case Updated;
    case InvalidData;
}
