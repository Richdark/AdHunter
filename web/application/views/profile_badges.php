<?php echo $profile_menu; ?>

<div class="profile_content" charset="utf-8">
    <?php
    
    echo '<br/><h1 align="center">Štatistika</h1>';
    foreach ($statistic as $statistic) {
        echo "Počet úlovkov: " . $statistic->bilboards . "<br />";
    }

    $b = $statistic->bilboards;

    switch ($b) {
        case ($b >= 0 && $b < 10):
            echo "Level: Nováčik<br />";
            $to_next_level = 10 - $b;
            break;
        case ($b >= 10 && $b < 20):
            echo "Level: Začiatočník<br />";
            $to_next_level = 20 - $b;
            break;
        case ($b >= 20 && $b < 35):
            echo "Level: Špión<br />";
            $to_next_level = 35 - $b;
            break;
        case ($b >= 35 && $b < 60):
            echo "Level: Lovec<br />";
            $to_next_level = 60 - $b;
            break;
        case ($b >= 60 && $b < 100):
            echo "Level: Reformátor<br />";
            $to_next_level = 100 - $b;
            break;
        case ($b >= 100 && $b < 200):
            echo "Level: Batman<br />";
            $to_next_level = 200 - $b;
            break;
        case ($b >= 200 && $b < 400):
            echo "Level: Záchranca svojho okolia<br />";
            $to_next_level = 400 - $b;
            break;
        case ($b >= 400):
            echo "Level: PROFESIONÁLNY ADHUNTER<br />";
            break;
    }
    
    echo "Do ďalšieho levelu chýba " . $to_next_level . " úlovkov<br />";
    
    echo "Typy úlovkov: ";
    foreach ($types as $statistic) {
        echo $statistic->title . ': ' . $statistic->bilboards . ', ';
    }

    echo '<br/>Zdroj úlovkov: ';

    foreach ($sources as $statistic) {
        if ($statistic->source == 'w')
            echo 'web: ' . $statistic->bilboards;
        if ($statistic->source == 'm')
            echo ', mobilná aplikácia: ' . $statistic->bilboards . ' ';
    }
    echo '<br/><h1 align="center">Odcenenia</h1>';
    echo '</hr>';
    
    foreach ($types as $statistic) {
        $t = $statistic->bilboards;
        echo '<h3 title="Odcenenia podľa počtu úlovkov typu '.$statistic->title .'">' . $statistic->title . ': </h3>';
        switch ($t) {
            case ($t >= 5 && $t < 1): {//5
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            case ($t >= 5 && $t < 2): {//10
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            case ($t >= 2 && $t < 50): {//25
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            case ($t >= 50): {
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/platinum.png" title="Platinová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            default:{
                    echo '<img src="' . assets_url() . 'img/badges/no.png" title="Zatiaľ žiadna medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break; 
            }
        }
        echo '</br>';
    }

    echo '</br>';
    echo '</hr>';
    foreach ($sources as $statistic) {
        $t = $statistic->bilboards;
        $s = $statistic->source;
        echo '<h3 title="Odcenenia za počet úlovkov podľa zdroja">' . ($s == 'w' ? "Domáci AdHunter" : "Terénny AdHunter") . '</h3>';
        switch ($t) {
            case ($t >= 0 && $t <1):{//20
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            case ($t >= 1 && $t <2):{//50
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            case ($t >= 2 && $t <200):{//100
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            case ($t >= 200):{
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/platinum.png" title="Platinová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            default:{
                    echo '<img src="' . assets_url() . 'img/badges/no.png" title="Zatiaľ žiadna medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break; 
            }
        }
    }
    
    echo '</br></br><hr>';
    
    foreach ($owners as $statistic) {
        $t = $statistic->bilboards;
        $s = $statistic->name;
        echo '<h3 title="Odcenenia za počet úlovkov podľa vlastníka reklamy">' . $s . '-Hunter</h3>';
        switch ($t) {
            case ($t >= 0 && $t <1):{//20
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 1 && $t <2):{//50
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 2 && $t <200):{//100
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 200):{
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/platinum.png" title="Platinová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            default:{
                    echo '<img src="' . assets_url() . 'img/badges/no.png" title="Zatiaľ žiadna medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break; 
            }
        }
    }

    /* foreach($test as $statistic)
      {
      echo  $statistic->owner.' .';
      } */
    ?>

</div>

<div class="clear"></div>