<?php

namespace Tests\Unit;

use App\Models\InstagramUser;
use App\Services\InstagramService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstagramServiceTest extends TestCase
{
    /** @var InstagramService */
    private $instagramService;

    public function setUp(): void
    {
        parent::setUp();

        $this->instagramService = app(InstagramService::class);
    }

    /**
     * Test instagram scraping
     * Not mocked for simplicity
     * Using own user, since i do not use instagram, values will most likely not chance
     *
     * @return void
     */
    public function testScrapeInstagram()
    {
        $user = factory(InstagramUser::class, 1)->create()[0];
        $dto = $this->instagramService->scrape($user);

        $this->assertEquals(45, $dto->followers);
        $this->assertEquals(53, $dto->followed);
        $this->assertEquals(2, $dto->posts);
    }
}
