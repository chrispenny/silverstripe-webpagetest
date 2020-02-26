<?php

namespace ChrisPenny\WebPageTest\Submission;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;

/**
 * Class Task
 *
 * @codeCoverageIgnore
 * @package ChrisPenny\WebPageTest\Submission
 */
class Task extends BuildTask
{
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    private static $segment = 'webpagetest-submit-test';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'WebPageTest - Submit Test';
    }

    /**
     * It's important to note that tests through WebPageTest do not necessarily get processed straight away. If you are
     * using the free service, then your request will go into a queue, and it will be processed when you're at the front
     * of that queue.
     *
     * The tests then (of course) take a while to complete. This is why we have a separate Task for retrieving the data
     * for the tests.
     *
     * This Task is only going to send a request for your test to be queued. A corresponding DB record will also be
     * created. You can see the history of them in the CMS.
     *
     * @param HTTPRequest|mixed $request
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function run($request): void
    {
        $service = Service::create();
        $service->requestTest();
    }
}
