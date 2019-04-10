# Hypequeue

## Rate limiting
Internet information states that instagram api has a rate limiting of 500 per hour, this seems reasonable compared to normal rate limiting strategies.

This means that the 20000 handles that needs to be parsed will roughly take 40-50 hours, also due to failing jobs and retries i strive to having a rate limit of 400 per hour. Since this needs to be running every day, it seems like a bad catch game to rate limit it. My analysis of the best solution for this, is to have a static definition of n queues. Etc. queue-1 and queue-2 running on individual workers, so worker-1 will run queue-1 and vice versa. Requiring each to have a seperat set of keys and ip.

In general i do not like this design approach but felt like the cleanest way of battling the rate limiting.

I like redis, for ease of installation and time, i went with database queue driver.

## Problems with queues
How to handle them, from experience working with queues one of the more imporant aproaches is to being able to restart the whole job chain. What if instagram has down time or the keys are expired? 

To combat this i build a composite key on statistics based on day and user_id, when the job is running it checks if the entry is already there, if it is it does not created duplicate data, this makes it possible to rerun the job chain if something goes run. In my experience this usually comes in handy, when data is bad or similar.

Why did i not choose the Laravel rate limiter, it abuses retrying jobs and as said in the documentation it can provide trouble and i have already tried to build a system only with the laravel rate limiter and it was not good. You could thou argue that a system with a combination of laravel rate limiter and a customer delayed solutions as i have would could be smart.
## Which edge cases could occour // Regressions

- Instagram API going down
- Throttling by ourselves
- Errors in Instagram API

Most of these things boils down to retrying, due to each row being checked for uniqueness and except something goes really wrong in dispatching multiple commands simultaneity race conditions should not be a problem. Retrying should not be a problem.

Failures on API or unexpected throttling, right now it expects most executions to go smooth, this setup 100 jobs per 400 can fail once without any problems. My best solution to make this more sturdy, when the errors occour release the job back on the queue based on similar principles to the already existing rate limiter. For solving how many jobs that already had been executed on each queue, use the cache for saving it.

## What’s your testing approach for those edge-cases and regressions?

The main problem is about errors from the instagram api, these can be mocked in fancy ways with [GuzzleMock](http://docs.guzzlephp.org/en/stable/testing.html). Still usually i run my test suite with sync, so in the spirit of this whole interview (not always testing features), i feel this is the solution that is realistic i would hand in, if i got this task as a task that needed to be done fairly quickly tests included. This assignment took me aproximitly 6 hours, i have ranned this example in the command line with the php artisan queue:work --queue=instagram-n command.
