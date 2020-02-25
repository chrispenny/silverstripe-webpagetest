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
        $runTest = Submission\Model::findByTestId($testId);

        if ($runTest === null) {
            throw new InvalidArgumentException(sprintf('RunTest result with ID "%s" not found', $testId));
        }

        if (strlen($runTest->JsonUrl) === 0) {
            throw new InvalidArgumentException('Unable to fetch test results, as there is no JsonUrl');
        }

        $response = file_get_contents($runTest->JsonUrl);

        if (strlen($response) === 0) {
            throw new Exception('Received no file contents from JsonUrl');
        }

        $result = Result::create();
        $result->hydrateFromPayload($response);

        $model = Model::findOrCreate($testId);
        $model->hydrateFromResult($result);
        $model->write();

        return $model;
    }
}
