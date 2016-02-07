<?php
namespace Canaillou\Cache;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class FileCache
{
    private static $instance;

    private static $fs;

    private static $path;

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$path = getenv('HOME') . DS . '.cache' . DS . 'canaillou';
            $adapter      = new Local(static::$path);
            static::$fs   = new Filesystem($adapter);

            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function store($key, $data)
    {
        $filename = "{$key}_{$data['id']}";

        if ($data['type'] === 'json') {
            $data['content'] = json_decode($data['content'], true);
        }

        static::$fs->write($filename, serialize($data));
    }

    public static function check($key, $expires)
    {
        $instance = static::getInstance();

        $items = scandir(static::$path);
        $valid = false;
        foreach ($items as $item) {
            $fullPath = join(DS, array(static::$path, $item));
            if (is_dir($fullPath)) {
                continue;
            }

            if (preg_match("#^{$key}_[A-Za-z0-9]+$#", $item)) {
                $content = unserialize(static::$fs->read($item));
                $now     = new \DateTime("Sun, 07 Feb 2016 16:01:35 GMT");
                if ($now->getTimestamp() > $content['expires']->getTimestamp()) {
                    static::$fs->delete($item);

                    break;
                }

                $valid = true;
            }
        }

        return $valid;
    }

    public static function read($key)
    {
        $instance = static::getInstance();

        $items = scandir(static::$path);
        $valid = false;
        foreach ($items as $item) {
            $fullPath = join(DS, array(static::$path, $item));
            if (is_dir($fullPath)) {
                continue;
            }

            if (preg_match("#^{$key}_[A-Za-z0-9]+$#", $item)) {
                $content = unserialize(static::$fs->read($item));

                break;
            }
        }

        return $content['content'];
    }
}
