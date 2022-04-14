<?php

namespace V8\Telegram;

class ChannelCrawler
{
    private $channel;
    const MESSAGE_REGEX = '/js-widget_message"(.*?)<\/time>/m';


    public function __construct($channel)
    {
        $this->channel = $channel;
    }

    public function getContent($after = null, $q = null)
    {
        return str_replace(["\n", "\r"], "", file_get_contents($this->getUrl($after, $q)));
    }

    public function getUrl($after = null, $q = null)
    {
        return 'https://telegram.me/s/' . $this->channel . ($after ? '?after=' . $after : '') . ($q ? '?q=' . $q : '');
    }

    /**
     * @return Message[]
     */
    public function getMessages($after = null, $q = null)
    {
        preg_match_all(static::MESSAGE_REGEX, $this->getContent($after, $q), $matches);
        $messages = [];
        foreach ($matches[1] as $item) {
            $messages[] = Message::parse($item);
        }

        return $messages;
    }
}
