<?php
namespace Canaillou\Driver;

use Canaillou\Driver\Base;
use Canaillou\Driver\DriverInterface;
use GuzzleHttp\Client;

class Caniuse extends Base implements DriverInterface
{
    public $baseUrl = 'https://raw.githubusercontent.com/Fyrd/caniuse/master/features-json';

    public function __construct() {}

    public function get($feature = '')
    {
        if (empty($feature)) {
            throw new \Exception("Missing or empty feature parameter");
        }

        $url = $this->url($feature);

        $client = new Client();
        $res    = $client->request('GET', $url);
    }

    public function url($feature = '')
    {
       $url = "{$this->baseUrl}/{$feature}.json";

       return $url;
    }

}
