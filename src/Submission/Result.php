<?php

namespace ChrisPenny\WebPageTest\Submission;

use ChrisPenny\WebPageTest\Api;
use GuzzleHttp\Psr7\Response;
use Opis\JsonSchema;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use stdClass;

/**
 * Class Result
 *
 * @package ChrisPenny\WebPageTest\RunTest
 */
class Result
{
    use Extensible;
    use Injectable;

    /**
     * @var string|null
     */
    private $detailCsvUrl;

    /**
     * @var string|null
     */
    private $jsonUrl;

    /**
     * @var string|null
     */
    private $ownerKey;

    /**
     * @var string|int|null
     */
    private $statusCode;

    /**
     * @var string|null
     */
    private $statusText;

    /**
     * @var string|null
     */
    private $summaryCsvUrl;

    /**
     * @var string|null
     */
    private $testId;

    /**
     * @var string|null
     */
    private $resultOverviewUrl;

    /**
     * @var string|null
     */
    private $xmlUrl;

    /**
     * @param Response $response
     * @return void
     */
    public function hydrateFromResponse(Response $response): void
    {
        /** @var string $payload */
        $payload = $response->getBody()->getContents();

        // We can't do anything if there is no response contents
        if (strlen($payload) === 0) {
            $this->setStatusCode(500);
            $this->setStatusText('No response content available');

            return;
        }

        /** @var stdClass $contents */
        $contents = json_decode($payload);

        // Validate that we have a valid basic response with codes and data
        $schema = JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../../schema/status.json'));

        $validator = new JsonSchema\Validator();
        $result = $validator->schemaValidation($contents, $schema);

        if (!$result->isValid()) {
            $this->setStatusCode(500);
            $this->setStatusText(Api\Helper::getValidationResultsAsJson($result));

            return;
        }

        $this->setStatusCode($contents->statusCode);
        $this->setStatusText($contents->statusText);

        if ($this->getStatusCode() !== 200) {
            return;
        }

        $schema = JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../../schema/submission.json'));

        $result = $validator->schemaValidation($contents, $schema);

        if (!$result->isValid()) {
            $this->setStatusCode(500);
            $this->setStatusText(Api\Helper::getValidationResultsAsJson($result));

            return;
        }

        /** @var stdClass $data */
        $data = $contents->data;

        // Set contents results
        $this->setDetailCsvUrl($data->detailCSV);
        $this->setJsonUrl($data->jsonUrl);
        $this->setOwnerKey($data->ownerKey);
        $this->setSummaryCsvUrl($data->summaryCSV);
        $this->setTestId($data->testId);
        $this->setResultOverviewUrl($data->userUrl);
        $this->setXmlUrl($data->xmlUrl);

        $this->invokeWithExtensions('updateResultAfterHydration', $this);
    }

    /**
     * @return string|null
     */
    public function getDetailCsvUrl(): ?string
    {
        return $this->detailCsvUrl;
    }

    /**
     * @param string|null $detailCsvUrl
     * @return Result
     */
    public function setDetailCsvUrl(?string $detailCsvUrl): Result
    {
        $this->detailCsvUrl = $detailCsvUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getJsonUrl(): ?string
    {
        return $this->jsonUrl;
    }

    /**
     * @param string|null $jsonUrl
     * @return Result
     */
    public function setJsonUrl(?string $jsonUrl): Result
    {
        $this->jsonUrl = $jsonUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOwnerKey(): ?string
    {
        return $this->ownerKey;
    }

    /**
     * @param string|null $ownerKey
     * @return Result
     */
    public function setOwnerKey(?string $ownerKey): Result
    {
        $this->ownerKey = $ownerKey;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param string|int|null $statusCode
     * @return Result
     */
    public function setStatusCode($statusCode): Result
    {
        $this->statusCode = (int) $statusCode;

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
     * @return string|null
     */
    public function getSummaryCsvUrl(): ?string
    {
        return $this->summaryCsvUrl;
    }

    /**
     * @param string|null $summaryCsvUrl
     * @return Result
     */
    public function setSummaryCsvUrl(?string $summaryCsvUrl): Result
    {
        $this->summaryCsvUrl = $summaryCsvUrl;

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
     * @return string|null
     */
    public function getResultOverviewUrl(): ?string
    {
        return $this->resultOverviewUrl;
    }

    /**
     * @param string|null $resultOverviewUrl
     * @return Result
     */
    public function setResultOverviewUrl(?string $resultOverviewUrl): Result
    {
        $this->resultOverviewUrl = $resultOverviewUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getXmlUrl(): ?string
    {
        return $this->xmlUrl;
    }

    /**
     * @param string|null $xmlUrl
     * @return Result
     */
    public function setXmlUrl(?string $xmlUrl): Result
    {
        $this->xmlUrl = $xmlUrl;

        return $this;
    }
}
