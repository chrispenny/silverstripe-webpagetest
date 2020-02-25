<?php

namespace ChrisPenny\WebPageTest\SubmitTest;

use ChrisPenny\WebPageTest\Connectors\ApiConnector;
use GuzzleHttp\Psr7\Request as BaseRequest;
use InvalidArgumentException;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class RunTest
 *
 * @package ChrisPenny\WebPageTest\Request
 */
class Request extends BaseRequest
{
    use Injectable;
    use Extensible;
    use Configurable;

    /**
     * Specify a string that will be used to hash the test to a specific test agent. The tester will be picked by index
     * among the available testers. If the number of testers changes then the tests will be distributed to different
     * machines but if the counts remain consistent then the same string will always run the tests on the same test
     * machine. This can be useful for controlling variability when comparing a given URL over time or different
     * parameters against each other (using the URL as the hash string)
     *
     * @var string|null
     */
    private $affinity;

    /**
     * String to append to the user agent string. This is in addition to the default PTST/ver string. If "keepua" is
     * also specified it will still append. Allows for substitution with some test parameters:
     *   %TESTID% - Replaces with the test ID for the current test
     *   %RUN% - Replaces with the current run number
     *   %CACHED% - Replaces with 1 for repeat view tests and 0 for initial view
     *   %VERSION% - Replaces with the current wptdriver version number
     *
     * @var string|null
     */
    private $appendUserAgent;

    /**
     * Type of authentication to use: 0 = Basic Auth, 1 = SNS
     * Default: 0
     *
     * @var int|null
     */
    private $authType;

    /**
     * Space-delimited list of urls to block (substring match)
     *
     * @var string|null
     */
    private $block;

    /**
     * Browser window height (in display pixels)
     *
     * @var int|null
     */
    private $browserHeight;

    /**
     * Browser window width (in display pixels)
     *
     * @var int|null
     */
    private $browserWidth;

    /**
     * Download bandwidth in Kbps (used when specifying a custom connectivity profile)
     *
     * @var int|null
     */
    private $bandwidthDown;

    /**
     * Upload bandwidth in Kbps (used when specifying a custom connectivity profile)
     *
     * @var int|null
     */
    private $bandwidthUp;

    /**
     * Set to 1 to clear the OS certificate caches (causes IE to do OCSP/CRL checks during SSL negotiation if the
     * certificates are not already cached)
     * Default: 0
     *
     * @var int|null
     */
    private $clearCerts;

    /**
     * Custom command-line options (Chrome only)
     *
     * @var string|null
     */
    private $cmdLine;

    /**
     * Override the number of concurrent connections IE uses (0 to not override)
     * Default: 0
     *
     * @var int|null
     */
    private $connections;

    /**
     * Custom metrics to collect at the end of a test
     * https://sites.google.com/a/webpagetest.org/docs/using-webpagetest/custom-metrics
     *
     * @var string|null
     */
    private $custom;

    /**
     * Device Pixel Ratio to use when emulating mobile
     *
     * @var string|int|null
     */
    private $devicePixelRatio;

    /**
     * DOM Element to record for sub-measurement
     *
     * @var string|null
     */
    private $domElement;

    /**
     * Set to 1 to skip the Repeat View test
     * Default: 0
     *
     * @var int|null
     */
    private $firstViewOnly;

    /**
     * Format:
     * Set to "xml" to request an XML response
     * Set to "json" for JSON-encoded response
     *
     * @var string|null
     */
    private $format;

    /**
     * Viewport Height in css pixels
     *
     * @var int|null
     */
    private $height;

    /**
     * Set to 1 to save the content of the first response (base page) instead of all of the text responses (bodies=1)
     *
     * @var int|null
     */
    private $htmlBody;

    /**
     * Set to 1 to Ignore SSL Certificate Errors e.g. Name mismatch, Self-signed certificates, etc
     * Default: 0
     *
     * @var int|null
     */
    private $ignoreSsl;

    /**
     * Specify a jpeg compression level (30-100) for the screen shots and video capture
     *
     * @var int|null
     */
    private $iq;

