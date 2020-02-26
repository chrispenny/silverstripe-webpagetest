# silverstripe-webpagetest

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
multiple different URLs. We'll get to that later.

### Recommended configuration

- `location` (if you don't provide a location, then it will be random, this makes your ongoing tests a bit random, as
you'll likely get different results from different locations)

Available locations can be found here:
https://webpagetest.org/getLocations.php?f=html

### Other configuration

Out of the box, the module supports you sending any/all of the currently supported GET params in WebPageTest's RESTful
API.

[Available configuration](docs/available-config.md)

## Using the /dev/tasks

There are two `/dev/tasks` available for you to use.

- `ChrisPenny\WebPageTest\Submission\Task`
- `ChrisPenny\WebPageTest\TestResult\Task`

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

There are three possible statuses for these Submission records. These indicate the status of the Test itself.
 
- Pending (WebPageTest is still processing our request - they haven't tested our URL yet)
- Success (The test has been completed by WebPageTest, and we should have the results)
- Failure (Something went wrong ...)

### `TestResult\Task`

When run, this task looks at what Submissions are set as `Pending`. It then requests an update from WebPageTest on what
the status of that test is.

There is (generally) three possibility:

- You are still in the queue
- Your test is currently running
- Your test has completed

In the case of the first two, we will create a Test Result record, and set it's data to indicate to you where in the
queue you are, or that the test is underway.

The the case where the test has completed, we will populate the Test Result record with the result data that we
(currently) care about.

There is so... SO, much data in these test results. So for now, we just have the basics being stored.

I welcome any and all to add support for us to store for of the data from the result data.

## Viewing your submissions and test results

The ModelAdmin can be found under: `/admin/performance-tests/`

This will list out all of the Submissions that you have made.

If you click on one of your Submissions, you'll see the current status, and once there is a result, a `Result` tab will
show up, where you can see the Result and any of its "Runs" (you may have requested that WebPageTest performs multiple
runs, and by default, it also performs a "first view" and "repeat view" test).  
