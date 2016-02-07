<?php
namespace Canaillou\Console\Command;

use ConsoleKit\Command;
use ConsoleKit\Colors;
use Canaillou\Canaillou;
use Canaillou\Cache\FileCache;

/**
 * Query a source for support
 *
 * @opt source  The source to query
 */
class FetchCommand extends Command
{
    public function execute(array $args, array $options = array())
    {
        if (empty($options['source'])) {
            throw new \Exception("Missing or empty source option");
        }
        $source = $options['source'];

        $this->Canaillou = new Canaillou([
            'source' => $source
        ]);

        if ($this->Canaillou->check()) {
            echo Colors::colorize("Data for {$source} is already up to date", Colors::GREEN);

            exit;
        }

        $this->Canaillou->fetch();
    }
}
