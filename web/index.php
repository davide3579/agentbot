<?php

require('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;



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
                "text"=>"Che cosa vuoi fare?",
                "buttons"=>[
                    [
                        "type"=>"web_url",
                        "url"=>"https://petersapparel.parseapp.com",
                        "title"=>"Show Website"
                    ],
                    [
                        "type"=>"postback",
                        "title"=>"scegli per categorie",
                        "payload"=>"CATEGORIES"
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }

    if($payload == "CATEGORIES"){

        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Vini bianchi",
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
                                "type"=>"postback",
                                "title"=>"mostra prodotti",
                                "payload"=>"WHITE"
                            ],
                        ]
                    ],
                    [
                        "title"=>"vini rossi",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"https://upload.wikimedia.org/wikipedia/commons/8/88/Glass_of_Red_Wine_with_a_bottle_of_Red_Wine_-_Evan_Swigart.jpg",
                        "subtitle"=>"Potente,dalla botta sicura",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"mostra prodotti",
                                "payload"=>"RED"
                            ],
                        ]
                    ],
                    [
                        "title"=>"vini rosè",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://media.bellevy.com/images/5000-10000/1375443549-14581819.jpg",
                        "subtitle"=>"da fighetti,per ogni tipo di aperitivo.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"mostra prodotti",
                                "payload"=>"PINK"
                            ],
                        ]
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }elseif($payload == "WHITE"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Gavi Cortese",
                        "image_url"=> "http://www.grandibottiglie.com/shop/951-706-thickbox/cortese-di-gavi-1982.jpg",
                        "subtitle"=> "colore paglierino più o meno intenso; odore delicato; sapore asciutto.",
                        "default_action"=> [
                            "type"=> "web_url",
                            "url"=> "https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                            "webview_height_ratio"=> "tall",
                            // "messenger_extensions"=> true,
                            // "fallback_url"=> "https://peterssendreceiveapp.ngrok.io/"
                        ],
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Moscato di Asti",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://static.lacavestore.com/817/moscato-d-asti-docg-pelassa-2016.jpg",
                        "subtitle"=>"colore paglierino più o meno intenso, dalla brillante limpidezza.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Arneis",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"https://atmosferaitaliana.it/26276-large_default/roero-arneis-docg.jpg",
                        "subtitle"=>"odore delicato, fresco e con eventuale sentore di legno; sapore elegante.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }elseif($payload == "RED"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Dolcetto di Acqui",
                        "image_url"=> "http://marencovini.com/wp-content/uploads/2015/05/resized-marchesa.tif.jpg",
                        "subtitle"=> "colore rosso rubino intenso, con tendenza al rosso mattone con l’invecchiamento.",
                        "default_action"=> [
                            "type"=> "web_url",
                            "url"=> "https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                            "webview_height_ratio"=> "tall",
                            // "messenger_extensions"=> true,
                            // "fallback_url"=> "https://peterssendreceiveapp.ngrok.io/"
                        ],
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Barbera del Monferrato",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://www.viniolivetta.it/wp-content/uploads/2015/03/barbera-monferrato1-924x784.jpg",
                        "subtitle"=>"Ha un colore rosso rubino più o meno intenso; odore vinoso; sapore asciutto.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Barolo",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://www.acquabuona.it/wp-content/uploads/2010/09/brezza_cannubi-eti.jpg",
                        "subtitle"=>" Ha un colore rosso granato con riflessi arancioni; profumo caratteristico, etereo, gradevole, intenso",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }elseif($payload == "PINK"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"list",
                "elements"=>[
                    [
                        "title"=> "Langhe Rosato",
                        "image_url"=> "http://broccardo.it/wp-content/uploads/2015/04/scont-rosato-131x500.png",
                        "subtitle"=> "Vino rosato ottenuto principalmente dalle uve dei vitigni Barbera, Dolcetto e Nebbiolo",
                        "default_action"=> [
                            "type"=> "web_url",
                            "url"=> "https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                            "webview_height_ratio"=> "tall",
                            // "messenger_extensions"=> true,
                            // "fallback_url"=> "https://peterssendreceiveapp.ngrok.io/"
                        ],
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Albugnano Rosato",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://www.lacortedelbarbio.it/sites/default/files/styles/large/public/fasoglio_albugnanorose.JPG?itok=z-Y7r2QA",
                        "subtitle"=>"Ottenuto da uve Nebbiolo in misura pari ad almeno l’85% e da altri vitigni tipici dell’Astigiano per la parte restante.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ],
                    [
                        "title"=>"Chiaretto",
                        "item_url"=>"https://www.cloudways.com/blog/migrate-symfony-from-cpanel-to-cloud-hosting/",
                        "image_url"=>"http://www.vinievino.com/images/784402946/benazzoli-jpg.jpg?MjUweDUwMDo6YXV0bzo3NDY5NWU4OGQxZDFjZWQ5MTc1YTc1MTk1ZjMzYWY3Nw%3D%3D",
                        "subtitle"=>"Odore vinoso, delicato, gradevole.",
                        "buttons"=>[
                            [
                                "type"=>"postback",
                                "title"=>"ordina",
                                "payload"=>"ORDER"
                            ],
                        ]
                    ]
                ]
            ]
        ]];
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => $answer
        ];
    }elseif($payload == "ORDER"){
        $answer = ["attachment"=>[
            "type"=>"template",
            "payload"=>[
                "template_type"=>"button",
                "text"=>"Ordine inviato con successo!Cosa desideri fare ora?",
                "buttons"=>[
                    [
                        "type"=>"postback",
                        "title"=>"scegli un altro prodotto per categorie",
                        "payload"=>"CATEGORIES"
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