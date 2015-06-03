<?php echo $profile_menu; ?>

<div align="center" class="profile_content" charset="utf-8">
    
    <br/><h1 align="center">Aktivita</h1>
    <?php
    $result = urlencode(json_encode($activity));
    
    
    if (array_key_exists(0, $record))
    {
        $best = $record[0]->catch_date;
        echo '<img src="'.assets_url().'php/drawBarChart.php?&value='.$result.'&value2='.$best.'"/>';
    }
    else {
        echo "<br/>Za uplynulý mesiac nebola zaznamenaná žiadna aktivita.";
    }
    
    foreach ($record as $statistic) {
        echo "<br/><br/>Rekord: " . $statistic->catch_date . " - " . $statistic->bilboards . " billboardy !!!<br/>";
    }
    ?>
    <br/><h1>Top 10</h1>
    
    <table>
        <tr>
            <th>Poradie</th>
            <th>Meno</th>
            <th>Počet úlovkov</th>
        </tr>
    <?php
    $order = 1;
    foreach ($top_ten as $statistic) {
    echo '
		<tr>
                        <td>' . $order . '</td>
			<td>' . $statistic->user . '</td>
			<td>' . $statistic->bilboards . '</td>
		</tr>
		';
    $order+=1;
    }
    ?>
    </table>

</div>

<div class="clear"></div>