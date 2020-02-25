<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Submission;
use ChrisPenny\WebPageTest\TestResult\RunResult;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class Model
 *
 * @package ChrisPenny\WebPageTest\TestResult
 * @property int $BehindCount
 * @property int $BandwidthDown
 * @property int $BandwidthUp
 * @property int $Completed
 * @property string $Connectivity
 * @property int $FirstViewId
 * @property int $FirstViewOnly
 * @property string $Latency
 * @property string $Location
 * @property int $Mobile
 * @property string $PacketLossRate
 * @property int $RepeatView
 * @property int $StatusCode
 * @property string $StatusText
 * @property int $TestsExpected
 * @property string $TestId
 * @property int $TestRuns
 * @property string $Url
 * @method RunResult\Model FirstView()
 * @method RunResult\Model RepeatView()
 */
class Model extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'BehindCount' => 'Int',
        'BandwidthDown' => 'Int',
        'BandwidthUp' => 'Int',
        'Completed' => 'Int',
        'Connectivity' => 'Varchar(255)',
        'FirstViewOnly' => 'Int',
        'Latency' => 'Int',
        'Location' => 'Varchar(255)',
        'Mobile' => 'Boolean(0)',
        'PacketLossRate' => 'Varchar(255)',
        'StatusCode' => 'Int',
        'StatusText' => 'Text',
        'TestsExpected' => 'Int',
        'TestId' => 'Varchar(255)',
        'TestRuns' => 'Int',
        'Url' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'RunResults' => RunResult\Model::class,
    ];

    /**
     * @var array
     */
    private static $belongs_to = [
        'Submission' => Submission\Model::class . '.TestResult',
    ];

    /**
     * @var string
     */
    private static $table_name = 'WebPageTestResult';

    /**
     * @var string
     */
    private static $singular_name = 'WebPageTest Result';

    /**
     * @var string
     */
    private static $plural_name = 'WebPageTest Results';

    /**
     * @param Result $result
     */
    public function hydrateFromResult(Result $result): void
    {
        // Set status results
        $this->StatusCode = $result->getStatusCode();
        $this->StatusText = $result->getStatusText();

        if ($this->StatusCode >= 100 && $this->StatusCode < 200) {
            $this->hydratePendingResult($result);

            return;
        }

        $this->hydrateCompletedResult($result);
    }

    /**
     * @param Result $result
     */
    protected function hydratePendingResult(Result $result): void
    {
        $this->BehindCount = $result->getBehindCount();
        $this->TestsExpected = $result->getTestsExpected();
    }

    /**
     * @param Result $result
     */
    protected function hydrateCompletedResult(Result $result): void
    {
        $this->BandwidthDown = $result->getBandwidthDown();
        $this->BandwidthUp = $result->getBandwidthUp();
        $this->Completed = $result->getCompleted();
        $this->Connectivity = $result->getConnectivity();
        $this->FirstViewOnly = $result->getFirstViewOnly();
        $this->Latency = $result->getLatency();
        $this->Location = $result->getLocation();
        $this->Mobile = $result->getMobile();
        $this->PacketLossRate = $result->getPacketLossRate();
        $this->TestRuns = $result->getTestRuns();
        $this->Url = $result->getUrl();
    }

    /**
     * @param string $testId
     * @return Model|null
     */
    public static function findByTestId(string $testId): ?Model
    {
        /** @var Model|null $existing */
        $existing = static::get()
            ->filter('TestId', $testId)
            ->first();

        return $existing;
    }

    /**
     * @param string $testId
     * @return Model
     */
    public static function findOrCreate(string $testId): Model
    {
        $existing = static::findByTestId($testId);

        if ($existing !== null) {
            return $existing;
        }

        $new = Model::create();
        $new->TestId = $testId;

        return $new;
    }

    /**
     * @param null $member
     * @param array $context
     * @return bool|int
     */
    public function canCreate($member = null, $context = array()): bool
    {
        return false;
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canDelete($member = null): bool
    {
        return false;
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canEdit($member = null): bool
    {
        return false;
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canView($member = null): bool
    {
        return Permission::check(Submission\Model::PERMISSION_PERFORMANCE_TEST_VIEW);
    }
}
