<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeInstagramUser;
use App\Models\InstagramUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InstagramStatisticsCommand extends Command
{
    const INSTAGRAM_QUEUE_NAME = 'instagram-%d';

    protected $signature = 'instagram:statistics {--throttling=}';

    protected $description = 'Daily job to fetch user related information from the Instagram API';

    /**
     * Variable to indicate how many specific instagram queues there is.
     *
     * @var int
     */
    private $instagramQueues;

    private $throttling = 400;

    private $jobsProcessed = 0;

    /** @var Carbon */
    private $now;

    public function __construct()
    {
        parent::__construct();

        $this->instagramQueues = config('instagram.queues');
    }

    public function handle(): void
    {
        $this->getThrottlingOption();

        $this->now = Carbon::now();
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

    private function getThrottlingOption(): void
    {
        if (
            $this->hasOption('throttling')
            && is_int((int) $this->option('throttling'))
            && (int) $this->option('throttling') !== 0
        ) {
            $this->throttling = (int) $this->option('throttling');
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
