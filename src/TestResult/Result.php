<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Api;
use ChrisPenny\WebPageTest\TestResult\RunResult;
use GuzzleHttp\Psr7\Response;
use Opis\JsonSchema;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use stdClass;

/**
 * Class Result
 *
 * @package ChrisPenny\WebPageTest\TestResult
 */
class Result
{
    use Extensible;
    use Injectable;

    /**
     * @var int|null
     */
    private $behindCount;

    /**
     * @var int|null
     */
    private $bandwidthDown;

    /**
     * @var int|null
     */
    private $bandwidthUp;

    /**
     * @var int|null
     */
    private $completed;

    /**
     * @var string|null
     */
    private $connectivity;

    /**
     * @var int|null
     */
    private $firstViewOnly;

    /**
     * @var int|null
     */
    private $latency;

    /**
     * @var string|null
     */
    private $location;

    /**
     * @var int|null
     */
    private $mobile;

    /**
     * @var string|null
     */
    private $packetLossRate;

    /**
     * @var array|RunResult\Result[]
     */
    private $runResults = [];

    /**
     * @var string|int|null
     */
    private $statusCode;

    /**
     * @var string|null
     */
    private $statusText;

    /**
     * @var int|null
     */
    private $testsExpected;

    /**
     * @var string|null
     */
    private $testId;

    /**
     * @var int|null
     */
    private $testRuns;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @param Response $response
     * @return void
     */
    public function hydrateFromResponse(Response $response): void
    {
        /** @var string $contents */
        $contents = $response->getBody()->getContents();

        // We can't do anything if there is no response contents
        if (strlen($contents) === 0) {
            $this->setStatusCode(500);
            $this->setStatusText('No response content available');

            return;
        }

        /** @var stdClass $contents */
        $contents = json_decode($contents);

        // Validate that we have a valid basic response with codes and data
        $schema = JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../../schema/status.json'));

        $validator = new JsonSchema\Validator();

        /** @var JsonSchema\ValidationResult $result */
        $result = $validator->schemaValidation($contents, $schema);

        if (!$result->isValid()) {
            $this->setStatusCode(500);
            $this->setStatusText(Api\Helper::getValidationResultsAsJson($result));

            return;
        }

        // Set status results
        $this->setStatusCode($contents->statusCode);
        $this->setStatusText($contents->statusText);

        // Status codes in the 1xx and 2xx ranges are valid, anything outside of those are an error
        if ($this->getStatusCode() < 100 || $this->getStatusCode() >= 300) {
            return;
        }

        // Status codes in the 1xx indicate that the test is still processing
        if ($this->getStatusCode() >= 100 && $this->getStatusCode() < 200) {
            $this->hydratePendingResult($contents);

            $this->invokeWithExtensions('updateResultAfterHydratePending', $this);

            return;
        }

        $this->hydrateCompletedResult($contents);

        $this->invokeWithExtensions('updateResultAfterHydrateCompleted', $this);
    }

    /**
     * @param stdClass $contents
     */
    protected function hydratePendingResult(stdClass $contents): void
    {
        // Validate that we have a valid basic response with codes and data
        $schema = JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../../schema/result-pending.json'));

        $validator = new JsonSchema\Validator();

        /** @var JsonSchema\ValidationResult $result */
        $result = $validator->schemaValidation($contents, $schema);

        if (!$result->isValid()) {
            $this->setStatusCode(500);
            $this->setStatusText(Api\Helper::getValidationResultsAsJson($result));

            return;
        }

        /** @var stdClass $data */
        $data = $contents->data;

        if (property_exists($data, 'behindCount')) {
            $this->setBehindCount($data->behindCount);
        }

        $this->setTestId($data->testId);
        $this->setTestsExpected($data->testsExpected);
    }

    /**
     * @param stdClass $contents
     */
    protected function hydrateCompletedResult(stdClass $contents): void
    {
        // Validate that we have a valid basic response with codes and data
        $schema = JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../../schema/result-completed.json'));

        $validator = new JsonSchema\Validator();

        /** @var JsonSchema\ValidationResult $result */
        $result = $validator->schemaValidation($contents, $schema);

        if (!$result->isValid()) {
            $this->setStatusCode(500);
            $this->setStatusText(Api\Helper::getValidationResultsAsJson($result));

            return;
        }

        /** @var stdClass $data */
        $data = $contents->data;

        $this->setBandwidthDown($data->bwDown);
        $this->setBandwidthUp($data->bwUp);
        $this->setCompleted($data->completed);
        $this->setConnectivity($data->connectivity);
        $this->setFirstViewOnly($data->fvonly);
        $this->setLatency($data->latency);
        $this->setLocation($data->location);
        $this->setMobile($data->mobile);
        $this->setPacketLossRate($data->plr);
        $this->setTestRuns($data->testRuns);
        $this->setUrl($data->url);

        $this->hydrateRunResultsFromRunData(get_object_vars($data->runs));
    }

