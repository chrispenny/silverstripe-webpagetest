<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Submission;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataList;

/**
 * Class Task
 *
 * @codeCoverageIgnore
 * @package ChrisPenny\WebPageTest\TestResult
 */
class Task extends BuildTask
{
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    private static $segment = 'webpagetest-fetch-results';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'WebPageTest - Fetch Results';
    }

    /**
     * This task will look up your current "Pending" test Submissions, and will attempt to retrieve and process the
     * results.
     *
     * If a test has not been performed by WebPageTest yet, then the status of the Result will be updated with whatever
     * info we are provided (EG: position in the queue).
     *
     * @param HTTPRequest|mixed $request
     * @throws \Exception
     */
    public function run($request): void
    {
        $service = Service::create();

        /** @var DataList|Submission\Model[] $pendingSubmissions */
        $pendingSubmissions = Submission\Model::get()
            ->filter('ProcessedStatus', Submission\Model::PROCESSED_STATUS_PENDING);

        foreach ($pendingSubmissions as $submission) {
            $service->updateTestResult($submission->TestId);
        }
    }
}
