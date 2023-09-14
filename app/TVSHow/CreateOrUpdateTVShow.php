<?php

namespace App\TVShow;

use App\Data\TVShowData;
use App\Events\LastAiredEpisodeDateUpdated;
use App\Events\NextEpisodeDateUpdated;
use App\Events\TVShowCreated;
use App\Events\TVShowUpdated;
use App\Models\TVShow;
use Carbon\Carbon;
use Str;

class CreateOrUpdateTVShow
{
    protected CreateOrUpdateStatus $status;

    private $showOnDB;

    public function __construct(protected array|TVShowData $tvShowInfo)
    {

        if (is_array($this->tvShowInfo)) {
            // parse array into tvshow data
            try {
                $tvShowInfo = TVShowData::from($this->tvShowInfo);
            } catch (\Exception $e) {
                $this->status = CreateOrUpdateStatus::InvalidData;

                return;
            }
        }

        // if show already exist on our db then get its data
        $this->showOnDB = TVShow::getShowByPermalink($tvShowInfo->permalink);

        $this->createOrUpdate($tvShowInfo);
    }

    private function createOrUpdate(TVShowData $showData)
    {

        $this->updateNextEpisodeDate($showData);
        $this->updateLastAiredEpisode($showData);

        // update last_check_date
        $showDataTobeSaved = array_merge($showData->toArray(), ['last_check_date' => now()]);

        $this->prepareDataBeforeSave($showDataTobeSaved);

        $this->showOnDB = TVShow::updateOrCreate(['permalink' => $showData->permalink], $showDataTobeSaved);

        // created event
        if ($this->showOnDB->wasRecentlyCreated) {
            TVShowCreated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Created;
        } else {
            // updated event
            TVShowUpdated::dispatch($this->showOnDB);
            $this->status = CreateOrUpdateStatus::Updated;
        }
    }

    // truncate extra data of fields
    private function prepareDataBeforeSave(&$showDataTobeSaved): void
    {
        $maxLenRules = ['name' => 150, 'network' => 30, 'country' => 30, 'permalink' => 150,
            'description' => 2500, 'status' => 30, 'thumb_url' => 255, 'image_url' => 255];

        foreach ($maxLenRules as $field => $maxLen) {
            if (isset($showDataTobeSaved[$field]) && Str::length($showDataTobeSaved[$field]) > $maxLen) {
                $showDataTobeSaved[$field] = Str::limit($showDataTobeSaved[$field], $maxLen, '');
            }
        }
    }

    public function getCreationStatus(): CreateOrUpdateStatus
    {
        return $this->status;
    }

    public function getShowOnDB(): TVShow
    {
        return $this->showOnDB;
    }

    // get info of next ep date and put it on next_ep_date field
    private function updateNextEpisodeDate(TVShowData &$showData)
    {
        $origDate = $this->showOnDB?->next_ep_date;
        $foundDate = null;

        /// first we use `next_ep` field if it is filled with data
        if (isset($showData->next_ep?->air_date) && $showData->next_ep->air_date instanceof Carbon) {
            $foundDate = $showData->next_ep->air_date;
        }
        // then we try to find next ep data in `episodes` field if there is any
        elseif (isset($showData->episodes) && count($showData->episodes)) {
            $next_ep = $showData->episodes->toCollection()->firstWhere('air_date', '>=', now()->startOfDay());
            if (! empty($next_ep)) {
                $showData->next_ep = $next_ep;
                $foundDate = $next_ep->air_date;
            }
        }

        // found a next ep date, then release event
        if (! empty($foundDate)) {
            $showData->next_ep_date = $foundDate;
            if ($origDate != $foundDate) {
                NextEpisodeDateUpdated::dispatch($this->showOnDB, $origDate, $foundDate);
            }
        }
    }

    // get info of last ep and put it on last_aired_ep and last_ep_date field
    private function updateLastAiredEpisode(TVShowData &$showData)
    {

        if (isset($showData->episodes) && count($showData->episodes)) {
            $last_aired_ep = $showData->episodes->toCollection()->reverse()->firstWhere('air_date', '<=', now()->endOfDay());

            if (! empty($last_aired_ep)) {
                // update date
                $origDate = $this->showOnDB?->last_ep_date;
                $foundDate = $last_aired_ep->air_date;
                $showData->last_aired_ep = $last_aired_ep;
                $showData->last_ep_date = $foundDate;

                // event
                if ($foundDate != $origDate) {
                    LastAiredEpisodeDateUpdated::dispatch($this->showOnDB, $origDate, $foundDate);
                }
            }
        }
    }
}
