<?php
namespace Canaillou\Driver;

abstract class Base
{
    public function __construct() {}

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
