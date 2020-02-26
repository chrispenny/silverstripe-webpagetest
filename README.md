# silverstripe-webpagetest

The goal of this module is to give you a way to queue and view WebPageTest performance tests. This is still early days
though - we have some basic functionality, but this definitely isn't replacing a paid for testing service (at least
not yet).

- [Installation](#installation)
- [Using the dev/tasks](#using-the-devtasks)
- [Viewing your submissions and results](#viewing-your-submissions-and-results)
- [Supported request configuration](docs/available-config.md)
- [Using the Services](docs/using-the-services.md)

## Out of box

- Provides Request and Service classes that you can use to queue and consume WebPageTest performance test results.
- Provides dev/tasks that can be used to queue and consume WebPageTest performance test results on a schedule.
- Basic ModelAdmin to view your submissions and WebPageTest performance test results.

## Still to come

- Reports!
- Graphs?
- Consume more of the performance test result data
- Unit tests (... forgive me)

## Installation

```
$ composer require chrispenny/silverstripe-webpagetest
```

### Getting an API key for WebPageTest

If you would like to use this module with the public API, then you will need to request an API key. There are no
requirements for getting one, it's just to make sure that folks are not abusing the API.

https://webpagetest.org/getkey.php

### Required configuration

There are different ways that you can use this module, but, assuming you just want to go ahead and use the provided
`/dev/tasks`, you'll need to add two configuration settings before you can go any further.

- `key` (the API key that you requested above)
- `url` (the URL that you would like WebPageTest to run the test against)

So, out of the box, we're only running performance tests against the same, single, URL.

In these early stages of the module, you'll need to implement your own `/dev/task` if you wish you support testing
multiple different URLs. See [Using the Services](docs/using-the-services.md) for details.

### Default configuration

By default, in `_config/submission.yml`, we have set a couple of default configs:

```
base_url: http://www.webpagetest.org/runtest.php
format: json
```

The `base_url` is the standard request URL for WebPageTest.
The `format` that this module supports is `json`.

### Recommended configuration

- `location` (if you don't provide a location, then it will be random, this makes your ongoing tests a bit random, as
you'll likely get different results from different locations)

Available locations can be found here:
https://webpagetest.org/getLocations.php?f=html

### Other configuration

Out of the box, the module supports you sending any/all of the currently supported GET params in WebPageTest's RESTful
API.

[Available configuration](docs/available-config.md)

## Using the dev/tasks

There are two `dev/tasks` available for you to use.

- `ChrisPenny\WebPageTest\Submission\Task`
- `ChrisPenny\WebPageTest\TestResult\Task`

Cron support is not adding as part of this module, but you could set up a cron to (say) run the `Submission\Task` once a
day (resulting in one new WebPageTest being requested per day), and you might want to set up `TestResult\Task` to run
every hour or so.

A full description of what each task does is below.

### `Submission\Task`

When run, this task will send a single GET request to WebPageTest requesting that they queue a test matching what you
have specified in your configuration.

The documentation for the RESTful API on WebPageTest will show you what the default values are if one is not supplied:
https://sites.google.com/a/webpagetest.org/docs/advanced-features/webpagetest-restful-apis

It's important to note that tests through WebPageTest do not necessarily get processed straight away. If you are
using the free service, then your request will go into a queue, and it will be processed when you're at the front
of that queue. The tests then also (of course) take a while to complete. This is why we have a separate Task for
retrieving the data for the tests.

Once we receive the "OK" (or otherwise) from WebPageTest, we create a Test Submission record, which you can see in the
ModelAdmin under `/admin/performance-tests/`.

There are three possible statuses for these Submission records. These indicate the status of the test itself.
 
- Pending (WebPageTest is still processing our request - they haven't tested our URL yet)
- Success (The test has been completed by WebPageTest, and we should have the results)
- Failure (Something went wrong ...)

If you want one new performance test run each day, then you would want to set up a cron triggering this task to run once
each day.

### `TestResult\Task`

When run, this task looks at what Submissions are current set with a status of `Pending`. It then requests an update
from WebPageTest on what the status of that test is.

There is (generally) three possibility:

- You are still in the queue, and your test has not begun yet
- Your test is currently running
- Your test has completed

In the case of the first two, we will create (or update) a Test Result record, and set its data to indicate to you where
in the queue you are, or that the test is underway.

In the case where the test has completed, we will populate the Test Result record with the result data that we
(currently) care about.

There is so... SO, much data in these test results. So for now, we just have the basics being stored. I welcome any and
all to add support for us to store for of the data from the result data.

If you are running a cron once a day to trigger a test submission, you may want to queue the retrieval of the result,
either for some time after the submission takes place, or, you could just trigger this task to run (say) every 20
minutes. The task does not do anything if there are no `Pending` submissions.

## Viewing your submissions and results

The ModelAdmin can be found under: `/admin/performance-tests/`

This will list out all of the Submissions that you have made.

If you click on one of your Submissions, you'll see the current status, and once there is a result, a `Result` tab will
show up, where you can see the Result and any of its "Runs" (you may have requested that WebPageTest performs multiple
runs, and by default, it also performs a "first view" and "repeat view" test).  
