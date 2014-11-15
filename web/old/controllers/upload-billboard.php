<?php
	/*
	 * funkcia skonvertuje diakritiku
	 */
	function cleanName(&$name) {
		$table = array(" "=>"-",
		"\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l",
		"\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r",
		"\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z");
		$name = mb_strtolower($name, "utf-8");
		$name = strtr($name, $table);

		return preg_replace("/[^A-Za-z0-9\-\_\.]/", "", $name);
	}

	/*
	 * funkcia vrati meno uploadovaneho suboru v tvare billboard_5.jpg
	 */
	function getFileName(&$folder, &$name) {
		$name = cleanName($name);
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$base = basename($name, ".$ext");

		for($i=1; file_exists("$folder/$name"); $i++) {
			$name = $base.($i<2 ? "" : "_$i").".$ext";
		}

		return $name;
	}

	//if(!empty($_FILES["photo"]) && isset($_POST["text"])) {		// called by ajax
	if(!empty($_FILES["photo"])) {		// called by ajax
		$folder = __DIR__."/../img/billboards";
		$name = getFileName($folder, $_FILES["photo"]["name"]);

		if(!file_exists($folder)) {
			mkdir($folder, 0777);						// vytvor rekurzivne dany folder ak neexistuje
		}
		if(!is_writable($folder)) {
			echo "Priecinok nieje zapisovatelny<br>";
		}

		if(move_uploaded_file($_FILES["photo"]["tmp_name"], "$folder/$name")) {		// move z tmp foldra
			echo "Billboard bol nahraný na server, jeho cesta je $folder/$name<br>";
		} else {
			echo "Nepodarilo sa uploadovať billboard na server<br>";
		}

		/*if(chgrp("$folder/$name", "root")) {
			echo "Skupina vlastníkov pre uploadovaný billboard boli úspešne aktualizované<br>";
		} else {
			echo "Nepodarilo sa aktualizovať skupinu vlastníkov pre uploadovaný billboard<br>";
		}*/

		if(chmod("$folder/$name", 0664)) {
			echo "Práva pre uploadovaný billboard boli úspešne aktualizované<br>";
		} else {
			echo "Nepodarilo sa aktualizovať práva pre uploadovaný billboard<br>";
		}

		$text = &$_POST["text"];
		
		echo "<a href='.'>späť</a>";
	} else {
		echo "boli prijaté neplatné dáta";
	}
?>
