<?php echo $profile_menu; ?>

<div align="center" class="profile_content" charset="utf-8">
    <?php
    
    echo '<br/><h1 align="center">Sumár</h1>';
    foreach ($statistic as $statistic) {
        echo "Počet úlovkov: " . $statistic->bilboards . "<br />";
    }

    $b = $statistic->bilboards;

    switch ($b) { /*podla poctu zachytenych billboardov sa stanovi level usera*/
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
    
    if($b < 400 )echo 'Do ďalšieho levelu chýba' .((($to_next_level>4)||($to_next_level==1)) ? " " : "jú ") 
            .  $to_next_level .($to_next_level>5 ? " úlovkov" : ($to_next_level==1 ? " úlovok": " úlovky")).'<br />';
    else echo "Dosiahol si maximálneho levelu v aplikácii AdHunter, neprestávaj loviť!!!";
    
    echo "Typy úlovkov: ";
    foreach ($types as $statistic) {
        echo $statistic->title . ': ' . $statistic->bilboards . ', ';
    }

    echo '<br/>Zdroj úlovkov: ';

    foreach ($sources as $statistic) { /*statistika odkial pochadzaju billboardy web/mobil*/
        if ($statistic->source == 'w')
            echo 'web: ' . $statistic->bilboards;
        if ($statistic->source == 'm')
            echo ', mobilná aplikácia: ' . $statistic->bilboards . ' ';
    }
    echo '<br/><h1 align="center">Ocenenia</h1>';
    echo '</hr>';
    
    foreach ($types as $statistic) { /*statistika typov billboardov, pridelia sa odznaky za stanovene pocty jednotlivyh typov billboardov*/
        $t = $statistic->bilboards;
        if ($statistic->title != 'other')
        {
        echo '<h3 title="Odcenenia podľa počtu úlovkov typu '.$statistic->title .'">' . $statistic->title . ': </h3>';
        switch ($t) {
            case ($t >= 5 && $t < 10): {//5
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            case ($t >= 10 && $t < 25): {//10
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov typu '.$statistic->title .'"/>';
                    break;
                }
            case ($t >= 25 && $t < 50): {//25
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
        echo '<br/>';
        }
    }
    
    echo '<br/><hr style="border-top:1px solid grey"/><br/>';
    
    foreach ($sources as $statistic) { /*statistika zdrojov billboardov, pridelia sa odznaky za stanovene pocty billboardov podla zdroja*/
        $t = $statistic->bilboards;
        $s = $statistic->source;
        echo '<h3 title="Odcenenia za počet úlovkov podľa zdroja">' . ($s == 'w' ? "Domáci AdHunter" : "Terénny AdHunter") . '</h3>';
        switch ($t) {
            case ($t >= 20 && $t <50):{//20
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            case ($t >= 50 && $t <100):{//50
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov zo zdroja '.($s == "w" ? "web" : "mobilná aplikácia").'"/>';
                    break;
                }
            case ($t >= 100 && $t <200):{//100
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
    
    echo '<br/><br/><hr style="border-top:1px solid grey"/><br/>';
    
    foreach ($owners as $statistic) { /*statistika vlastnikov billboardov, pridelia sa odznaky podla poctu ulovenych billboardov od jednotlivych vlastnikov*/
        $t = $statistic->bilboards;
        $s = $statistic->name;
        echo '<h3 title="Odcenenia za počet úlovkov podľa vlastníka reklamy">' . $s . '-Hunter</h3>';
        switch ($t) {
            case ($t >= 5 && $t <10):{//5
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 10 && $t <20):{//10
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 20 && $t <50):{//20
                    echo '<img src="' . assets_url() . 'img/badges/bronze.png" title="Bronzová medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/silver.png" title="Strieborná medaila za počet úlovkov vlastníka '.$s.'"/>';
                    echo '<img src="' . assets_url() . 'img/badges/gold.png" title="Zlatá medaila za počet úlovkov vlastníka '.$s.'"/>';
                    break;
                }
            case ($t >= 50):{
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
    ?>

</div>

<div class="clear"></div>