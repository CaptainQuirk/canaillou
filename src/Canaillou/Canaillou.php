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
        $results = $this->Driver->query($options['feature'], [
          'browser' => isset($options['browser']) ? $options['browser'] : null,
          'version' => isset($options['version']) ? $options['version'] : null
        ]);

        return $results;
    }

    public function fetch($check = false)
    {
        $resource = $this->Driver->fetch($check);

        return $resource;
    }
}
