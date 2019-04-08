<?php

namespace App\Models;

class InstagramDTO
{
    /** @var string */
    public $username;

    /** @var int */
    public $followers;

    /** @var int */
    public $followed;

    /** @var int */
    public $posts;

    public function __construct(string $username, int $followers, int $followed, int $posts)
    {
        $this->username = $username;
        $this->followers = $followers;
        $this->followed = $followed;
        $this->posts = $posts;
    }
}
