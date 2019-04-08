<?php

return [
    'token' => env('INSTAGRAM_TOKEN'),
    /*
    |--------------------------------------------------------------------------
    | Amount of separate workers for instagram
    |--------------------------------------------------------------------------
    |
    | Due to the throttling rates of instagram, we must be able to control which worker-server the job is running on.
    | This config indicates how many worker instances that are.
    | The naming convention to follow:instagram-queue-i starting with 1 until queues amount is reached.
    */
    'queues' => env('INSTAGRAM_QUEUES'),
];
