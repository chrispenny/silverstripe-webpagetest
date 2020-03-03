<?php

namespace ChrisPenny\WebPageTest\Tests\Submission;

use ChrisPenny\WebPageTest\Api\Connector;
use ChrisPenny\WebPageTest\Submission\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;

/**
 * Class ServiceTest
 *
 * @package ChrisPenny\WebPageTest\Tests\Submission
 */
class ServiceTest extends SapphireTest
{
    /**
     * @var string
     */
    protected $usesDatabase = true;

    /**
     * @var MockHandler
     */
    protected $mock;

    public function setUp(): void
    {
        parent::setUp();

        $this->mock = new MockHandler([]);

        $handler = HandlerStack::create($this->mock);
        $client = new Client(['handler' => $handler]);

        Injector::inst()->registerService(new Connector($client), Connector::class);
    }

    /**
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function testPendingTestResult(): void
    {
        $mock = file_get_contents(__DIR__ . '/../resources/mocks/test-submission.json');

        $headers = [
            'Content-Type' => 'application/json;charset=utf-8',
        ];

        $response = new Response(200, $headers, $mock);

        $this->mock->append($response);

        $service = Service::create();
        $model = $service->requestTest();

        $this->assertNotNull($model);
        $this->assertEquals(200, $model->StatusCode);
        $this->assertEquals('Ok', $model->StatusText);
    }
}
