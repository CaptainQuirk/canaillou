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
        $items = array();
        $i = 0;
        foreach ($data['stats'] as $browser => $stats) {
            if (isset($filters['browser']) && $browser !== $filters['browser']) {
                continue;
            }

            $items[$i] = array(
                'name'  => $browser,
                'items' => array()
            );

            foreach ($stats as $version => $value) {
                if (isset($filters['version']) && $version != $filters['version']) {
                    continue;
                }

                $items[$i]['items'][] = array(
                    'label'   => $this->formatNumber($version),
                    'value'   => $value === 'y' ? 1 : 0,
                    'current' => false
                );
            }


            $i++;
        }

        return $items;
    }

    private function formatNumber($number)
    {
        if (!preg_match('#[0-9]+\.[0-9]+-#', $number)) {
            return $number;
        }

        $parts = explode('-', $number);

        return end($parts);
    }
}