    /**
     * Set to 1 to preserve the original browser User Agent string (don't append PTST to it)
     * Default: 0
     *
     * @var int|null
     */
    private $keepUserAgent;

    /**
     * API Key (if assigned) - applies only to runtest.php calls. Contact the site owner for a key if required
     * (http://www.webpagetest.org/getkey.php for the public instance)
     *
     * @var string|null
     */
    private $key;

    /**
     * Label for the test
     *
     * @var string|null
     */
    private $label;

    /**
     * First-hop Round Trip Time in ms (used when specifying a custom connectivity profile)
     *
     * @var string|int|null
     */
    private $latency;

    /**
     * Set to 1 to have a lighthouse test also performed (Chrome-only, wptagent agents only)
     * Default: 0
     *
     * @var int|null
     */
    private $lighthouse;

    /**
     * Location to test from
     *
     * @var string|null
     */
    private $location;

    /**
     * User name to use for authenticated tests (http authentication)
     *
     * @var string|null
     */
    private $login;

    /**
     * Default metric to use when calculating the median run
     * Default: loadTime
     *
     * @var string|null
     */
    private $medianMetric;

    /**
     * Set to 1 when capturing video to only store the video from the median run
     * Default: 0
     *
     * @var int|null
     */
    private $medianVideo;

    /**
     * Set to 1 to have Chrome emulate a mobile browser (screen resolution, UA string, fixed viewport)
     * Default: 0
     *
     * @var int|null
     */
    private $mobile;

    /**
     * Device name from mobile_devices.ini to use for mobile emulation (only when mobile=1 is specified to enable
     * emulation and only for Chrome)
     *
     * @var string|null
     */
    private $mobileDevice;

    /**
     * E-mail address to notify with the test results
     *
     * @var string|null
     */
    private $notify;

    /**
     * Set to 1 to disable saving of the http headers (as well as browser status messages and CPU utilization)
     * Default: 0
     *
     * @var int|null
     */
    private $noHeaders;

    /**
     * Set to 1 to disable screen shot capturing
     * Default: 0
     *
     * @var int|null
     */
    private $noImages;

    /**
     * Set to 1 to disable optimization checks (for faster testing)
     * Default: 0
     *
     * @var int|null
     */
    private $noOpt;

    /**
     * Set to 1 to disable javascript (IE, Chrome, Firefox)
     * Default: 0
     *
     * @var int|null
     */
    private $noScript;

    /**
     * Packet loss rate - percent of packets to drop (used when specifying a custom connectivity profile)
     *
     * @var string|int|null
     */
    private $packetLossRate;

    /**
     * Password to use for authenticated tests (http authentication)
     *
     * @var string|null
     */
    private $password;

    /**
     * URL to ping when the test is complete (the test ID will be passed as an "id" parameter)
     *
     * @var string|null
     */
    private $pingBack;

    /**
     * Set to 1 to save a full-resolution version of the fully loaded screen shot as a png
     * Default: 0
     *
     * @var int|null
     */
    private $pngScreenShot;

    /**
     * Set to 0 to make the test visible in the history log (defaults to private)
     * Default: 1
     *
     * @var int|null
     */
    private $private;

    /**
     * When using the xml interface, will echo back in the response
     *
     * @var string|null
     */
    private $return;

    /**
     * Number of test runs (1-10 on the public instance)
     * Default: 1
     *
     * @var int|null
     */
    private $runs;

    /**
     * Scripted test to execute
     *
     * @var string|null
     */
    private $script;

    /**
     * Set to 1 to enable tcpdump capture
     * Default: 0
     *
     * @var int|null
     */
    private $tcpDump;

    /**
     * Test name to use when submitting results to tsviewdb (for private instances that have integrated with tsviewdb)
     *
     * @var string|null
     */
    private $tsViewId;

    /**
     * Specify a specific tester that the test should run on (must match the PC name in /getTesters.php). If the tester
     * is not available the job will never run.
     *
     * @var string|null
     */
    private $tester;

