<?php

namespace ChrisPenny\WebPageTest\Submission;

use ChrisPenny\WebPageTest\TestResult;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

/**
 * Class Model
 *
 * @package ChrisPenny\WebPageTest\Submission
 * @property string $DetailCsvUrl
 * @property string $JsonUrl
 * @property string $OwnerKey
 * @property string $ResultOverviewUrl
 * @property string $RequestUrl
 * @property int $StatusCode
 * @property string $StatusText
 * @property string $SummaryCsvUrl
 * @property string $TestId
 * @property string $TestedUrl
 * @property int $TestResultID
 * @property string $XmlUrl
 * @method TestResult\Model TestResult()
 */
class Model extends DataObject implements PermissionProvider
{
    public const PERMISSION_PERFORMANCE_TEST_CREATE = 'PERMISSION_PERFORMANCE_TEST_CREATE';
    public const PERMISSION_PERFORMANCE_TEST_DELETE = 'PERMISSION_PERFORMANCE_TEST_DELETE';
    public const PERMISSION_PERFORMANCE_TEST_EDIT = 'PERMISSION_PERFORMANCE_TEST_EDIT';
    public const PERMISSION_PERFORMANCE_TEST_VIEW = 'PERMISSION_PERFORMANCE_TEST_VIEW';

    public const PROCESSED_STATUS_PENDING = 1;
    public const PROCESSED_STATUS_SUCCESS = 2;
    public const PROCESSED_STATUS_FAILURE = 3;

    public const PROCESSED_STATUSES = [
        self::PROCESSED_STATUS_PENDING => 'Pending',
        self::PROCESSED_STATUS_SUCCESS => 'Success',
        self::PROCESSED_STATUS_FAILURE => 'Failure',
    ];

    /**
     * @var array
     */
    private static $db = [
        'DetailCsvUrl' => 'Varchar(255)',
        'JsonUrl' => 'Varchar(255)',
        'OwnerKey' => 'Varchar(255)',
        'ProcessedStatus' => 'Int',
        'ResultOverviewUrl' => 'Varchar(255)',
        'RequestUrl' => 'Text',
        'StatusCode' => 'Int',
        'StatusText' => 'Text',
        'SummaryCsvUrl' => 'Varchar(255)',
        'TestId' => 'Varchar(255)',
        'TestedUrl' => 'Text',
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
    private static $table_name = 'WebPageTestSubmission';

    /**
     * @var string
     */
    private static $singular_name = 'WebPageTest Submission';

    /**
     * @var string
     */
    private static $plural_name = 'WebPageTest Submissions';

    /**
     * @var array
     */
    private static $summary_fields = [
        'TestedUrl',
        'Created',
        'StatusCode',
        'StatusText',
        'TestId',
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Remove scaffold fields so that they can be added in the correct order
        $fields->removeByName([
            'DetailCsvUrl',
            'JsonUrl',
            'OwnerKey',
            'ProcessedStatus',
            'ResultOverviewUrl',
            'RequestUrl',
            'StatusCode',
            'StatusText',
            'SummaryCsvUrl',
            'TestId',
            'TestResultID',
            'TestedUrl',
            'XmlUrl',
        ]);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                $statusCodeField = TextField::create('StatusCode'),
                $statusTextField = TextField::create('StatusText'),
                $processedStatus = DropdownField::create(
                    'ProcessedStatus',
                    'Result Processed Status',
                    self::PROCESSED_STATUSES
                ),
                $resultOverviewField = TextField::create('ResultOverviewUrl'),
                $testIdField = TextField::create('TestId'),
                $testedUrlField = TextField::create('TestedUrl'),
                $requestUrlField = TextField::create('RequestUrl'),
                TextField::create('OwnerKey'),
                HeaderField::create('RawResultFields', 'Raw Result Data', 3),
                TextField::create('SummaryCsvUrl'),
                TextField::create('DetailCsvUrl'),
                TextField::create('JsonUrl'),
                TextField::create('XmlUrl'),
            ]
        );

        $statusCodeField->setDescription('A status of 200 indicates that the request was successfully received');
        $statusTextField->setDescription('A status of "OK" indicates that the request is being processed');
        $resultOverviewField->setDescription('The URL for you to view the test results in the WPT interface');
        $processedStatus->setDescription('If you wish to reprocess these results, set this status to Pending');
        $testIdField->setDescription('The identifier that WebPageTest assigned to the test request');
        $testedUrlField->setDescription('The URL that you requested be tested');
        $requestUrlField->setDescription('The full test request that was sent to WebPageTest');

        return $fields;
    }

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
        $this->ResultOverviewUrl = $result->getResultOverviewUrl();
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

    /**
     * @return array
     */
    public function providePermissions(): array
    {
        return [
            self::PERMISSION_PERFORMANCE_TEST_CREATE => 'Performance Tests - Create',
            self::PERMISSION_PERFORMANCE_TEST_DELETE => 'Performance Tests - Delete',
            self::PERMISSION_PERFORMANCE_TEST_EDIT => 'Performance Tests - Edit',
            self::PERMISSION_PERFORMANCE_TEST_VIEW => 'Performance Tests - View',
        ];
    }

    /**
     * @param null $member
     * @param array $context
     * @return bool|int
     */
    public function canCreate($member = null, $context = array()): bool
    {
        return Permission::check(self::PERMISSION_PERFORMANCE_TEST_CREATE);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canDelete($member = null): bool
    {
        return Permission::check(self::PERMISSION_PERFORMANCE_TEST_DELETE);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canEdit($member = null): bool
    {
        return Permission::check(self::PERMISSION_PERFORMANCE_TEST_EDIT);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canView($member = null): bool
    {
        return Permission::check(self::PERMISSION_PERFORMANCE_TEST_VIEW);
    }
}
