<?php

namespace ChrisPenny\WebPageTest\Connectors;

use GuzzleHttp\Client;

/**
 * Class for accessing Guzzle Client via injector so it can be mocked in unit tests
 *
 * Class GuzzleClient
 *
 * @package App\Connectors
 */
class GuzzleClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client|null $client
     */
    public function __construct(?Client $client = null)
    {
        if ($client === null) {
            $client = new Client();
        }

        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
