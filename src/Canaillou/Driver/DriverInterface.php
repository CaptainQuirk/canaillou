<?php
namespace Canaillou\Driver;

interface DriverInterface
{
    public function parse($data, $filters);
    public function browsers();
}
