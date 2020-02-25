<?php

namespace ChrisPenny\WebPageTest\TestResult\RunResult;

use ChrisPenny\WebPageTest\Submission;
use ChrisPenny\WebPageTest\TestResult;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class Model
 *
 * The intention here is to store more result info as it becomes useful. There is an absolute tonne of data, and it
 * doesn't make sense to store it all until there is a reason to use it.
 *
 * @package ChrisPenny\WebPageTest\TestResult\RunResult
 * @property int $DomInteractive
 * @property int $FirstContentfulPaint
 * @property int $FirstLayout
 * @property int $FirstMeaningfulPaint
 * @property int $FirstPaint
 * @property int $FullyLoaded
 * @property int $IsRepeatView
 * @property int $Responses200
 * @property int $Responses404
 * @property int $RunNumber
 * @property int $ScoreCookies
 * @property int $ScoreCdn
 * @property int $ScoreMinify
 * @property int $ScoreCombine
 * @property int $ScoreETags
 * @property int $ScoreProgressiveJpeg
 * @property int $ScoreGzip
 * @property int $ScoreCompress
 * @property int $ScoreKeepAlive
 * @property int $ScoreCache
 * @property string $StatusText
 * @property int $TestResultID
 * @property int $TimeToFirstByte
 * @method TestResult\Model TestResult()
 */
class Model extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'DomInteractive' => 'Int',
        'FirstContentfulPaint' => 'Int',
        'FirstLayout' => 'Int',
        'FirstMeaningfulPaint' => 'Int',
        'FirstPaint' => 'Int',
        'FullyLoaded' => 'Int',
        'IsRepeatView' => 'Boolean(0)',
        'Responses200' => 'Int',
        'Responses404' => 'Int',
        'RunNumber' => 'Int',
        'ScoreCookies' => 'Int',
        'ScoreCdn' => 'Int',
        'ScoreMinify' => 'Int',
        'ScoreCombine' => 'Int',
        'ScoreETags' => 'Int',
        'ScoreProgressiveJpeg' => 'Int',
        'ScoreGzip' => 'Int',
        'ScoreCompress' => 'Int',
        'ScoreKeepAlive' => 'Int',
        'ScoreCache' => 'Int',
        'StatusText' => 'Text',
        'TimeToFirstByte' => 'Int',
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
    private static $table_name = 'WebPageTestRunResult';

    /**
     * @var string
     */
    private static $singular_name = 'WebPageTest Run Result';

    /**
     * @var string
     */
    private static $plural_name = 'WebPageTest Run Results';

    /**
     * @param Result $result
     */
    public function hydrateFromResult(Result $result): void
    {
        $this->DomInteractive = $result->getDomInteractive();
        $this->FirstContentfulPaint = $result->getFirstContentfulPaint();
        $this->FirstLayout = $result->getFirstLayout();
        $this->FirstMeaningfulPaint = $result->getFirstMeaningfulPaint();
        $this->FirstPaint = $result->getFirstPaint();
        $this->FullyLoaded = $result->getFullyLoaded();
        $this->IsRepeatView = $result->getIsRepeatView();
        $this->Responses200 = $result->getResponses200();
        $this->Responses404 = $result->getResponses404();
        $this->RunNumber = $result->getRunNumber();
        $this->ScoreCache = $result->getScoreCache();
        $this->ScoreCdn = $result->getScoreCdn();
        $this->ScoreCombine = $result->getScoreCombine();
        $this->ScoreCompress = $result->getScoreCompress();
        $this->ScoreCookies = $result->getScoreCookies();
        $this->ScoreETags = $result->getScoreETags();
        $this->ScoreGzip = $result->getScoreGzip();
        $this->ScoreKeepAlive = $result->getScoreKeepAlive();
        $this->ScoreMinify = $result->getScoreMinify();
        $this->ScoreProgressiveJpeg = $result->getScoreProgressiveJpeg();
        $this->TimeToFirstByte = $result->getTimeToFirstByte();
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
