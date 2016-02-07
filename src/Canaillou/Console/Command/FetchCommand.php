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

        $resource = $this->Canaillou->fetch(true);

        $expires = new \DateTime($resource->getHeader('Expires')[0]);
        if (FileCache::check($source, $expires)) {
            echo Colors::colorize("Data for {$source} is already up to date", Colors::GREEN);

            exit;
        }

        $resource = $this->Canaillou->fetch();
        FileCache::store($source, array(
            'id'      => str_replace('"', '', $resource->getHeader('Etag')[0]),
            'expires' => $expires,
            'content' => (string)$resource->getBody()
        ));
    }
}
