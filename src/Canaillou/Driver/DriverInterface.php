<?php
namespace Canaillou\Driver;

interface DriverInterface
{
    public function get($item);
    public function parse($data, $filters);
}
