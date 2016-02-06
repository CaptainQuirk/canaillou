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

        $this->Canaillou = new Canaillou([
            'source' => $source
        ]);

        $results = $this->Canaillou->query($options);
        foreach ($results as $browser => $data) {
            $this->line($browser, $data);
        }
    }

    private function line($browser, $versions)
    {
        $lineTpl    = "         %s\n%s %s\n         %s\n\n";
        $versionStr = '';
        $paddingTop = '';
        $paddingBottom = '';
        foreach ($versions as $number => $feature) {
            $color           = $this->getColor($feature);
            $number          = $this->formatNumber($number);
            $versionStr     .= Colors::colorize(str_pad($number, 6, " ", STR_PAD_BOTH), 'white', $color) . " ";
            $paddingTop     .= Colors::colorize("      " , 'white', $color) . " ";
            $paddingBottom  .= Colors::colorize("      ", 'white', $color) . " ";
        }

        $browser = str_pad($browser, 8, " ", STR_PAD_BOTH);

        echo sprintf($lineTpl, $paddingTop, $browser, $versionStr, $paddingBottom);
    }

    private function formatNumber($number)
    {
        if (!preg_match('#[0-9]+\.[0-9]+-#', $number)) {
            return $number;
        }

        $parts = explode('-', $number);

        return end($parts);
    }

    private function getColor($featureSupport)
    {
        $color = null;

        switch ($featureSupport) {
            case 'y':
                $color = Colors::GREEN;
                break;

            case 'n':
                $color = Colors::RED;
                break;

            default:
                $color = Colors::YELLOW;
                break;
        }

        return $color;
    }
}
