<?php
namespace Canaillou;

class Canaillou
{
    public function __construct($settings = array())
    {
        $driverName   = '\Canaillou\Driver\\' . ucfirst($settings['source']);
        $this->Driver = new $driverName();
    }

    public function query($options)
    {
        $results = $this->Driver->query($options['feature'], [ $options['browser'], $options['version'] ]);

        return $results;
    }
}
