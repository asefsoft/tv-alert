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

    public function __construct(protected array $tvShowInfo) {
        $this->createOrUpdate();
    }

    private function createOrUpdate() {
        $showData = TVShowData::from($this->tvShowInfo);
        $this->showOnDB = TVShow::updateOrCreate(['permalink' => $showData->permalink], $showData->toArray());

        if($this->showOnDB->wasRecentlyCreated) {
            TVShowCreated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Created;
        }
        else {
            TVShowUpdated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Updated;
        }
    }

    /**
     * @return CreateOrUpdateStatus
     */
    public function getCreationStatus(): CreateOrUpdateStatus {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getShowOnDB() : TVShow {
        return $this->showOnDB;
    }
}

Enum CreateOrUpdateStatus {
    case Created;
    case Updated;
}
