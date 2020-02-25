<?php

namespace ChrisPenny\WebPageTest\TestResult\RunResult;

use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use stdClass;

/**
 * Class Result
 *
 * @package ChrisPenny\WebPageTest\TestResult\RunResult
 */
class Result
{
    use Extensible;
    use Injectable;

    /**
     * @var int|null
     */
    private $domInteractive;

    /**
     * @var int|null
     */
    private $firstContentfulPaint;

    /**
     * @var int|null
     */
    private $firstLayout;

    /**
     * @var int|null
     */
    private $firstMeaningfulPaint;

    /**
     * @var int|null
     */
    private $firstPaint;

    /**
     * @var int|null
     */
    private $fullyLoaded;

    /**
     * @var int|null
     */
    private $isRepeatView;

    /**
     * @var int|null
     */
    private $responses200;

    /**
     * @var int|null
     */
    private $responses404;

    /**
     * @var int|null
     */
    private $runNumber;

    /**
     * @var int|null
     */
    private $scoreCache;

    /**
     * @var int|null
     */
    private $scoreCdn;

    /**
     * @var int|null
     */
    private $scoreCombine;

    /**
     * @var int|null
     */
    private $scoreCompress;

    /**
     * @var int|null
     */
    private $scoreCookies;

    /**
     * @var int|null
     */
    private $scoreETags;

    /**
     * @var int|null
     */
    private $scoreGzip;

    /**
     * @var int|null
     */
    private $scoreKeepAlive;

    /**
     * @var int|null
     */
    private $scoreMinify;

    /**
     * @var int|null
     */
    private $scoreProgressiveJpeg;

    /**
     * @var string|null
     */
    private $statusText;

    /**
     * @var int|null
     */
    private $timeToFirstByte;

    /**
     * @param stdClass $contents
     */
    public function hydrateFromContents(stdClass $contents): void
    {
        $errors = [];

        if (!property_exists($contents, 'domInteractive')) {
            $errors[] = 'No "domInteractive" property provided';
        }

        if (!property_exists($contents, 'firstContentfulPaint')) {
            $errors[] = 'No "firstContentfulPaint" property provided';
        }

        if (!property_exists($contents, 'firstLayout')) {
            $errors[] = 'No "firstLayout" property provided';
        }

        if (!property_exists($contents, 'firstMeaningfulPaint')) {
            $errors[] = 'No "firstMeaningfulPaint" property provided';
        }

        if (!property_exists($contents, 'firstPaint')) {
            $errors[] = 'No "firstPaint" property provided';
        }

        if (!property_exists($contents, 'fullyLoaded')) {
            $errors[] = 'No "fullyLoaded" property provided';
        }

        if (!property_exists($contents, 'responses_200')) {
            $errors[] = 'No "responses_200" property provided';
        }

        if (!property_exists($contents, 'responses_404')) {
            $errors[] = 'No "responses_404" property provided';
        }

        if (!property_exists($contents, 'run')) {
            $errors[] = 'No "run" property provided';
        }

        if (!property_exists($contents, 'score_cache')) {
            $errors[] = 'No "score_cache" property provided';
        }

        if (!property_exists($contents, 'score_cdn')) {
            $errors[] = 'No "score_cdn" property provided';
        }

        if (!property_exists($contents, 'score_combine')) {
            $errors[] = 'No "score_combine" property provided';
        }

        if (!property_exists($contents, 'score_compress')) {
            $errors[] = 'No "score_compress" property provided';
        }

        if (!property_exists($contents, 'score_cookies')) {
            $errors[] = 'No "score_cookies" property provided';
        }

        if (!property_exists($contents, 'score_etags')) {
            $errors[] = 'No "score_etags" property provided';
        }

        if (!property_exists($contents, 'score_gzip')) {
            $errors[] = 'No "score_gzip" property provided';
        }

        if (!property_exists($contents, 'score_keep-alive')) {
            $errors[] = 'No "score_keep-alive" property provided';
        }

        if (!property_exists($contents, 'score_minify')) {
            $errors[] = 'No "score_minify" property provided';
        }

        if (!property_exists($contents, 'score_progressive_jpeg')) {
            $errors[] = 'No "score_progressive_jpeg" property provided';
        }

        if (!property_exists($contents, 'TTFB')) {
            $errors[] = 'No "TTFB" property provided';
        }

        // If we're missing any of the above fields, then we can't proceed any further
        if (count($errors) > 0) {
            $this->setStatusText(implode("\n", $errors));

            return;
        }

        $this->setDomInteractive($contents->domInteractive);
        $this->setFirstContentfulPaint($contents->firstContentfulPaint);
        $this->setFirstLayout($contents->firstLayout);
        $this->setFirstMeaningfulPaint($contents->firstMeaningfulPaint);
        $this->setFirstPaint($contents->firstPaint);
        $this->setFullyLoaded($contents->fullyLoaded);
        $this->setResponses200($contents->responses_200);
        $this->setResponses404($contents->responses_404);
        $this->setRunNumber($contents->run);
        $this->setScoreCache($contents->score_cache);
        $this->setScoreCdn($contents->score_cdn);
        $this->setScoreCombine($contents->score_combine);
        $this->setScoreCompress($contents->score_compress);
        $this->setScoreCookies($contents->score_cookies);
        $this->setScoreETags($contents->score_etags);
        $this->setScoreGzip($contents->score_gzip);
        $this->setScoreKeepAlive($contents->{'score_keep-alive'});
        $this->setScoreMinify($contents->score_minify);
        $this->setScoreProgressiveJpeg($contents->score_progressive_jpeg);
        $this->setTimeToFirstByte($contents->TTFB);
    }

