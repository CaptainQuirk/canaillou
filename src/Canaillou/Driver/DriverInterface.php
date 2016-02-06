<?php
namespace Canaillou\Driver;

interface DriverInterface
{
    public function get();
    public function url($feature = '', $params = array());
    public function parse($data, $filters);
}
