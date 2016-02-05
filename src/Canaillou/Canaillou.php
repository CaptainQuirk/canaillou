<?php
namespace Canaillou;

class Canaillou
{
    public function __construct($settings = array())
    {
        $driverName   = '\Canaillou\Driver\\' . ucfirst($settings['source']);
        $this->Driver = new $driverName();
    }

}
