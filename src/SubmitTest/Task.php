<?php

namespace ChrisPenny\WebPageTest\SubmitTest;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;

/**
 * Class TestTask
 *
 * @codeCoverageIgnore
 * @package App\Tasks
 */
class Task extends BuildTask
{
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    private static $segment = 'webpagetest-runtest';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'WebPageTest - Run Test';
    }

    /**
     * It's important to note that tests through WebPageTest do not necessarily get processed straight away. If you are
     * using the free service, then your request will go into a queue, and it will be processed when you're at the front
     * of that queue.
     *
     * The tests then (of course) take a while to complete. This is why we have delayed and separate Tasks/etc for
     * retrieving the data for the tests.
     *
     * This Task is only going to send a request for your test to be queued. A corresponding DB record will also be
     * created. You can see the history of them in the CMS.
     *
     * @param HTTPRequest|mixed $request
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function run($request): void
    {
        $service = new Service();
        $service->requestTest();
    }
}
