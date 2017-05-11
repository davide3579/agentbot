<?php

require('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->match("/", function (Request $request) use ($app) {

    // parameters
    $hubVerifyToken = 'wineshop093748';
    $accessToken =   "EAAS3BvhSDrkBACpFikfYZCZCrHMFVdpKZAXqZCmwUPF0MHAHoYE9PHrEYTEJfLGqqF74n8mWbwVLEVCCdkdOhs0CvAFX5z9LRuiMW0ZCpqWfrJqH11ZAouP23Vw9m7J1vt5WbEwFODfRZBZA3OWmX9sZAaK5tcXjoTqYmpeNHgI5JTERWLFU1k2Rv";
// check token at setup
    if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
        echo $_REQUEST['hub_challenge'];
        exit;
    }
// handle bot's anwser
    $input = json_decode(file_get_contents('php://input'), true);
    $senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
    $messageText = $input['entry'][0]['messaging'][0]['message']['text'];

    $response = null;
//set Message
    if($messageText == "today"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Classic T-Shirt Collection",
                        "image_url"=> "https://www.cloudways.com/blog/wp-content/uploads/Migrating-Your-Symfony-Website-To-Cloudways-Banner.jpg",
                        "subtitle"=> "See all our colors",
                        "default_action"=> [
                            "type"=> "web_url",
                            "url"=> "https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                            "webview_height_ratio"=> "tall",
                            // "messenger_extensions"=> true,
                            // "fallback_url"=> "https://peterssendreceiveapp.ngrok.io/"
                        ],
                        "buttons"=>[
                            [
                                "type"=>"web_url",
                                "url"=>"https://petersfancybrownhats.com",
                                "title"=>"View Website"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Welcome to Peters Hats",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"https://www.cloudways.com/blog/wp-content/uploads/Migrating-Your-Symfony-Website-To-Cloudways-Banner.jpg",
                        "subtitle"=>"Weve got the right hat for everyone.",
                        "buttons"=>[
                            [
                                "type"=>"web_url",
                                "url"=>"https://petersfancybrownhats.com",
                                "title"=>"View Website"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Welcome to Peters Hats",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"https://www.cloudways.com/blog/wp-content/uploads/Migrating-Your-Symfony-Website-To-Cloudways-Banner.jpg",
                        "subtitle"=>"Weve got the right hat for everyone.",
                        "buttons"=>[
                            [
                                "type"=>"web_url",
                                "url"=>"https://petersfancybrownhats.com",
                                "title"=>"View Website"
                            ],
                        ]
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];}elseif($messageText == "ciao"){
            $answer = ["attachment"=>[
                "type"=>"template",
                "payload"=>[
                    "template_type"=>"button",
                    "text"=>"What do you want to do next?",
                    "buttons"=>[
                        [
                            "type"=>"web_url",
                            "url"=>"https://petersapparel.parseapp.com",
                            "title"=>"Show Website"
                        ],
                        [
                            "type"=>"postback",
                            "title"=>"categorie",
                            "payload"=>"USER_DEFINED_PAYLOAD"
                        ]
                    ]
                ]
            ]];
            $response = [
                'recipient' => [ 'id' => $senderId ],
                'message' => $answer
            ];
    }
    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    if(!empty($input)){
        $result = curl_exec($ch);
    }
    curl_close($ch);

    $app['monolog']->addDebug('logging output.!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
//  return $app['twig']->render('index.twig');
    return new Response('Thank you for your feedback!', 201);

});

$app->get('/bot', function() use($app) {
    return $app->render('bot.php');
});

$app->run();