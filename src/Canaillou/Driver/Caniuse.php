<?php
namespace Canaillou\Driver;

use Canaillou\Driver\Base;
use Canaillou\Driver\DriverInterface;
use GuzzleHttp\Client;

class Caniuse extends Base implements DriverInterface
{
    public $baseUrl = 'https://raw.githubusercontent.com/Fyrd/caniuse/master/features-json';

    public function __construct() {}

    public function get($feature = '', $params = array())
    {
        if (empty($feature)) {
            throw new \Exception("Missing or empty feature parameter");
        }

        $url = $this->url($feature);

        $client = new Client();
        $res    = $client->request('GET', $url);
        $data   = json_decode((string)$res->getBody(), true);

        return $data;
    }

    public function url($feature = '', $params = array())
    {
       $url = "{$this->baseUrl}/{$feature}.json";

       return $url;
    }

    public function parse($data, $filters)
    {
        $browser = isset($filters['browser']) ? $filters['browser'] : null;

        if (is_null($browser)) {
            return $data['stats'];
        }

        $result = [ "{$browser}" => $data['stats']["{$browser}"] ];
        $version = isset($filters['version']) ? $filters['version'] : null;

        if (is_null($version)) {
            return $result;
        }

        $result = [ "{$browser}" => [
          $version => $result["{$browser}"][$version]
        ]];

        return $result;
    }
}
