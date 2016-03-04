<?php

require 'vendor/autoload.php';
use PhpSlackBot\Bot;

$config = [
    'slack'   => [
        'token'  => 'xoxb-xxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxx'
    ],
    'simsimi' => [
        // 'endpoint' => 'http://api.simsimi.com/request.p',    // paid key
        'endpoint' => 'http://sandbox.api.simsimi.com/request.p',   // trial key
        'token'  => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        'locale' => 'en'
    ]
];

function curl($url) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function talkToSimsimi($text) {
    global $config;
    $json = curl($config['simsimi']['endpoint']
            ."?key=".$config['simsimi']['token']
            ."&lc=".$config['simsimi']['locale']
            ."&ft=1.0&text=".urlencode($text));
    $arr = json_decode($json, true);
    if(empty($arr['response'])) {
        // This trial api will have less db. Use paid key for full db. I don't try so I don't know it worth or not?
        $arr['response'] = "[Simsimi not response.]";
    }

    return $arr['response'];
}


class SimsimiCommand extends \PhpSlackBot\Command\BaseCommand {

    protected function configure() {
        // We don't have to configure a command name in this case
    }

    protected function execute($data, $context)
    {
        $botID = $context['self']['id'];

        // if user is bot, skip it.
        if (!empty($data['user']) && $data['user'] == $botID) {
            return;
        }

        if (empty($data["channel"])) {
            return;
        }

        $text = null;

        if (!empty($data['type']) && $data['type'] == 'message' && !empty($data['text'])) {
            $text = $data['text'];
        }

        if (!empty($data['comment'])) {
            $text = $data['comment'];
        }

        if (!empty($text)) {

            // check bot got mention?
            if (strpos($text, '<@'.$botID.'>') === 0) {
                $text = preg_replace('#<\@'.$botID.'>:? ?#', '', $text); // remove bot name
                $text = preg_replace('#<\@[A-Z0-9]+>#', '', $text); // remove slack user id mention out. Simsimi don't understand it.
                $this->send($data["channel"], $data['user'], talkToSimsimi($text));
                return;
            }
        }
    }

}

$bot = new Bot();
$bot->setToken($config['slack']['token']); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCatchAllCommand(new SimsimiCommand());
$bot->run();