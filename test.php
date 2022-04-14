<?php

use V8\Telegram\ChannelCrawler;

require __DIR__."/vendor/autoload.php";

$crawler = new ChannelCrawler('test9733');

$messages = $crawler->getMessages(2);
print_r($messages);
die();
echo $messages[0]->getText();
die;
$content = file_get_contents('https://t.me/s/almahdilaptop');

$message = explode('<div class="tgme_widget_message_grouped_wrap js-message_grouped_wrap" data-margin-w="2" data-margin-h="2" style="width:453px;">', $content)[4];

$photosDom = explode('tgme_widget_message_photo_wrap grouped_media_wrap blured js-message_photo', $message);
$photos = array();

foreach ($photosDom as $item) {
    $photo = preg_match_all('/background-image:url\(\'(.*?)\'\)/m', $item, $matches, PREG_SET_ORDER);

    if (@$matches[0][1]) {
        $photos[] = $matches[0][1];
    }
}

foreach ($photos as $photo) {
    echo '<img src="' . $photo . '" />';
}

echo $message;
