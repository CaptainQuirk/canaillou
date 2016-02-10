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

    private function getBgColor($featureSupport, $current)
    {
        $color = null;

        if ($current) {
            return 'white';
        }

        switch ($featureSupport) {
            case 0:
                $color = 'red';
                break;

            case 0.5:
                $color = 'yellow';
                break;

            case 1:
                $color = 'green';
                break;
        }

        return $color;
    }

    private function getFgColor($featureSupport, $current)
    {
        if (!$current) {
            return 'white';
        }

        switch ($featureSupport) {
            case 0:
                $color = 'red';
                break;

            case 0.5:
                $color = 'yellow';
                break;

            case 1:
                $color = 'green';
                break;
        }

        return "{$color}+bold";
    }

    private function line($line)
    {
        $lineTpl       = "         %s\n%s %s\n         %s\n\n";
        $versionStr    = '';
        $paddingTop    = '';
        $paddingBottom = '';
        foreach ($line['items'] as $item) {
            $bgColor = $this->getBgColor($item['value'], $item['current']);
            $fgColor = $this->getFgColor($item['value'], $item['current']);
            $versionStr     .= Colors::colorize(str_pad($item['label'], 6, " ", STR_PAD_BOTH), $fgColor, $bgColor) . " ";
            $paddingTop     .= Colors::colorize("      " , $fgColor, $bgColor) . " ";
            $paddingBottom  .= Colors::colorize("      ", $fgColor, $bgColor) . " ";
        }

        $browser = str_pad($line['name'], 8, " ", STR_PAD_BOTH);

        return sprintf($lineTpl, $paddingTop, $browser, $versionStr, $paddingBottom);
    }
}

