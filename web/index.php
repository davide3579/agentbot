<?php

require('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;



$app = new Silex\Application();
$app['debug'] = true;

$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider('pdo'),
    array(
        'pdo.server' => array(
            'driver'   => 'pgsql',
            'user' => $dbopts["user"],
            'password' => $dbopts["pass"],
            'host' => $dbopts["host"],
            'port' => $dbopts["port"],
            'dbname' => ltrim($dbopts["path"],'/')
        )
    )
);

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
    $accessToken =   "EAAbq0pvXHasBABud3spgZBYyyen4yJ4ujgivSekB1ZACCCyaclqyr96uWNhVe8gaqEy50H1I3SO6dedt1UzNIt2zYZAY1fMy0bDzLJ0sjjZAvZASBDVcZC8UF4SZCRmnt67kE7DyL4MUijxhvzcZA9s5pRcHFDqQ9gRj2LHBkFuJlFgDBJZBBKG6Y";
// check token at setup
    if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
        echo $_REQUEST['hub_challenge'];
        exit;
    }
// handle bot's anwser
    $input = json_decode(file_get_contents('php://input'), true);
    $senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
    $messageText = $input['entry'][0]['messaging'][0]['message']['text'];
    $payload =  $input['entry'][0]['messaging'][0]['postback']['payload'];

    $response = null;
//set Message
    if($messageText == "ciao"){
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
                        "payload"=>"CATEGORIES"
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }else if($messageText == "db"){
        $st = $app['pdo']->prepare('SELECT nome FROM prodotti');
        $st->execute();

        $names = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $app['monolog']->addDebug('Row ' . $row['nome']);
            $names[] = $row;
        }

        $answer = json_encode($names);
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $names[0]['nome']
        ];
    }

    if($payload == "USER_DEFINED_PAYLOAD"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Vino bianco",
                        "image_url"=> "https://www.vinook.it/vino-bianco/vini-bianchi/vino-bianco-fermo_O1.jpg",
                        "subtitle"=> "leggero,ma ubriacante",
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
                                "title"=>"dettagli"
                            ],
                        ]
                    ],
                    [
                        "title"=>"vino rosso",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"https://upload.wikimedia.org/wikipedia/commons/8/88/Glass_of_Red_Wine_with_a_bottle_of_Red_Wine_-_Evan_Swigart.jpg",
                        "subtitle"=>"Potente,dalla botta sicura",
                        "buttons"=>[
                            [
                                "type"=>"web_url",
                                "url"=>"https://petersfancybrownhats.com",
                                "title"=>"View Website"
                            ],
                        ]
                    ],
                    [
                        "title"=>"vino rosè",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://media.bellevy.com/images/5000-10000/1375443549-14581819.jpg",
                        "subtitle"=>"da fighetti,per ogni tipo di aperitivo.",
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
        ];}else if($payload == "CATEGORIES"){

        $st = $app['pdo']->prepare('SELECT nome_cat FROM categorie');
        $st->execute();

        $names = array();
        $buttons = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $app['monolog']->addDebug('Row-----categorie ' . $row['nome_cat']);
            $names[] = $row['nome_cat'];
            $button = array("type"=>"postback", "title"=> $row['nome_cat'], "payload"=> $row['nome_cat']);
            $app['monolog']->addDebug('Row-----button ' . json_encode($button));
            $buttons[] = $button;
        }

        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"button",
                "text"=>"select one category",
                "buttons"=>["' . $buttons . '"]
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