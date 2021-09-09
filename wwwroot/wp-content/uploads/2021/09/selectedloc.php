<?php

$q = $_REQUEST["q"];
$r = $_REQUEST["r"];

echo "<p class='animated animatedFadeInUp fadeInUp' style='width: 100%; text-align: center; font-weight:bold; font-size:24px;'>".$q."</p>";

echo '
<form action="https://www.droughtwise.live/index.php/calculation_result/" method="post">
<p class="animated animatedFadeInUp fadeInUp" style="width: 100%; text-align: center;">What is the area of your roof ? &nbsp;&nbsp; <input class="animated animatedFadeInUp fadeInUp" type="number" name="roof_area" step="any" required> &#13217;<br></br><button class="bn4 animated animatedFadeInUp fadeInUp" type="submit">Calculate<br></br></p>

<input type="hidden" name="rainfall" value="'.$r.'" />
<input type="hidden" name="location" value="'.$q.'" />
</form>
';


?>