<?php

namespace ChrisPenny\WebPageTest\SubmitTest;

use ChrisPenny\WebPageTest\TestResult;
use SilverStripe\ORM\DataObject;

/**
 * Class WebPageTestResponse
 *
 * @package ChrisPenny\WebPageTest\Model
 * @property string $DetailCsvUrl
 * @property string $JsonUrl
 * @property string $OwnerKey
 * @property int $StatusCode
 * @property string $StatusText
 * @property string $SummaryCsvUrl
 * @property string $TestId
 * @property string $TestedUrl
 * @property int $TestResultID
 * @property string $UserUrl
 * @property string $XmlUrl
 * @method TestResult\Model TestResult()
 */
class Model extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'DetailCsvUrl' => 'Varchar(255)',
        'JsonUrl' => 'Varchar(255)',
        'OwnerKey' => 'Varchar(255)',
        'StatusCode' => 'Int',
        'StatusText' => 'Text',
        'SummaryCsvUrl' => 'Varchar(255)',
        'TestId' => 'Varchar(255)',
        'TestedUrl' => 'Text',
        'UserUrl' => 'Varchar(255)',
        'XmlUrl' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'TestResult' => TestResult\Model::class,
    ];

    /**
     * @var string
     */
    private static $table_name = 'WebPageTestRun';

    /**
     * @var string
     */
    private static $singular_name = 'RunTest Response';

    /**
     * @var string
     */
    private static $plural_name = 'RunTest Responses';

    /**
     * @param Result $result
     */
    public function hydrateFromResult(Result $result): void
    {
        // Set status results
        $this->StatusCode = $result->getStatusCode();
        $this->StatusText = $result->getStatusText();

        // Set contents results
        $this->DetailCsvUrl = $result->getDetailCsvUrl();
        $this->JsonUrl = $result->getJsonUrl();
        $this->OwnerKey = $result->getOwnerKey();
        $this->SummaryCsvUrl = $result->getSummaryCsvUrl();
        $this->TestId = $result->getTestId();
        $this->UserUrl = $result->getUserUrl();
        $this->XmlUrl = $result->getXmlUrl();
    }

    /**
     * @param string $testId
     * @return Model|null
     */
    public static function findByTestId(string $testId): ?Model
    {
        /** @var Model $model */
        $model = static::get()
            ->filter('TestId', $testId)
            ->first();

        return $model;
    }
}
