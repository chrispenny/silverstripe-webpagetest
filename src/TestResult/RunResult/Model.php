<?php

namespace ChrisPenny\WebPageTest\TestResult\RunResult;

use ChrisPenny\WebPageTest\TestResult;
use SilverStripe\ORM\DataObject;

/**
 * Class Model
 *
 * @package ChrisPenny\WebPageTest\TestResult\RunResult
 */
class Model extends DataObject
{
    /**
     * @var array
     */
    private static $belongs_to = [
        'FirstViewed' => TestResult\Model::class . '.FirstView',
        'RepeatViewed' => TestResult\Model::class . '.RepeatView',
    ];
}