    /**
     * @return int|null
     */
    public function getDomInteractive(): ?int
    {
        return $this->domInteractive;
    }

    /**
     * @param int|string|null $domInteractive
     * @return Result
     */
    public function setDomInteractive($domInteractive): Result
    {
        $this->domInteractive = (int) $domInteractive;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstContentfulPaint(): ?int
    {
        return $this->firstContentfulPaint;
    }

    /**
     * @param int|string|null $firstContentfulPaint
     * @return Result
     */
    public function setFirstContentfulPaint($firstContentfulPaint): Result
    {
        $this->firstContentfulPaint = (int) $firstContentfulPaint;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstLayout(): ?int
    {
        return $this->firstLayout;
    }

    /**
     * @param int|string|null $firstLayout
     * @return Result
     */
    public function setFirstLayout($firstLayout): Result
    {
        $this->firstLayout = (int) $firstLayout;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstMeaningfulPaint(): ?int
    {
        return $this->firstMeaningfulPaint;
    }

    /**
     * @param int|string|null $firstMeaningfulPaint
     * @return Result
     */
    public function setFirstMeaningfulPaint($firstMeaningfulPaint): Result
    {
        $this->firstMeaningfulPaint = (int) $firstMeaningfulPaint;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstPaint(): ?int
    {
        return $this->firstPaint;
    }

    /**
     * @param int|string|null $firstPaint
     * @return Result
     */
    public function setFirstPaint($firstPaint): Result
    {
        $this->firstPaint = (int) $firstPaint;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFullyLoaded(): ?int
    {
        return $this->fullyLoaded;
    }

    /**
     * @param int|string|null $fullyLoaded
     * @return Result
     */
    public function setFullyLoaded($fullyLoaded): Result
    {
        $this->fullyLoaded = (int) $fullyLoaded;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIsRepeatView(): ?int
    {
        return $this->isRepeatView;
    }

    /**
     * @param int|string|null $isRepeatView
     * @return Result
     */
    public function setIsRepeatView($isRepeatView): Result
    {
        $this->isRepeatView = (int) $isRepeatView;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponses200(): ?int
    {
        return $this->responses200;
    }

    /**
     * @param int|string|null $responses200
     * @return Result
     */
    public function setResponses200($responses200): Result
    {
        $this->responses200 = (int) $responses200;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponses404(): ?int
    {
        return $this->responses404;
    }

    /**
     * @param int|string|null $responses404
     * @return Result
     */
    public function setResponses404($responses404): Result
    {
        $this->responses404 = (int) $responses404;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRunNumber(): ?int
    {
        return $this->runNumber;
    }

    /**
     * @param int|string|null $runNumber
     * @return Result
     */
    public function setRunNumber($runNumber): Result
    {
        $this->runNumber = (int) $runNumber;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreCache(): ?int
    {
        return $this->scoreCache;
    }

    /**
     * @param int|string|null $scoreCache
     * @return Result
     */
    public function setScoreCache($scoreCache): Result
    {
        $this->scoreCache = (int) $scoreCache;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreCdn(): ?int
    {
        return $this->scoreCdn;
    }

    /**
     * @param int|string|null $scoreCdn
     * @return Result
     */
    public function setScoreCdn($scoreCdn): Result
    {
        $this->scoreCdn = (int) $scoreCdn;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreCombine(): ?int
    {
        return $this->scoreCombine;
    }

    /**
     * @param int|string|null $scoreCombine
     * @return Result
     */
    public function setScoreCombine($scoreCombine): Result
    {
        $this->scoreCombine = (int) $scoreCombine;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreCompress(): ?int
    {
        return $this->scoreCompress;
    }

    /**
     * @param int|string|null $scoreCompress
     * @return Result
     */
    public function setScoreCompress($scoreCompress): Result
    {
        $this->scoreCompress = (int) $scoreCompress;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreCookies(): ?int
    {
        return $this->scoreCookies;
    }

    /**
     * @param int|string|null $scoreCookies
     * @return Result
     */
    public function setScoreCookies($scoreCookies): Result
    {
        $this->scoreCookies = (int) $scoreCookies;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreETags(): ?int
    {
        return $this->scoreETags;
    }

    /**
     * @param int|string|null $scoreETags
     * @return Result
     */
    public function setScoreETags($scoreETags): Result
    {
        $this->scoreETags = (int) $scoreETags;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreGzip(): ?int
    {
        return $this->scoreGzip;
    }

    /**
     * @param int|string|null $scoreGzip
     * @return Result
     */
    public function setScoreGzip($scoreGzip): Result
    {
        $this->scoreGzip = (int) $scoreGzip;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreKeepAlive(): ?int
    {
        return $this->scoreKeepAlive;
    }

    /**
     * @param int|string|null $scoreKeepAlive
     * @return Result
     */
    public function setScoreKeepAlive($scoreKeepAlive): Result
    {
        $this->scoreKeepAlive = (int) $scoreKeepAlive;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreMinify(): ?int
    {
        return $this->scoreMinify;
    }

    /**
     * @param int|string|null $scoreMinify
     * @return Result
     */
    public function setScoreMinify($scoreMinify): Result
    {
        $this->scoreMinify = (int) $scoreMinify;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScoreProgressiveJpeg(): ?int
    {
        return $this->scoreProgressiveJpeg;
    }

    /**
     * @param int|string|null $scoreProgressiveJpeg
     * @return Result
     */
    public function setScoreProgressiveJpeg($scoreProgressiveJpeg): Result
    {
        $this->scoreProgressiveJpeg = (int) $scoreProgressiveJpeg;
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
    public function getTimeToFirstByte(): ?int
    {
        return $this->timeToFirstByte;
    }

    /**
     * @param int|string|null $timeToFirstByte
     * @return Result
     */
    public function setTimeToFirstByte($timeToFirstByte): Result
    {
        $this->timeToFirstByte = (int) $timeToFirstByte;
        return $this;
    }
}
