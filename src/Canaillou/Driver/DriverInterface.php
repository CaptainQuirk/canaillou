<?php
namespace Canaillou\Driver;

interface DriverInterface
{
    public function get();
    public function url();
    public function parse();
}
