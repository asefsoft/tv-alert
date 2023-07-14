<?php

namespace App\TVSHow;

use App\Data\TVShowData;
use App\Events\TVShowCreated;
use App\Events\TVShowUpdated;
use App\Models\TVShow;

class CreateOrUpdateTVShow
{
    protected CreateOrUpdateStatus $status;
    private $showOnDB;

    public function __construct(protected array | TVShowData $tvShowInfo) {

        if(is_array($this->tvShowInfo)) {
            // parse array into tvshow data
            try {
                $showData = TVShowData::from($this->tvShowInfo);
            } catch (\Exception $e) {
                $this->status = CreateOrUpdateStatus::InvalidData;
                return;
            }
        }
        
        $this->createOrUpdate($showData);
    }

    private function createOrUpdate(TVShowData $showData) {

        // update last_check_date
        $showDataTobeSaved = array_merge($showData->toArray(), ['last_check_date' => now()]);

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

    public function getCreationStatus(): CreateOrUpdateStatus {
        return $this->status;
    }

    public function getShowOnDB() : TVShow {
        return $this->showOnDB;
    }
}

Enum CreateOrUpdateStatus {
    case Created;
    case Updated;
    case InvalidData;
}