    /**
     * @param array $runs
     */
    public function hydrateRunResultsFromRunData(array $runs): void
    {
        foreach ($runs as $run) {
            if (property_exists($run, 'firstView')) {
                $result = RunResult\Result::create();
                $result->setIsRepeatView(0);
                $result->hydrateFromContents($run->firstView);

                $this->addRunResult($result);
            }

            if (property_exists($run, 'repeatView')) {
                $result = RunResult\Result::create();
                $result->setIsRepeatView(1);
                $result->hydrateFromContents($run->repeatView);

                $this->addRunResult($result);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getBehindCount(): ?int
    {
        return $this->behindCount;
    }

    /**
     * @param int|string|null $behindCount
     * @return Result
     */
    public function setBehindCount($behindCount): Result
    {
        $this->behindCount = (int) $behindCount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBandwidthDown(): ?int
    {
        return $this->bandwidthDown;
    }

    /**
     * @param int|string|null $bandwidthDown
     * @return Result
     */
    public function setBandwidthDown($bandwidthDown): Result
    {
        $this->bandwidthDown = (int) $bandwidthDown;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBandwidthUp(): ?int
    {
        return $this->bandwidthUp;
    }

    /**
     * @param int|string|null $bandwidthUp
     * @return Result
     */
    public function setBandwidthUp($bandwidthUp): Result
    {
        $this->bandwidthUp = (int) $bandwidthUp;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompleted(): ?int
    {
        return $this->completed;
    }

    /**
     * @param int|string|null $completed
     * @return Result
     */
    public function setCompleted($completed): Result
    {
        $this->completed = (int) $completed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConnectivity(): ?string
    {
        return $this->connectivity;
    }

    /**
     * @param string|null $connectivity
     * @return Result
     */
    public function setConnectivity(?string $connectivity): Result
    {
        $this->connectivity = $connectivity;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstViewOnly(): ?int
    {
        return $this->firstViewOnly;
    }

    /**
     * @param int|string|null $firstViewOnly
     * @return Result
     */
    public function setFirstViewOnly($firstViewOnly): Result
    {
        $this->firstViewOnly = (int) $firstViewOnly;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLatency(): ?int
    {
        return $this->latency;
    }

    /**
     * @param int|string|null $latency
     * @return Result
     */
    public function setLatency($latency): Result
    {
        $this->latency = (int) $latency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return Result
     */
    public function setLocation(?string $location): Result
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMobile(): ?int
    {
        return $this->mobile;
    }

    /**
     * @param int|string|null $mobile
     * @return Result
     */
    public function setMobile($mobile): Result
    {
        $this->mobile = (int) $mobile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPacketLossRate(): ?string
    {
        return $this->packetLossRate;
    }

    /**
     * @param string|null $packetLossRate
     * @return Result
     */
    public function setPacketLossRate(?string $packetLossRate): Result
    {
        $this->packetLossRate = $packetLossRate;

        return $this;
    }

    /**
     * @return array|RunResult\Result[]
     */
    public function getRunResults()
    {
        return $this->runResults;
    }

    /**
     * @param RunResult\Result $runResult
     * @return Result
     */
    public function addRunResult(RunResult\Result $runResult)
    {
        $this->runResults[] = $runResult;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int|string|null $statusCode
     * @return Result
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatusText(): ?string
    {
        return $this->statusText;
    }

    /**
     * @param string|null $statusText
     * @return Result
     */
    public function setStatusText(?string $statusText): Result
    {
        $this->statusText = $statusText;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTestsExpected(): ?int
    {
        return $this->testsExpected;
    }

    /**
     * @param int|string|null $testsExpected
     * @return Result
     */
    public function setTestsExpected($testsExpected): Result
    {
        $this->testsExpected = (int) $testsExpected;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTestId(): ?string
    {
        return $this->testId;
    }

    /**
     * @param string|null $testId
     * @return Result
     */
    public function setTestId(?string $testId): Result
    {
        $this->testId = $testId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTestRuns(): ?int
    {
        return $this->testRuns;
    }

    /**
     * @param int|string|null $testRuns
     * @return Result
     */
    public function setTestRuns($testRuns): Result
    {
        $this->testRuns = (int) $testRuns;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Result
     */
    public function setUrl(?string $url): Result
    {
        $this->url = $url;

        return $this;
    }
}
