<?php
namespace Canaillou\Console\Formatter;

use ConsoleKit\Colors;

class Table implements OutputInterface
{
    public function output($lines = array())
    {
        $out = '';
        foreach ($lines as $line) {
            $out .= $this->line($line);
        }

        return $out;
    }

    private function getColor($featureSupport)
    {
        $color = null;

        switch ($featureSupport) {
            case 0:
                $color = Colors::RED;
                break;

            case 0.5:
                $color = Colors::YELLOW;
                break;

            case 1:
                $color = Colors::GREEN;
                break;
        }

        return $color;
    }

    private function line($line)
    {
        $lineTpl       = "         %s\n%s %s\n         %s\n\n";
        $versionStr    = '';
        $paddingTop    = '';
        $paddingBottom = '';
        foreach ($line['items'] as $item) {
            $color           = $this->getColor($item['value']);
            $versionStr     .= Colors::colorize(str_pad($item['label'], 6, " ", STR_PAD_BOTH), 'white', $color) . " ";
            $paddingTop     .= Colors::colorize("      " , 'white', $color) . " ";
            $paddingBottom  .= Colors::colorize("      ", 'white', $color) . " ";
        }

        $browser = str_pad($line['name'], 8, " ", STR_PAD_BOTH);

        return sprintf($lineTpl, $paddingTop, $browser, $versionStr, $paddingBottom);
    }
}

