<?php

namespace App\Services;

use App\Models\InstagramDTO;
use App\Models\InstagramUser;
use App\Models\InstagramUserStatistic;
use Carbon\Carbon;

class InstagramStatisticsService
{
    public function find(InstagramUser $user, Carbon $date): ?InstagramUserStatistic
    {
        /** @var InstagramUserStatistic $model */
        $model = InstagramUserStatistic::query()
            ->where('instagram_user_id', $user->id)
            ->whereDate('date', $date)
            ->first();

        return $model;
    }

    public function save(InstagramUser $user, Carbon $date, InstagramDTO $data): InstagramUserStatistic
    {
        if ($statistic = $this->find($user, $date)) {
            return $statistic;
        }

        /** @var  InstagramUserStatistic$statistic */
        $statistic = app(InstagramUserStatistic::class);

        $statistic->fill(
            array_merge([
                'date' => $date,
            ], $data->toArray()
            )
        );

        $user->statistics()->save($statistic);

        return $statistic;
    }
}
