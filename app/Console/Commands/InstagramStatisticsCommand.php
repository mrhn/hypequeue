<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeInstagramUser;
use App\Models\InstagramUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InstagramStatisticsCommand extends Command
{
    const INSTAGRAM_QUEUE_NAME = 'instagram-%d';

    protected $signature = 'instagram:statistics';

    protected $description = 'Daily job to fetch user related information from the Instagram API';

    /**
     * Variable to indicate how many specific instagram queues there is.
     *
     * @var int
     */
    private $instagramQueues;

    private $throttling = 1;

    private $jobsProcessed = 0;

    /** @var Carbon */
    private $now;

    public function setThrottling(int $throttling): self
    {
        $this->throttling = $throttling;
    }

    public function __construct()
    {
        parent::__construct();

        $this->instagramQueues = config('instagram.queues');
        $this->now = now();
    }

    public function handle(): void
    {
        $instagramUsers = InstagramUser::all();

        while ($instagramUsers->isNotEmpty()) {
            foreach (range(1, $this->instagramQueues) as $queue) {
                /** @var InstagramUser $user */
                $user = $instagramUsers->shift();

                if (!$user) {
                    break;
                }

                $this->dispatchThrottledJob($user, $queue);
            }

            $this->jobsProcessed++;
        }
    }

    private function dispatchThrottledJob(InstagramUser $instagramUser, int $queue): void
    {
        $job = new ScrapeInstagramUser($instagramUser);
        $job->onQueue(sprintf(static::INSTAGRAM_QUEUE_NAME, $queue));
        $job->delay($this->now->copy()->addHour(floor($this->jobsProcessed / $this->throttling)));

        dispatch($job);
    }
}
