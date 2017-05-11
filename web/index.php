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

$app->get('/', function() use($app) {

    return new Response("hi", 201);

});

$app->post('/bot', function () use ($app) {
    // parameters
    $hubVerifyToken = 'winestore566';
    $accessToken =   "EAAS3BvhSDrkBAFHAZBu0FfkPt7pKSk6tzaYYdVxiFNYcYWYjtTyJl2TGZBMCZC7lzeuAoIoiqNU5msZCKwroyZAIcfsKJEhxtj8HF8gs0ZBlrV6JVPWeQacannMKGB5W1zScuq9KEIZADxZBjZCAO4TluwuGQBDAxYVymFag5KPZBjZBdGhRcpFpPnS";

    // check token at setup
    if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
        echo $_REQUEST['hub_challenge!'];
        exit;
    }

    // handle bot's anwser
    $input = json_decode(file_get_contents('php://input'), true);
    $senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
    $messageText = $input['entry'][0]['messaging'][0]['message']['text'];
    $response = null;

    //set Message
    if($messageText == "hi") {
        $answer = "Hello";
    }elseif($messageText == "db"){
        $st = $app['pdo']->prepare('SELECT name FROM products');
        $st->execute();

        $names = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $app['monolog']->addDebug('Row ' . $row['name']);
            $names[] = $row;
        }

        $answer = $names[0];
    }

    //send message to facebook bot
    $response = [
        'recipient' => [ 'id' => $senderId ],
        'message' => [ 'text' => $answer ]
    ];

    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    if(!empty($input)){
        $result = curl_exec($ch);
    }
    curl_close($ch);

    $app['monolog']->addDebug('logging output.');

    return new Response('challenge', 201);

    //ENDING BOT CODE
});

$app->run();