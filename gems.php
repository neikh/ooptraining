<p> Choisissez votre pack de gemmes </p>
<?php
	$gems = array(
				array(25,0.99),
				array(130,4.99),
				array(275,9.99),
				array(575,19.99),
				array(1500,49.99),
				array(3125,99.99),
			);
			
	for ($i = 0; $i < 6; $i++){
		echo '<div class="opponent"><div class="photo"><img src="images/gem'.$i.'.png"></div><a href="#" class="myButton" onclick="buy('.$gems[$i][0].','.$gems[$i][1].'); return false">Acheter</a></div>';
	}
?>

<p id="achat_effectue"></p>