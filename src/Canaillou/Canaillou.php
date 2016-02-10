<?php
namespace Canaillou;

use Canaillou\Cache\FileCache;

class Canaillou
{
    public function __construct($settings = array())
    {
        $driverName   = '\Canaillou\Driver\\' . ucfirst($settings['source']);
        $this->Driver = new $driverName();
    }

    public function query($options)
    {
        if (!$this->check()) {
            $this->fetch();
        }

        $results = $this->Driver->query($options['feature'], [
          'browsers' => isset($options['browsers']) ? explode(',', $options['browsers']) : array(),
          'version' => isset($options['version']) ? $options['version'] : null
        ]);

        return $results;
    }

    public function check()
    {
        $resource = $this->Driver->fetch(true);

        $expires = new \DateTime($resource->getHeader('Expires')[0]);
        if (!FileCache::check($this->Driver->name, $expires)) {
            return false;
        }

        return true;
    }

    public function fetch()
    {
        $resource = $this->Driver->fetch(false);
        $expires = new \DateTime($resource->getHeader('Expires')[0]);
        FileCache::store($this->Driver->name, array(
            'id'      => str_replace('"', '', $resource->getHeader('Etag')[0]),
            'expires' => $expires,
            'type'    => $this->Driver->dataType,
            'content' => (string)$resource->getBody()
        ));

        return $resource;
    }
}
