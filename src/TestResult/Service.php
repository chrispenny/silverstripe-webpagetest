<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Api\Connector;
use ChrisPenny\WebPageTest\Submission;
use Exception;
use InvalidArgumentException;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;

/**
 * Class Service
 *
 * @package ChrisPenny\WebPageTest\TestResult
 */
class Service
{
    use Injectable;

    /**
     * @param string $testId
     * @return Model
     * @throws Exception
     */
    public function updateTestResult(string $testId): Model
    {
        $testSubmission = Submission\Model::findByTestId($testId);

        if ($testSubmission === null) {
            throw new InvalidArgumentException(sprintf('RunTest result with ID "%s" not found', $testId));
        }

        if (strlen($testSubmission->JsonUrl) === 0) {
            throw new InvalidArgumentException('Unable to fetch test results, as there is no JsonUrl');
        }

        $request = Request::create($testSubmission->JsonUrl);

        $connector = Injector::inst()->get(Connector::class);
        $response = $connector->send($request);

        $result = Result::create();
        $result->hydrateFromResponse($response);

        $testResult = Model::findOrCreate($testId);
        $testResult->hydrateFromResult($result);
        $testResult->write();

        $testSubmission->TestResultID = $testResult->ID;

        if ($testResult->StatusCode >= 100 && $testResult->StatusCode < 200) {
            $testSubmission->ProcessedStatus = Submission\Model::PROCESSED_STATUS_PENDING;
        } elseif ($testResult->StatusCode >= 200 && $testResult->StatusCode < 300) {
            $testSubmission->ProcessedStatus = Submission\Model::PROCESSED_STATUS_SUCCESS;
        } else {
            $testSubmission->ProcessedStatus = Submission\Model::PROCESSED_STATUS_FAILURE;
        }

        $testSubmission->write();

        return $testResult;
    }
}
