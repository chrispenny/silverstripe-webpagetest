<?php

namespace ChrisPenny\WebPageTest\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use SilverStripe\Core\Injector\Injectable;
use Throwable;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 *
 * Having our own Client like this will allow us to easily mock up unit tests (as we can pass in a MockClient when we
 * need to).
 *
 * @package ChrisPenny\WebPageTest\Api
 */
class Client
{
    use Injectable;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public const VERSION = '1.1';

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @param GuzzleClient|null $client
     */
    public function __construct(?GuzzleClient $client = null)
    {
        if ($client === null) {
            $client = new GuzzleClient();
        }

        $this->client = $client;
    }

    /**
     * @return GuzzleClient
     */
    public function getClient(): GuzzleClient
    {
        return $this->client;
    }

    /**
     * @param Request $request
     * @return Response|mixed|ResponseInterface|null
     */
    public function send(Request $request)
    {
        try {
            $response = $this->client->send($request);
        } catch (Throwable $e) {
            if ($e instanceof RequestException
                && $e->hasResponse()
            ) {
                return $e->getResponse();
            }

            $message = strlen($e->getMessage()) > 0
                ? $e->getMessage()
                : 'Unknown error during API request';

            $body = [
                'message' => $message,
            ];

            $response = new Response(500, [], json_encode($body));
        }

        return $response;
    }
}