    /**
     * Set to 1 to have Chrome capture the Dev Tools timeline
     * Default: 0
     *
     * @var int|null
     */
    private $timeline;

    /**
     * Set to between 1 - 5 to have Chrome include the Javascript call stack. Must be used in conjunction with
     * "timeline"
     * Default: 0
     *
     * @var int|null
     */
    private $timelineStack;

    /**
     * For running alternative test types, can specify 'traceroute' or 'lighthouse' (lighthouse as a test type is only
     * supported on wptagent agents)
     *
     * @var string|null
     */
    private $type;

    /**
     * Custom User Agent String to use
     *
     * @var string|null
     */
    private $userAgentString;

    /**
     * The URL to be tested
     *
     * @var string|null
     */
    private $url;

    /**
     * Set to 1 to capture video (video is required for calculating Speed Index)
     * Default: 0
     *
     * @var int|null
     */
    private $video;

    /**
     * Set to 1 to force the test to stop at Document Complete (onLoad)
     * Default: 0
     *
     * @var int|null
     */
    private $web10;

    /**
     * Viewport Width in css pixels
     *
     * @var int|null
     */
    private $width;

    public function __construct()
    {
        parent::__construct(
            ApiConnector::METHOD_GET,
            null,
            $this->getHeaders(),
            $this->getBody(),
            ApiConnector::VERSION
        );
    }

