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

$app->post('/', function() use($app) {

//STARTING BOT CODE

    // parameters
    $hubVerifyToken = 'agriturismo3579';
    $accessToken =   "EAAS3BvhSDrkBAMZBDKpFg6NWBPwdqXD1z34FEbmNlEzbZCBBQ6eejZA68va21CtCBkizfab2fZAtbRONHH7bYimzm6FGoa7nk34vEc6XYU0WEtWdV0NZAht8tKHRKpReckozVudIiHA17WYZAC3jWyTU8GY8zvvAGU5xvFPc3cqKOhZAhTkDt25";

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

        $answer = json_encode($names);
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

    return "";

    //ENDING BOT CODE
});

$app->run();
