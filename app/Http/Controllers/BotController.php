<?php

namespace App\Http\Controllers;

use CodeBot\Build\Solid;
use CodeBot\CallSendApi;
use CodeBot\Element\Button;
use CodeBot\Element\Product;
use CodeBot\Message\Audio;
use CodeBot\Message\File;
use CodeBot\Message\Text;
use CodeBot\Message\Image;
use CodeBot\Message\Video;
use CodeBot\SenderRequest;
use CodeBot\TemplatesMessage\ButtonsTemplate;
use CodeBot\TemplatesMessage\GenericTemplate;
use CodeBot\TemplatesMessage\ListTemplate;
use CodeBot\WebHook;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function subscribe()
    {
        $webhook = new WebHook;
        $subscribe = $webhook->check(config('botfb.validationToken'));
        if (!$subscribe) {
            abort(403, 'Unauthorized action.');
        }
        return $subscribe;
    }

    public function receiveMessage(Request $request)
    {
        $sender = new SenderRequest;
        $senderId = $sender->getSenderId();
        $message = $sender->getMessage();
        $postback = $sender->getPostback();

        $bot = Solid::factory();
        Solid::pageAccessToken(config('botfb.pageAccessToken'));
        Solid::setSender($senderId);

        if ($postback) {
            if (is_array($postback)) {
                $postback = json_encode($postback);
            }
            $bot->message('text', 'Você chamou o postback '.$postback);
            return '';
        }

        $bot->message('text', 'Oiii, eu sou um bot');
        $bot->message('text', 'Voce digitou: ' . $message);

        $bot->message('image', 'http://fathomless-castle-56481.herokuapp.com/img/homer.gif');
        $bot->message('audio', 'http://fathomless-castle-56481.herokuapp.com/audio/woohoo.wav');
        $bot->message('file', 'http://fathomless-castle-56481.herokuapp.com/file/file.zip');
        $bot->message('video', 'http://fathomless-castle-56481.herokuapp.com/video/video.mp4');

        $buttons = [
            new Button('web_url','Code.Education','http://code.education'),
            new Button('web_url','Google','https://www.google.com.br')
        ];
        $bot->template('buttons', 'Que tal testarmos a abertura de um site?', $buttons);

        $products = [
            new Product(
                'Produto 1',
                'https://cms-assets.tutsplus.com/uploads/users/34/posts/23535/preview_image/angular-js-firebase.png',
                'Curso de Angular',
                 new Button('web_url', null, 'https://angular.io/')
            ),
            new Product(
                'Produto 2',
                'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/711px-PHP-logo.svg.png',
                'Curso de PHP',
                 new Button('web_url', null, 'http://php.net/')
            )
        ];

        $bot->template('generic', '', $products);
        $bot->template('list', '', $products);

        return '';
    }
}
