<?php
namespace Canaillou\Driver;

interface DriverInterface
{
    public function get();
    public function url($feature = '');
    public function parse();
}
