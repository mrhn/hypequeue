<?php

namespace Tests\Feature;

use App\Jobs\ScrapeInstagramUser;
use App\Models\InstagramUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InstagramStatisticsCommandTest extends TestCase
{

    public function testInstagramCommand()
    {
        factory(InstagramUser::class, 4)->create();

        $date = $this->mockDate();

        Queue::fake();

        $this->artisan('instagram:statistics --throttling=1');

        // Check if the 3 first jobs is pushed to 3 different queues
        foreach (range(1, config('instagram.queues')) as $queue) {
            Queue::assertPushed(ScrapeInstagramUser::class, function (ScrapeInstagramUser $job) use ($date, $queue) {
                return $job->queue === sprintf('instagram-%d', $queue)
                    && $date->equalTo($job->delay);
            });
        }

        // With the low test throttling the 4. job should be delayed by 1 hour to proove that the rate limiter works.
        Queue::assertPushed(ScrapeInstagramUser::class, function (ScrapeInstagramUser $job) use ($date, $queue) {
            return $job->queue === 'instagram-1'
                && $date->copy()->addHour()->equalTo($job->delay);
        });
    }

    private function mockDate(): Carbon
    {
        $knownDate = Carbon::create(2001, 1, 1, 12);
        Carbon::setTestNow($knownDate);

        return $knownDate;
    }
}
