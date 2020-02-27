<?php

namespace ChrisPenny\WebPageTest\Tests\TestResult;

use ChrisPenny\WebPageTest\Api\Connector;
use ChrisPenny\WebPageTest\Submission\Model;
use ChrisPenny\WebPageTest\TestResult\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;

/**
 * Class ServiceTest
 *
 * @package ChrisPenny\WebPageTest\Tests\TestResult
 */
class ServiceTest extends SapphireTest
{
    protected static $fixture_file = 'ServiceTest.yml';

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
     * @throws \Exception
     */
    public function testPendingTestResult(): void
    {
        /** @var Model $submission */
        $submission = $this->objFromFixture(Model::class, 'one');

        // Test we're set up correctly before we kick off
        $this->assertNotNull($submission);
        $this->assertEquals(100, $submission->StatusCode);

        $testId = $submission->TestId;

        $mock = file_get_contents(__DIR__ . '/../resources/mocks/pending-test-result.json');

        $headers = [
            'Content-Type' => 'application/json;charset=utf-8',
        ];

        $response = new Response(100, $headers, $mock);

        $this->mock->append($response);

        $service = Service::create();
        $model = $service->updateTestResult($testId);

        $this->assertNotNull($model);
        $this->assertEquals(100, $model->StatusCode);
        $this->assertEquals('Test Started 13 seconds ago', $model->StatusText);
    }

    /**
     * @throws \Exception
     */
    public function testCompletedTestResult(): void
    {
        /** @var Model $submission */
        $submission = $this->objFromFixture(Model::class, 'one');

        // Test we're set up correctly before we kick off
        $this->assertNotNull($submission);
        $this->assertEquals(100, $submission->StatusCode);

        $testId = $submission->TestId;

        $mock = file_get_contents(__DIR__ . '/../resources/mocks/completed-test-result.json');

        $headers = [
            'Content-Type' => 'application/json;charset=utf-8',
        ];

        $response = new Response(200, $headers, $mock);

        $this->mock->append($response);

        $service = Service::create();
        $model = $service->updateTestResult($testId);

        $this->assertNotNull($model);
        $this->assertEquals(200, $model->StatusCode);
        $this->assertEquals('Test Complete', $model->StatusText);
        $this->assertCount(2, $model->RunResults());
    }
}
