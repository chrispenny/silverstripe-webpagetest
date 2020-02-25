<?php

namespace ChrisPenny\WebPageTest\Submission;

use ChrisPenny\WebPageTest\Api\Client;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class Service
 *
 * @package ChrisPenny\WebPageTest\RunTest
 */
class Service
{
    use Injectable;

    /**
     * When using this method, you can optionally pass in your own Request (which you may have changed a bunch of
     * settings on), or, if you leave it null, then it will generate a Request from your current configuration settings
     *
     * For your configuration settings, you will want to at least have `url` defined. Otherwise you'll be sending a
     * request to test `null`
     *
     * @param Request|null $request
     * @return Model
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function requestTest(?Request $request = null): Model
    {
        if ($request === null) {
            $request = Request::create()->hydrateFromConfiguration();
        }

        $connector = Client::create();
        $response = $connector->send($request);

        $result = Result::create();
        $result->hydrateFromResponse($response);

        $model = Model::create();
        $model->hydrateFromResult($result);
        $model->RequestUrl = $request->getUri();
        $model->TestedUrl = $request->getUrl();
        $model->write();

        return $model;
    }
}
