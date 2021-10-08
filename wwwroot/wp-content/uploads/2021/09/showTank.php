<?php

$result = $_POST;
if ($result['tank'] == 1) {
    echo "<br><p style='font-size:18px;'>Based on your dimensions, your tank storage capacity is <b>".(ceil($result['height']*number_format(($result['diameter'] / 2), 2)*number_format(($result['diameter'] / 2), 2)*3140))." litres</b></p>";
    echo "<br><p style='font-size:18px;'>Based on the annual rainfall in your region, if you think your tank storage capacity should be increased or optimized, then click below for more information</p>";
    echo '<br><a href="https://www.droughtwise.live/index.php/optimise-2/" target="_blank"><input class="bn5 animated animatedFadeInUp fadeInUp" value="Optimize Storage">';
}
else{
    echo "<br><p style='font-size:18px;'>Based on your dimensions, your tank storage capacity is <b>".($result['height']*number_format($result['diameter'], 2)*number_format($result['diameter'], 2))." litres</b></p>";
    echo "<br><p style='font-size:18px;'>Based on the annual rainfall in your region, if you think your tank storage capacity should be increased or optimized, then click below for more information</p>";
    echo '<br><a href="https://www.droughtwise.live/index.php/optimise-2/" target="_blank"><input class="bn5 animated animatedFadeInUp fadeInUp" value="Optimize Storage">';
}


?>