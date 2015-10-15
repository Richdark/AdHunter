<?php echo $profile_menu; ?>

<div class="profile_content">
    <?php

    $adhunter_levels = array(1 => 'Nováčik', 'Začiatočník', 'Špión', 'Lovec', 'Reformátor', 'Batman', 'Záchranca svojho okolia', 'Profesionálny lovec reklám');
    $level_images = array(1 => 'no', 'bronze', 'silver', 'gold', 'platinum');

    if (isset($medals['regular']['adhunter']))
    {
        echo '<h2>Level '. $medals['regular']['adhunter']. ': '. $adhunter_levels[$medals['regular']['adhunter']]. '</h2>';
        echo '<div style="margin-bottom: 25px; font-size: 14px; font-style: italic;">Úroveň sa odvíja od celkového počtu nahraných úlovkov.</div>';
    }
    else
        echo '<h2>Zhrnutie</h2>';

    if (isset($medals['regular']['web']))
    {
        echo '<h2>Ocenenie: Domáci lovec reklám</h2>';
        echo '<div style="font-size: 14px; font-style: italic;">Za nahrávanie úlovkov prostredníctvom webovej aplikácie.</div>';
        echo '<p>';

        if ($medals['regular']['web'] == 1)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[1]. '.png" />';
        }

        for ($i = 2; $i <= $medals['regular']['web']; $i++)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[$i]. '.png" />';
        }

        echo '</p>';
    }

    if (isset($medals['regular']['mobile']))
    {
        echo '<h2>Ocenenie: Terénny lovec reklám</h2>';
        echo '<div style="font-size: 14px; font-style: italic;">Za nahrávanie úlovkov prostredníctvom mobilnej aplikácie.</div>';
        echo '<p>';

        if ($medals['regular']['mobile'] == 1)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[1]. '.png" />';
        }

        for ($i = 2; $i <= $medals['regular']['mobile']; $i++)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[$i]. '.png" />';
        }

        echo '</p>';
    }

    foreach ($medals['owner'] as $owner)
    {
        echo '<h2>Ocenenie: Lovec reklám '. $owner['name']. '</h2>';
        echo '<div style="font-size: 14px; font-style: italic;">Za nahrávanie úlovkov na nosičoch prevádzkovaných spoločnosťou '. $owner['name']. '.</div>';
        echo '<p>';

        if ($owner['level'] == 1)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[1]. '.png" />';
        }

        for ($i = 2; $i <= $owner['level']; $i++)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[$i]. '.png" />';
        }

        echo '</p>';
    }

    foreach ($medals['backing'] as $backing)
    {
        echo '<h2>Ocenenie: Lovec reklám typu '. $backing['title']. '</h2>';
        echo '<div style="font-size: 14px; font-style: italic;">Za nahrávanie úlovkov na nosičoch typu '. $backing['title']. '.</div>';
        echo '<p>';

        if ($backing['level'] == 1)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[1]. '.png" />';
        }

        for ($i = 2; $i <= $backing['level']; $i++)
        {
            echo '<img src="' . assets_url() . 'img/badges/'. $level_images[$i]. '.png" />';
        }

        echo '</p>';
    }
    ?>

</div>

<div class="clear"></div>