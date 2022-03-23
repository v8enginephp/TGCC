<?php

namespace V8\Telegram;

use function Couchbase\defaultDecoder;

class Message
{

    /**
     * @var Media[] $photos
     */
    private $photos = [];

    private $id;
    private $text;

    const PHOTO_SEPARATOR = 'tgme_widget_message_photo_wrap grouped_media_wrap blured js-message_photo';
    const TEXT_REGEX = '/tgme_widget_message_text.*?>(.*)<div class="tgme_widget_message_footer compact/m';
    const ID_REGEX = '/data-post=".*?\/(.*?)"/m';


    private function __construct($id, $text, $photos)
    {
        $this->id = $id;
        $this->photos = $photos;
        $this->text = $text;
    }

    public static function parse($doc)
    {
        return new static(self::id($doc), self::text($doc), self::photos($doc));
    }

    public function gePhotos()
    {
        return $this->photos;
    }

    public function getText()
    {
        return $this->text;
    }


    private static function text($doc)
    {
        preg_match_all(static::TEXT_REGEX, $doc, $matches, PREG_SET_ORDER, 0);
        return @$matches[0][1];
    }

    private static function photos($doc)
    {
        $photosDoc = explode(static::PHOTO_SEPARATOR, $doc);
        $photos = [];
        foreach ($photosDoc as $item) {
            preg_match_all('/background-image:url\(\'(.*?)\'\)/m', $item, $matches, PREG_SET_ORDER);
            if (@$matches[0][1]) {
                $photos[] = new Media($matches[0][1]);
            }
        }
        return $photos;
    }

    private static function id($doc)
    {
        preg_match_all(static::ID_REGEX, $doc, $matches, PREG_SET_ORDER, 0);
        return @$matches[0][1];
    }

}