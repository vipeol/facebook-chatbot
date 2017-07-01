<?php

namespace App\Http\Controllers;

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

        $text = new Text($senderId);
        $callSendApi = new CallSendApi(config('botfb.pageAccessToken'));

        $callSendApi->make($text->message('Oiii, eu sou um bot'));
        $callSendApi->make($text->message('Voce digitou: '.$message));

        $message = new Image($senderId);
        $callSendApi->make($message->message('http://fathomless-castle-56481.herokuapp.com/img/homer.gif'));

        $message = new Audio($senderId);
        $callSendApi->make($message->message('http://fathomless-castle-56481.herokuapp.com/audio/woohoo.wav'));

        $message = new File($senderId);
        $callSendApi->make($message->message('http://fathomless-castle-56481.herokuapp.com/file/file.zip'));

        $message = new Video($senderId);
        $callSendApi->make($message->message('http://fathomless-castle-56481.herokuapp.com/video/video.mp4'));

        $message = new ButtonsTemplate($senderId);
        $message->add(new Button('web_url','Code.Education','http://code.education'));
        $message->add(new Button('web_url','Google','https://www.google.com.br'));
        $callSendApi->make($message->message('Que tal testarmos a abertura de um site?'));

        $button = new Button('web_url', null, 'https://angular.io/');
        $product = new Product('Produto 1','https://www.w3schools.com/angular/pic_angular.jpg','Curso de Angular', $button);
        $button = new Button('web_url', null, 'http://php.net/');
        $product2 = new Product('Produto 2','https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/711px-PHP-logo.svg.png','Curso de PHP', $button);

        $template = new GenericTemplate($senderId);
        $template->add($product);
        $template->add($product2);
        $callSendApi->make($template->message('qwe'));

        $button = new Button('web_url', null, 'https://angular.io/');
        $product = new Product('Produto 1','https://www.w3schools.com/angular/pic_angular.jpg','Curso de Angular', $button);
        $button = new Button('web_url', null, 'http://php.net/');
        $product2 = new Product('Produto 2','https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/711px-PHP-logo.svg.png','Curso de PHP', $button);

        $template = new ListTemplate($senderId);
        $template->add($product);
        $template->add($product2);
        $callSendApi->make($template->message('qwe'));

        return '';
    }
}
