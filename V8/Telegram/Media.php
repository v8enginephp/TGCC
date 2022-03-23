<?php

namespace V8\Telegram;

class Media
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function download($destination)
    {
        $ch = curl_init($this->url);
        $fp = fopen($destination, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}