<?php
namespace Canaillou\Console;

use Canaillou\Canaillou;
use ConsoleKit\Console;
use Canaillou\Console\Command\QueryCommand;

class Application
{
    public function __construct() {}

    public function run()
    {
        $console = new Console([
            'Canaillou\Console\Command\QueryCommand'
        ]);

        $console->run();
    }
}
