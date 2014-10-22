<?php
	/*
	 * vrati realnu url suboru ktory budeme includovat, tato funkcia sa vola pri kazdom requeste
	 */
	function getURL() {
		$url = __DIR__."/views/home.php";			// default

		if(isset($_GET["url"])) {					// ak bola zadana nejaka cesta
			if(isset($_SERVER["HTTP_REFERER"])
			&& strpos($_SERVER["HTTP_REFERER"], "http://".$_SERVER["HTTP_HOST"]) == 0		// ak prisiel request z rovnakeho servera
			&& file_exists(__DIR__."/controllers/".$_GET["url"].".php")) {			// a existuje taky controller
				$url = __DIR__."/controllers/".$_GET["url"].".php";
			} elseif(file_exists(__DIR__."/views/".$_GET["url"].".php")) {			// ak existuje take view
				$url = __DIR__."/views/".$_GET["url"].".php";
			}
		}
		return $url;
	}

	// var_dump($_SERVER);

	require_once getUrl();
?>