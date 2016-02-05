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

        $data   = $this->get();
        $result = $this->parse($data);

        return $result;
    }
}
