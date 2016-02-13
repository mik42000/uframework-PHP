<?php

require __DIR__ . '/../vendor/autoload.php';

use Model\InMemoryFinder;
use Model\JsonFinder;
use Model\Connection;
use Model\DatabaseFinder;
use Model\StatusMapper;
use Exception\HttpException;
use Http\Request;
use Http\Response;
use Model\Status;


// Config
$debug = true;

$app = new \App(new View\TemplateEngine(__DIR__.'/templates/'), $debug);

$connection = new Connection("mysql:dbname=uframework;host=127.0.0.1;port=32768", "uframework", "p4ssw0rd");
$statusMapper = new StatusMapper($connection);

/**
 * Index
 */
$app->get('/', function (Request $request) use ($app) {
    return $app->render('index.php');
});

//Formulaire de création
$app->get('/tweet/new', function (Request $request) use ($app) {
	$request->guessBestFormat();
    
    return $app->render('post_tweet.php');
});

//Formulaire de suppression
/*$app->get('/tweet/del/(\d+)', function (Request $request, $id) use ($app) {
    return $app->render('status.php', ['id' => $id]);
});*/



//Get liste des tweets (array php - InMemoryFinder)
/*$app->get('/tweet', function (Request $request) use ($app) {
	$request->guessBestFormat();
	
	$datas = new InMemoryFinder();
	$listeTweets = $datas->findAll(); 
    return $app->render('liste_tweets.php', ['liste' => $listeTweets]);
});

//Get un tweet (grâce à son id) (array php - InMemoryFinder)
$app->get('/tweet/(\d+)', function (Request $request, $id) use ($app) {
	$request->guessBestFormat();
	
	$datas = new InMemoryFinder();
	$tweetTmp = $datas->findOneById($id);
	if($tweetTmp != null) { 
    	return $app->render('tweet.php', ['tweet' => $tweetTmp]);
	}
	throw new HttpException(404, "Not found : Ce tweet n'existe pas ! Sorry :(");
});*/

//---------------------------------------------------------JSON-----------------------------------------------------------//

//Get liste des tweets (JSON - JsonFinder)
/*$app->get('/jsontweet', function (Request $request) use ($app) {
	$request->guessBestFormat();
	
	$jsonf = new JsonFinder();
	$listeTweets = $jsonf->findAll();
        return $app->render('liste_tweets.php', ['liste' => $listeTweets]);
});

//Get un tweet (grâce à son id) (JSON - JsonFinder)
$app->get('/jsontweet/(\d+)', function (Request $request, $id) use ($app) {
	$request->guessBestFormat();
	
	$jsonf = new JsonFinder();
	$tweetTmp = $jsonf->findOneById($id);
	if($tweetTmp != null) { 
    		return $app->render('tweet.php', ['tweet' => $tweetTmp]);
	}
	throw new HttpException(404, "Not found : Ce tweet n'existe pas ! Sorry :(");
});*/



//---------------------------------------------------------Database-----------------------------------------------------------//

//Get liste des tweets
$app->get('/tweets', function (Request $request) use ($connection, $app) {	
	$criteria = [];
	if($request->getParameter('limit') !== null){
		$criteria['limit'] = $request->getParameter('limit');
	}
	if($request->getParameter('orderBy') !== null){ // query acceptée : ?limit=5&orderBy=date_tweet
		if($request->getParameter('sfx') !== null){ // sfx = ASC ou DESC
			$criteria['orderBy'] = $request->getParameter('orderBy').' '.$request->getParameter('sfx');
		} else {
			$criteria['orderBy'] = $request->getParameter('orderBy');
		}
	}

	$dbf = new DatabaseFinder();
	$listeTweets = $dbf->findAll($connection, $criteria);
	
	$format = $request->guessBestFormat();
	$code = 200;
	if(empty($listeTweets)) {
		$code = 204;
	}
	if($format==="json") {
		$response = new Response(json_encode($listeTweets), $code, ["Content-Type" => "application/json"]);
		$response->send();
	}
	else {
		return $app->render('liste_tweets.php', ['liste' => $listeTweets]);
	}

        
});

//Get un tweet (grâce à son id)
$app->get('/tweets/(\d+)', function (Request $request, $id) use ($connection, $app) {
	$request->guessBestFormat();
	
	$dbf = new DatabaseFinder();
	$tweetTmp = $dbf->findOneById($connection, $id);
	
	if($tweetTmp === null) { 
		throw new HttpException(404, "Not found : Ce tweet n'existe pas ! Sorry :(");
	}
	if($format==="json") {
		$response = new Response(json_encode($listeTweets), 200, array("Content-Type" =>"application/json"));
		$response->send();
	}
	return $app->render('tweet.php', ['tweet' => $tweetTmp]);
	
});





//Créer un nouveau tweet
$app->post('/tweet', function (Request $request) use ($app, $connection) { 
	$request->guessBestFormat();
	
	//Pour générer un id > au dernier (+1)
	$jsonf = new JsonFinder();
	$listeTweets = $jsonf->findAll($connection, []); 
	$lastId = end($listeTweets)['id'];
	
	$username = $request->getParameter('nom');
	$message = $request->getParameter('message');
	$dateNow = date('d-m-Y');
	$idTweet = $lastId + 1;
	$os = $request->getParameter('os');

	if($os == null) {
		$dateTweet = new DateTime();
		$dateTweet = $dateTweet->format("Y-m-d H:i:s");
		$status = new Status($username, $message, $dateTweet);
	} else {
		$status = new Status($username, $message, $dateTweet, $os);
	}

	//$tweet = array("id"=>$idTweet,"message"=>$message,"date"=>$dateNow,"nom"=>$username);
	//$listeTweets[] = $tweet;

	$statusMapper = new StatusMapper($connection);
	$statusMapper->persist($status);
	$listeTweets[] = $status;
	
	$app->redirect('/tweets'); // si bdd, pas besoin de passer après

	$fileName = $jsonf->getFileName();
	if(!file_put_contents($fileName, json_encode($listeTweets))) {
		echo "Le tweet n'a pas pu être enregistré ! Sorry :(";
	}
	else {
		$app->redirect('/jsontweet');
	}
});

//Supprimer un tweet
$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $connection) {
	$request->guessBestFormat();
	
	//$jsonf = new JsonFinder();
	//$tweet = $jsonf->findOneById($id);
	$dbf = new DatabaseFinder();
	$status = $dbf->findOneById($connection, $id);

	if($status === null) {
		throw new HttpException(404, "Object doesn't exist");
	} else {
		// pour json
		/*$tab = $jsonf->findAll();
		foreach($tab as $key=>$value) {
			if($value['id'] == $id) {
				unset($tab[$key]);
			}
		}
		$fileName = $jsonf->getFileName();
		if(!file_put_contents($fileName, json_encode($tab))) {
			echo "Le tweet n'a pas pu être supprimé ! Sorry :(";
		} else {
			$app->redirect('/jsontweet');
		}*/

		// pour bdd
		$statusMapper = new StatusMapper($connection);
		$statusMapper->remove($status);
		$app->redirect('/tweets');
	}
});

return $app;
