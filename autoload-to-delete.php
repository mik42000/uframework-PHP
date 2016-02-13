<?php

spl_autoload_register(function($className)
{
	$cacheFile = __DIR__ . '/cache.php';
	include $cacheFile; 

	if(!isset($mapCache[$className]))
	{
		//Remplacement ainti-slash par slash et underscore par slash
		$slash = str_replace('\\','/',$className);
		$underscore = str_replace('_','/',$slash);

		//Ajout des classes dans la map
		if(file_exists(__DIR__ . "/src/Model/" . $underscore . ".php")){
			$mapCache[$className] = "/src/Model/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/src/Exception/" . $underscore . ".php")){
			$mapCache[$className] = "/src/Exception/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/src/Routing/" . $underscore . ".php")){
			$mapCache[$className] = "/src/Routing/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/src/View/" . $underscore . ".php")){
			$mapCache[$className] = "/src/View/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/src/" . $underscore . ".php")){
			$mapCache[$className] = "/src/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/tests/" . $underscore . ".php")){
			$mapCache[$className] = "/tests/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/tests/View/" . $underscore . ".php")){
			$mapCache[$className] = "/tests/View/" . $underscore . ".php";
		}
		elseif(file_exists(__DIR__ . "/" . $underscore . ".php")){
			$mapCache[$className] = "/" . $underscore . ".php";
		}
		//Remplace le tableau de base de cache.php par ce tableau rempli
		file_put_contents($cacheFile, sprintf('<?php $mapCache = %s;', 
			var_export($mapCache,true))
		);
	}
	//Si le nom de la classe existe dans la map et est différent de null on le charge
	if(isset($mapCache[$className]))
		require_once __DIR__ . $mapCache[$className];
});
