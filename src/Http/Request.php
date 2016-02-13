<?php

namespace Http;

class Request
{
	const GET    = 'GET';
	const POST   = 'POST';
	const PUT    = 'PUT';
	const DELETE = 'DELETE';
	
	private $mapFormatMimeType = array(
		"text/html" => "html",
		"application/json" => "json",
//		"application/x-www-form-urlencoded" => "html",
	);
	
	private $parameters;
	
	//$query = tableau de paramètres passés en GET
	//$request = tableau de paramètres passés en POST
	public function __construct(array $query = array(), array $request = array()) {
		$this->parameters = array_merge($query, $request); //Fusion des deux tableaux pour en donner qu'un
	}
	
	public static function createFromGlobals() {
		if(isset($_SERVER['HTTP_CONTENT_TYPE'])) {
			$contentType = $_SERVER['HTTP_CONTENT_TYPE'];
		} elseif(isset($_SERVER['CONTENT_TYPE'])) {
			$contentType = $_SERVER['CONTENT_TYPE'];
		} else {
			$contentType = null;
		}
		
		if($contentType != null && $contentType == "json") {
			$data = file_get_contents('php://input');
			$request = @json_decode($data, true);
			
			var_dump($_GET);
			var_dump($request); // $request est null dans le cas d'une suppression via HTML (voir $_POST) !!!!!!!!!!!!!!!!
			return new Request($_GET, $request);
		}
		
		return new Request($_GET, $_POST);
	}
	
	public function getMethod() {
		$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : Request::GET;
		if ($method === self::POST) {
			return $this->getParameter('_method', $method); //Retoure valeur du hidden (formulaire HTML) est PUT ou autre (sauf GET)
		}
		return $method;
	}
	
	public function getUri() {
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		//Récupérer que la route (sans paramètres s'ils existent)
		if ($pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		return $uri;
	}
	
	public function getParameter($name, $default = null)
	{
		// !!!!!!!!!!!!!!!!!!! à remettre si php7 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		return $this->parameters[$name] ?? $default;
	}

	public function guessBestFormat(){
		$negotiator = new \Negotiation\Negotiator();
		
		$acceptHeader = $_SERVER['HTTP_ACCEPT'];
		$priorities   = array('text/html', 'application/json');	

		$mediaType = $negotiator->getBest($acceptHeader, $priorities);
		
		$mimeType = $mediaType->getValue();
		if(isset($this->mapFormatMimeType[$mimeType])) {
			return $this->mapFormatMimeType[$mimeType]; // Si le mimeType est trouvé, on retourne le bon format
		}
		return "html"; // retour par défaut
	}
}