    /**
     * @return Request
     */
    public function hydrateFromConfiguration(): Request
    {
        $this->setAffinity(Request::config()->get('affinity'))
            ->setAppendUserAgent(Request::config()->get('append_user_agent'))
            ->setAuthType(Request::config()->get('auth_type'))
            ->setBlock(Request::config()->get('block'))
            ->setBrowserHeight(Request::config()->get('browser_height'))
            ->setBrowserWidth(Request::config()->get('browser_width'))
            ->setBandwidthDown(Request::config()->get('bw_down'))
            ->setBandwidthUp(Request::config()->get('bw_up'))
            ->setClearCerts(Request::config()->get('clear_certs'))
            ->setCmdLine(Request::config()->get('cmd_line'))
            ->setConnections(Request::config()->get('connections'))
            ->setCustom(Request::config()->get('custom'))
            ->setDomElement(Request::config()->get('dom_element'))
            ->setDevicePixelRatio(Request::config()->get('device_pixel_ratio'))
            ->setFormat(Request::config()->get('format'))
            ->setFirstViewOnly(Request::config()->get('first_view_only'))
            ->setHeight(Request::config()->get('height'))
            ->setHtmlBody(Request::config()->get('html_body'))
            ->setIgnoreSsl(Request::config()->get('ignore_ssl'))
            ->setIq(Request::config()->get('iq'))
            ->setKey(Request::config()->get('key'))
            ->setKeepUserAgent(Request::config()->get('keep_user_agent'))
            ->setLabel(Request::config()->get('label'))
            ->setLatency(Request::config()->get('latency'))
            ->setLighthouse(Request::config()->get('lighthouse'))
            ->setLogin(Request::config()->get('login'))
            ->setLocation(Request::config()->get('location'))
            ->setMedianMetric(Request::config()->get('median_metric'))
            ->setMobile(Request::config()->get('mobile'))
            ->setMobileDevice(Request::config()->get('mobile_device'))
            ->setMedianVideo(Request::config()->get('median_video'))
            ->setNoHeaders(Request::config()->get('no_headers'))
            ->setNoImages(Request::config()->get('no_images'))
            ->setNoOpt(Request::config()->get('no_opt'))
            ->setNoScript(Request::config()->get('no_script'))
            ->setNotify(Request::config()->get('notify'))
            ->setPassword(Request::config()->get('password'))
            ->setPingBack(Request::config()->get('ping_back'))
            ->setPacketLossRate(Request::config()->get('packet_loss_rate'))
            ->setPngScreenShot(Request::config()->get('png_screen_shot'))
            ->setPrivate(Request::config()->get('private'))
            ->setReturn(Request::config()->get('return'))
            ->setRuns(Request::config()->get('runs'))
            ->setScript(Request::config()->get('script'))
            ->setTcpDump(Request::config()->get('tcp_dump'))
            ->setTester(Request::config()->get('tester'))
            ->setTimeline(Request::config()->get('timeline'))
            ->setTimelineStack(Request::config()->get('timeline_stack'))
            ->setTsViewId(Request::config()->get('ts_view_id'))
            ->setType(Request::config()->get('type'))
            ->setUserAgentString(Request::config()->get('user_agent_string'))
            ->setUrl(Request::config()->get('url'))
            ->setVideo(Request::config()->get('video'))
            ->setWeb10(Request::config()->get('web10'))
            ->setWidth(Request::config()->get('width'));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestTarget(): ?string
    {
        return static::config()->get('base_url');
    }

    /**
     * Override the base method, as our Uri will not be generated when you instantiate the class, it will be generated
     * at the time of request, after you have set all of the settings you wish
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public function getUri(): ?string
    {
        $requestTarget = $this->getRequestTarget();
        $requestParams = $this->getRequestParams();

        // Can't do anything if no base_url is available
        if (strlen($requestTarget) === 0) {
            throw new InvalidArgumentException('No base_url has been set for this Request type');
        }

        // We don't need to append any params if there aren't any
        if (count($requestParams) === 0) {
            return $requestTarget;
        }

        // Append each of our params and return the resulting string
        $uri = sprintf(
            '%s?%s',
            $requestTarget,
            implode(
                '&',
                array_map(
                    function ($key, $value) {
                        return sprintf("%s=%s", $key, $value);
                    },
                    array_keys($requestParams),
                    $requestParams
                )
            )
        );

        return $uri;
    }

    /**
     * Map the fields used by WebPageTest to our getter methods
     *
     * @return array
     */
    protected function getMap(): array
    {
        $map = [
            'affinity' => $this->getAffinity(),
            'appendua' => $this->getAppendUserAgent(),
            'authType' => $this->getAuthType(),
            'block' => $this->getBlock(),
            'browser_height' => $this->getBrowserHeight(),
            'browser_width' => $this->getBrowserWidth(),
            'bwDown' => $this->getBandwidthDown(),
            'bwUp' => $this->getBandwidthUp(),
            'clearcerts' => $this->getClearCerts(),
            'cmdline' => $this->getCmdLine(),
            'connections' => $this->getConnections(),
            'custom' => $this->getCustom(),
            'domelement' => $this->getDomElement(),
            'dpr' => $this->getDevicePixelRatio(),
            'f' => $this->getFormat(),
            'fvonly' => $this->getFirstViewOnly(),
            'height' => $this->getHeight(),
            'htmlbody' => $this->getHtmlBody(),
            'ignoreSSL' => $this->getIgnoreSsl(),
            'iq' => $this->getIq(),
            'k' => $this->getKey(),
            'keepua' => $this->getKeepUserAgent(),
            'label' => $this->getLabel(),
            'latency' => $this->getLatency(),
            'lighthouse' => $this->getLighthouse(),
            'login' => $this->getLogin(),
            'location' => $this->getLocation(),
            'medianMetric' => $this->getMedianMetric(),
            'mobile' => $this->getMobile(),
            'mobileDevice' => $this->getMobileDevice(),
            'mv' => $this->getMedianVideo(),
            'noheaders' => $this->getNoHeaders(),
            'noimages' => $this->getNoImages(),
            'noopt' => $this->getNoOpt(),
            'noscript' => $this->getNoScript(),
            'notify' => $this->getNotify(),
            'password' => $this->getPassword(),
            'pingback' => $this->getPingBack(),
            'plr' => $this->getPacketLossRate(),
            'pngss' => $this->getPngScreenShot(),
            'private' => $this->getPrivate(),
            'r' => $this->getReturn(),
            'runs' => $this->getRuns(),
            'script' => $this->getScript(),
            'tcpdump' => $this->getTcpDump(),
            'tester' => $this->getTester(),
            'timeline' => $this->getTimeline(),
            'timelineStack' => $this->getTimelineStack(),
            'tsview_id' => $this->getTsViewId(),
            'type' => $this->getType(),
            'uastring' => $this->getUserAgentString(),
            'url' => $this->getUrl(),
            'video' => $this->getVideo(),
            'web10' => $this->getWeb10(),
            'width' => $this->getWidth(),
        ];

        $this->invokeWithExtensions('updateMap', $map);

        return $map;
    }

    /**
     * Loop through our map and remove any values that have not been explicitly set
     *
     * @return array
     */
    protected function getRequestParams(): array
    {
        $map = $this->getMap();
        $validParams = [];

        foreach ($map as $key => $value) {
            // Any value other than `null` is considered valid
            if ($value === null) {
                continue;
            }

            $validParams[$key] = $value;
        }

        return $validParams;
    }

    /**
     * @return string|null
     */
    public function getAffinity(): ?string
    {
        return $this->affinity;
    }

    /**
     * @param string|null $affinity
     * @return Request
     */
    public function setAffinity(?string $affinity): Request
    {
        $this->affinity = $affinity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAppendUserAgent(): ?string
    {
        return $this->appendUserAgent;
    }

    /**
     * @param string|null $appendUserAgent
     * @return Request
     */
    public function setAppendUserAgent(?string $appendUserAgent): Request
    {
        $this->appendUserAgent = $appendUserAgent;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAuthType(): ?int
    {
        return $this->authType;
    }

    /**
     * @param int|null $authType
     * @return Request
     */
    public function setAuthType(?int $authType): Request
    {
        $this->authType = $authType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBlock(): ?string
    {
        return $this->block;
    }

    /**
     * @param string|null $block
     * @return Request
     */
    public function setBlock(?string $block): Request
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBrowserHeight(): ?int
    {
        return $this->browserHeight;
    }

    /**
     * @param int|null $browserHeight
     * @return Request
     */
    public function setBrowserHeight(?int $browserHeight): Request
    {
        $this->browserHeight = $browserHeight;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBrowserWidth(): ?int
    {
        return $this->browserWidth;
    }

    /**
     * @param int|null $browserWidth
     * @return Request
     */
    public function setBrowserWidth(?int $browserWidth): Request
    {
        $this->browserWidth = $browserWidth;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBandwidthDown(): ?int
    {
        return $this->bandwidthDown;
    }

    /**
     * @param int|null $bandwidthDown
     * @return Request
     */
    public function setBandwidthDown(?int $bandwidthDown): Request
    {
        $this->bandwidthDown = $bandwidthDown;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBandwidthUp(): ?int
    {
        return $this->bandwidthUp;
    }

    /**
     * @param int|null $bandwidthUp
     * @return Request
     */
    public function setBandwidthUp(?int $bandwidthUp): Request
    {
        $this->bandwidthUp = $bandwidthUp;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClearCerts(): ?int
    {
        return $this->clearCerts;
    }

    /**
     * @param int|null $clearCerts
     * @return Request
     */
    public function setClearCerts(?int $clearCerts): Request
    {
        $this->clearCerts = $clearCerts;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCmdLine(): ?string
    {
        return $this->cmdLine;
    }

    /**
     * @param string|null $cmdLine
     * @return Request
     */
    public function setCmdLine(?string $cmdLine): Request
    {
        $this->cmdLine = $cmdLine;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getConnections(): ?int
    {
        return $this->connections;
    }

    /**
     * @param int|null $connections
     * @return Request
     */
    public function setConnections(?int $connections): Request
    {
        $this->connections = $connections;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustom(): ?string
    {
        return $this->custom;
    }

    /**
     * @param string|null $custom
     * @return Request
     */
    public function setCustom(?string $custom): Request
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getDevicePixelRatio()
    {
        return $this->devicePixelRatio;
    }

    /**
     * @param int|string|null $devicePixelRatio
     * @return Request
     */
    public function setDevicePixelRatio($devicePixelRatio)
    {
        $this->devicePixelRatio = $devicePixelRatio;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDomElement(): ?string
    {
        return $this->domElement;
    }

    /**
     * @param string|null $domElement
     * @return Request
     */
    public function setDomElement(?string $domElement): Request
    {
        $this->domElement = $domElement;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFirstViewOnly(): ?int
    {
        return $this->firstViewOnly;
    }

    /**
     * @param int|null $firstViewOnly
     * @return Request
     */
    public function setFirstViewOnly(?int $firstViewOnly): Request
    {
        $this->firstViewOnly = $firstViewOnly;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string|null $format
     * @return Request
     */
    public function setFormat(?string $format): Request
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     * @return Request
     */
    public function setHeight(?int $height): Request
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHtmlBody(): ?int
    {
        return $this->htmlBody;
    }

    /**
     * @param int|null $htmlBody
     * @return Request
     */
    public function setHtmlBody(?int $htmlBody): Request
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIgnoreSsl(): ?int
    {
        return $this->ignoreSsl;
    }

    /**
     * @param int|null $ignoreSsl
     * @return Request
     */
    public function setIgnoreSsl(?int $ignoreSsl): Request
    {
        $this->ignoreSsl = $ignoreSsl;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIq(): ?int
    {
        return $this->iq;
    }

    /**
     * @param int|null $iq
     * @return Request
     */
    public function setIq(?int $iq): Request
    {
        $this->iq = $iq;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getKeepUserAgent(): ?int
    {
        return $this->keepUserAgent;
    }

    /**
     * @param int|null $keepUserAgent
     * @return Request
     */
    public function setKeepUserAgent(?int $keepUserAgent): Request
    {
        $this->keepUserAgent = $keepUserAgent;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     * @return Request
     */
    public function setKey(?string $key): Request
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     * @return Request
     */
    public function setLabel(?string $label): Request
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getLatency()
    {
        return $this->latency;
    }

    /**
     * @param int|string|null $latency
     * @return Request
     */
    public function setLatency($latency)
    {
        $this->latency = $latency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLighthouse(): ?int
    {
        return $this->lighthouse;
    }

    /**
     * @param int|null $lighthouse
     * @return Request
     */
    public function setLighthouse(?int $lighthouse): Request
    {
        $this->lighthouse = $lighthouse;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return Request
     */
    public function setLocation(?string $location): Request
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     * @return Request
     */
    public function setLogin(?string $login): Request
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMedianMetric(): ?string
    {
        return $this->medianMetric;
    }

    /**
     * @param string|null $medianMetric
     * @return Request
     */
    public function setMedianMetric(?string $medianMetric): Request
    {
        $this->medianMetric = $medianMetric;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMedianVideo(): ?int
    {
        return $this->medianVideo;
    }

    /**
     * @param int|null $medianVideo
     * @return Request
     */
    public function setMedianVideo(?int $medianVideo): Request
    {
        $this->medianVideo = $medianVideo;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMobile(): ?int
    {
        return $this->mobile;
    }

    /**
     * @param int|null $mobile
     * @return Request
     */
    public function setMobile(?int $mobile): Request
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMobileDevice(): ?string
    {
        return $this->mobileDevice;
    }

    /**
     * @param string|null $mobileDevice
     * @return Request
     */
    public function setMobileDevice(?string $mobileDevice): Request
    {
        $this->mobileDevice = $mobileDevice;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotify(): ?string
    {
        return $this->notify;
    }

    /**
     * @param string|null $notify
     * @return Request
     */
    public function setNotify(?string $notify): Request
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoHeaders(): ?int
    {
        return $this->noHeaders;
    }

    /**
     * @param int|null $noHeaders
     * @return Request
     */
    public function setNoHeaders(?int $noHeaders): Request
    {
        $this->noHeaders = $noHeaders;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoImages(): ?int
    {
        return $this->noImages;
    }

    /**
     * @param int|null $noImages
     * @return Request
     */
    public function setNoImages(?int $noImages): Request
    {
        $this->noImages = $noImages;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoOpt(): ?int
    {
        return $this->noOpt;
    }

    /**
     * @param int|null $noOpt
     * @return Request
     */
    public function setNoOpt(?int $noOpt): Request
    {
        $this->noOpt = $noOpt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoScript(): ?int
    {
        return $this->noScript;
    }

    /**
     * @param int|null $noScript
     * @return Request
     */
    public function setNoScript(?int $noScript): Request
    {
        $this->noScript = $noScript;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPacketLossRate()
    {
        return $this->packetLossRate;
    }

    /**
     * @param int|string|null $packetLossRate
     * @return Request
     */
    public function setPacketLossRate($packetLossRate)
    {
        $this->packetLossRate = $packetLossRate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return Request
     */
    public function setPassword(?string $password): Request
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPingBack(): ?string
    {
        return $this->pingBack;
    }

    /**
     * @param string|null $pingBack
     * @return Request
     */
    public function setPingBack(?string $pingBack): Request
    {
        $this->pingBack = $pingBack;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPngScreenShot(): ?int
    {
        return $this->pngScreenShot;
    }

    /**
     * @param int|null $pngScreenShot
     * @return Request
     */
    public function setPngScreenShot(?int $pngScreenShot): Request
    {
        $this->pngScreenShot = $pngScreenShot;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrivate(): ?int
    {
        return $this->private;
    }

    /**
     * @param int|null $private
     * @return Request
     */
    public function setPrivate(?int $private): Request
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturn(): ?string
    {
        return $this->return;
    }

    /**
     * @param string|null $return
     * @return Request
     */
    public function setReturn(?string $return): Request
    {
        $this->return = $return;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRuns(): ?int
    {
        return $this->runs;
    }

    /**
     * @param int|null $runs
     * @return Request
     */
    public function setRuns(?int $runs): Request
    {
        $this->runs = $runs;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getScript(): ?string
    {
        return $this->script;
    }

    /**
     * @param string|null $script
     * @return Request
     */
    public function setScript(?string $script): Request
    {
        $this->script = $script;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTcpDump(): ?int
    {
        return $this->tcpDump;
    }

    /**
     * @param int|null $tcpDump
     * @return Request
     */
    public function setTcpDump(?int $tcpDump): Request
    {
        $this->tcpDump = $tcpDump;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTsViewId(): ?string
    {
        return $this->tsViewId;
    }

    /**
     * @param string|null $tsViewId
     * @return Request
     */
    public function setTsViewId(?string $tsViewId): Request
    {
        $this->tsViewId = $tsViewId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTester(): ?string
    {
        return $this->tester;
    }

    /**
     * @param string|null $tester
     * @return Request
     */
    public function setTester(?string $tester): Request
    {
        $this->tester = $tester;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimeline(): ?int
    {
        return $this->timeline;
    }

    /**
     * @param int|null $timeline
     * @return Request
     */
    public function setTimeline(?int $timeline): Request
    {
        $this->timeline = $timeline;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimelineStack(): ?int
    {
        return $this->timelineStack;
    }

    /**
     * @param int|null $timelineStack
     * @return Request
     */
    public function setTimelineStack(?int $timelineStack): Request
    {
        $this->timelineStack = $timelineStack;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Request
     */
    public function setType(?string $type): Request
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserAgentString(): ?string
    {
        return $this->userAgentString;
    }

    /**
     * @param string|null $userAgentString
     * @return Request
     */
    public function setUserAgentString(?string $userAgentString): Request
    {
        $this->userAgentString = $userAgentString;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Request
     */
    public function setUrl(?string $url): Request
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVideo(): ?int
    {
        return $this->video;
    }

    /**
     * @param int|null $video
     * @return Request
     */
    public function setVideo(?int $video): Request
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeb10(): ?int
    {
        return $this->web10;
    }

    /**
     * @param int|null $web10
     * @return Request
     */
    public function setWeb10(?int $web10): Request
    {
        $this->web10 = $web10;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     * @return Request
     */
    public function setWidth(?int $width): Request
    {
        $this->width = $width;

        return $this;
    }
}
