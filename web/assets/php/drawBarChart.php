<?php
header("Content-type: image/png");
 /* CAT:Bar Chart */

 /* pChart library inclusions */
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");

 
 $MyData = new pData();
 $a = json_decode($_GET['value']);
 $record =$_GET['value2'];
 $catches=array();
 $labels=array();
 foreach ($a as $statistic) {
        array_push($catches,$statistic->bilboards) ;
        array_push($labels,$statistic->catch_date) ;
        if($statistic->catch_date == $record) $color = array("R"=>150,"G"=>24,"B"=>24,"Alpha"=>100);
        else $color = array("R"=>188,"G"=>224,"B"=>46,"Alpha"=>100);
        $Palette[] = $color;
 }
 $MyData->addPoints($catches,"Denná Aktivita");
 $MyData->setAxisName(0,"Pocet úlovkov");
 $MyData->addPoints($labels,"Labels");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(500,230,$MyData);

 /* Draw the background */
 $Settings = array("R"=>256, "G"=>256, "B"=>256, "Dash"=>0, "DashR"=>200, "DashG"=>200, "DashB"=>200);
 $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
 $myPicture->drawText(250,55,"Uplynulý mesiac",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Draw the scale and the 1st chart */
 $myPicture->setGraphArea(60,60,450,190);
 $myPicture->drawFilledRectangle(60,60,450,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
 $myPicture->drawScale(array("DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_START0));
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
 $myPicture->drawBarChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette));
 $myPicture->setShadow(FALSE);

 /* Write the chart legend */
 $myPicture->drawLegend(210,215,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawBarChart.png");

?>