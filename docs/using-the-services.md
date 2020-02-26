# Using the services

There are two Services provided:

- `Submission\Service`
- `TestResult\Service`

## `Submission\Service`

This service really only has one purpose: To send test requests/submissions to WebPageTest.

If you just want to send a submission using your default configuration, then you can simply do this:

```php
$service = Submission\Service::create();
$service->requestTest();
```

But, `requestTest()` does also accept a `Request` being passed in as a parameter, so, if you would like to send a
request with different settings, then you can first create the `Request`, and then send it through `requestTest()`.

For example:

```php
$request = Submission\Request::create();
$request->setUrl('http://www.some-other-url.com');
$request->setLocation('ec2-ap-southeast-2:Chrome');
$request->setFirstViewOnly(1);
$request->setMobile(1);
// etc

$service = Submission\Service::create();
$service->requestTest($request);
```

There is also a method available on `Submission\Request` to populate from configuration, if you'd like to use that as
a starting point, and then manipulate from there.

For example:

```php

$request = Submission\Request::create();
$request->hydrateFromConfiguration();
$request->setFirstViewOnly(1);
$request->setMobile(1);
// etc

$service = Submission\Service::create();
$service->requestTest($request);
```

## `TestResult\Service`

The purpose of this service is to update the status of one of your results. It requires a `TestId` that matches one of
your submissions.

```php
// $testId = 1286293461082741982

$service = TestResult\Service::create();
$service->updateTestResult($testId);
```

The service will then do all of the work to update your Result record.
