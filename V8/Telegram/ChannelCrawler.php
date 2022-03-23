<?php

namespace V8\Telegram;

class ChannelCrawler
{
    private $channel;
    const MESSAGE_REGEX = '/js-widget_message"(.*?)js-widget_message/m';


    public function __construct($channel)
    {
        $this->channel = $channel;
    }

    public function getContent($after = null)
    {
        return str_replace(["\n", "\r"], "", file_get_contents($this->getUrl($after)));
    }

    public function getUrl($after = null)
    {
        return 'https://telegram.me/s/' . $this->channel . ($after ? '?after=' . $after : '');
    }

    /**
     * @return Message[]
     */
    public function getMessages($after = null)
    {
     preg_match_all(static::MESSAGE_REGEX, $this->getContent($after),$matches);
        $messages = [];
        foreach ($matches[1] as $item) {
            $messages[] = Message::parse($item);
        }

        return $messages;
    }
}