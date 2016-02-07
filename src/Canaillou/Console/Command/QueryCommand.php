<?php
namespace Canaillou\Console\Command;

use ConsoleKit\Command;
use ConsoleKit\Colors;
use Canaillou\Canaillou;

/**
 * Query a source for support
 *
 * @opt source  The source to query
 * @opt feature The feature to query
 * @opt browser The browser name
 * @opt version The browser version
 * @opt format  The output format - defaults to table
 */
class QueryCommand extends Command
{
    public function execute(array $args, array $options = array())
    {
        if (empty($options['source'])) {
            throw new \Exception("Missing or empty source option");
        }
        $source = $options['source'];
        unset($options['source']);

        if (empty($options['feature'])) {
            throw new \Exception("Missing or empty feature option");
        }

        $formatterName = isset($options['format'])
            ? ucfirst($options['format'])
            : 'Table'
        ;

        $this->Canaillou = new Canaillou([
            'source' => $source
        ]);

        $results = $this->Canaillou->query($options);

        $className     = "Canaillou\\Console\\Formatter\\{$formatterName}";
        $formatter     = new $className();
        echo $formatter->output($results);
    }
}
