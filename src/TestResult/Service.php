<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Submission;
use Exception;
use InvalidArgumentException;
use SilverStripe\Core\Injector\Injectable;

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

        $response = file_get_contents($testSubmission->JsonUrl);

        if (strlen($response) === 0) {
            throw new Exception('Received no file contents from JsonUrl');
        }

        $result = Result::create();
        $result->hydrateFromPayload($response);

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
