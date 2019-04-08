<?php

namespace App\Services;

use App\Models\InstagramDTO;
use App\Models\InstagramUser;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use stdClass;

/**
 * Class InstagramService
 *
 * Using own personal instagram api access
 * Presuming in the task of creation this for the company some other solution must be made,
 * for the purpose of this test this should be "fine"
 *
 * @package App\Services
 */
class InstagramService
{
    /** @var  */
    private $guzzle;

    const URL = 'https://api.instagram.com/v1/users/self/';

    public function __construct(Client $client)
    {
        $this->guzzle = $client;
    }

    public function scrape(InstagramUser $user) : InstagramDTO
    {
        // Perfect scenario i would replace the username in the url
        $result = $this->guzzle->get(
            sprintf(static::URL, $user->handle),
            [
                RequestOptions::QUERY => [
                    'access_token' => config('instagram.token')
                ]
            ]
        )
            ->getBody()
            ->getContents();

        /** @var stdClass $json */
        $json = json_decode($result)->data;

        return new InstagramDTO(
            $user->handle, // should be $json->username if the api was not faked
            $json->counts->followed_by,
            $json->counts->follows,
            $json->counts->media
        );
    }
}
