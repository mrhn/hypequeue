<?php

namespace Tests\Unit;

use App\Jobs\ScrapeInstagramUser;
use App\Models\InstagramUser;
use App\Services\InstagramService;
use App\Services\InstagramStatisticsService;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScrapeInstagramUserJobTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testScrapeInstagram()
    {
        /** @var InstagramUser $user */
        $user = factory(InstagramUser::class, 1)->create()[0];
        Carbon::setTestNow(Carbon::now());

        $scrapeJob = new ScrapeInstagramUser($user);
        $scrapeJob->handle(app(InstagramService::class), app(InstagramStatisticsService::class));

        $this->assertDatabaseHas(
            'instagram_user_statistics',
            [
                'date' => Carbon::getTestNow(),
                'followers' => 45,
                'followed' => 53,
                'posts' => 2,
            ]
        );
    }
}
