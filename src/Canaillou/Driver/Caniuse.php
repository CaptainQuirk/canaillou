<?php
namespace Canaillou\Driver;

use Canaillou\Driver\Base;
use Canaillou\Driver\DriverInterface;

class Caniuse extends Base implements DriverInterface
{
    use \Canaillou\Network\Downloadable;

    public $name     = 'caniuse';
    public $dataType = 'json';

    public $baseUrl = 'https://raw.githubusercontent.com/Fyrd/caniuse/master/data.json';

    public function __construct() {}

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
