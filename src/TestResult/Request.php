<?php

namespace ChrisPenny\WebPageTest\TestResult;

use ChrisPenny\WebPageTest\Api\Connector;
use GuzzleHttp\Psr7\Request as BaseRequest;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class Request
 *
 * @package ChrisPenny\WebPageTest\TestResult
 */
class Request extends BaseRequest
{
    use Injectable;

    public function __construct(string $url)
    {
        parent::__construct(
            Connector::METHOD_GET,
            $url,
            $this->getHeaders(),
            $this->getBody(),
            Connector::VERSION
        );
    }
}
