<?php

namespace App\Jobs;

use App\Models\InstagramUser;
use App\Services\InstagramService;
use App\Services\InstagramStatisticsService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScrapeInstagramUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var InstagramUser */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InstagramUser $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(InstagramService $instagramService, InstagramStatisticsService $statisticsService): void
    {
        $data = $instagramService->scrape($this->user);

        $statisticsService->save($this->user, Carbon::now(), $data);
    }
}
