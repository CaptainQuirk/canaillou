<?php
namespace Canaillou\Installer;

use Canaillou\Canaillou;
use Guzzle\Client;

require_once dirname(dirname(dirname((__FILE__)))) . '/bootstrap.php';

final class ScriptRegistry
{
    public static function postInstall()
    {
        $supportedDrivers = [ 'caniuse' ];

        foreach ($supportedDrivers as $driver) {
            $canaillou = new Canaillou([ 'source' => $driver ]);
            $canaillou->fetch();
        };
    }
}
