<?php
namespace Canaillou\Driver;

use Canaillou\Driver\Base;
use Canaillou\Driver\DriverInterface;

class Caniuse extends Base implements DriverInterface
{
    use \Canaillou\Network\Downloadable;

    public $baseUrl = 'https://raw.githubusercontent.com/Fyrd/caniuse/master/data.json';

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
        $i     = 0;
        foreach ($data['stats'] as $browser => $stats) {
            if (isset($filters['browser']) && $browser !== $filters['browser']) {
                continue;
            }

            $items[$i] = array(
                'name'  => $browser,
                'items' => array()
            );

            $st = $stats;
            uksort($st, function($a, $b) {
                $a = (float)$a;
                $b = (float)$b;

                if ($b > $a) {
                    return 1;
                } else if ($b < $a) {
                    return -1;
                } else {
                    return 0;
                }
            });

            $j = 0;
            foreach ($st as $version => $value) {
                if (isset($filters['version']) && $version != $filters['version']) {
                    continue;
                }

                if ($j > 6) {
                    break;
                }

                $items[$i]['items'][] = array(
                    'label'   => $this->formatNumber($version),
                    'value'   => $value === 'y' ? 1 : 0,
                    'current' => false
                );

                $j++;
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

    public function fetch($check)
    {
        if (!$check) {
          $data = $this->download($this->baseUrl);
        } else {
          $data = $this->check($this->baseUrl);
        }

        return $data;
    }
}
