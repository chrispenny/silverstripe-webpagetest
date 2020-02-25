<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\SubmitTest;
use ChrisPenny\WebPageTest\TestResult\RunResult;
use SilverStripe\ORM\DataObject;

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
     * For now at least, we're only supporting keeping the relationship for the "average" runs.
     *
     * @var array
     */
    private static $has_one = [
        'FirstView' => RunResult\Model::class,
        'RepeatView' => RunResult\Model::class,
    ];

    /**
     * @var array
     */
    private static $belongs_to = [
        'SubmittedTest' => SubmitTest\Model::class . '.TestResult',
    ];

    /**
     * @var string
     */
    private static $table_name = 'WebPageTestResult';

    /**
     * @var string
     */
    private static $singular_name = 'Test Result';

    /**
     * @var string
     */
    private static $plural_name = 'Test Results';

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
}
