# Hypequeue

## Rate limiting
Internet information states that instagram api has a rate limiting of 500 per hour, this seems reasonable compared to normal rate limiting strategies.

This means that the 20000 handles that needs to be parsed will roughly take 40-50 hours, also due to failing jobs and retries i strive to having a rate limit of 400 per hour. Since this needs to be running every day, it seems like a bad catch game to rate limit it. My analysis of the best solution for this, is to have a static definition of n queues. Etc. queue-1 and queue-2 running on individual workers, so worker-1 will run queue-1 and vice versa. Requiring each to have a seperat set of keys and ip.

In general i do not like this design approach but felt like the cleanest way of battling the rate limiting.

I like redis, for ease of installation and time, i went with database queue driver.

## Problems with queues
How to handle them, from experience working with queues one of the more imporant aproaches is to being able to restart the whole job chain. What if instagram has down time or the keys are expired? 

To combat this i build a composite key on statistics based on day and user_id, when the job is running it checks if the entry is already there, if it is it does not created duplicate data, this makes it possible to rerun the job chain if something goes run. In my experience this usually comes in handy, when data is bad or similar.

#More to come
