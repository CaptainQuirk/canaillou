<?php
namespace Canaillou\Driver;

use Canaillou\Cache\FileCache;

abstract class Base
{
    public $data;

    public function __construct() {}

    public function get($item)
    {
        $this->data = FileCache::read($this->name);

        return $this->data['data'][$item];
    }

    public function query($item = null, $params = [])
    {
        if (empty($item)) {
            throw new \Exception("\$item parameter is missing or empty");
        }

        $data   = $this->get($item, $params);
        $result = $this->parse($data, $params);

        return $result;
    }
}
