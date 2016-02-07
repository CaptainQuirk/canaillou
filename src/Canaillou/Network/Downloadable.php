<?php
namespace Canaillou\Network;

use GuzzleHttp\Client;

trait Downloadable
{
    public function download($url)
    {
        $client = new Client();
        $res    = $client->request('GET', $url);

        return $res;
    }
}
