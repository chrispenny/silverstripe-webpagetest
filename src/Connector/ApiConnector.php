<?php

namespace ChrisPenny\WebPageTest\Connectors;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use SilverStripe\Core\Injector\Injectable;
use Throwable;

/**
 * Note:
 * Before using this, make sure you have your Alacrity .env variables set up.
 *
 * Class ApiConnector
 *
 * @package App\Connectors
 */
class ApiConnector extends GuzzleClient
{
    use Injectable;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public const VERSION = '1.1';

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
