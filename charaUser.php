<?php
	require "bootstrap.php";
	
	$db = new PDO('mysql:host=localhost;dbname=php', 'php', 'php');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.
	$manager = new PersonnageRepository($db);
	
	$persos = $manager->verifierPersonnage($_POST['nom']);
	
	if ($persos == true){
		
		$chara = $manager->selectionnerPersonnage($_POST['nom']);
		
		if (file_exists('avatar/'.$chara->id().'-'.$chara->nom().'.png')){
			echo '<img src="avatar/'.$chara->id().'-'.$chara->nom().'.png" height="250">';
		} else {
			echo '<img src="avatar/0-pigeon.png" height="250">';
		}
		
	} else {
		
		echo '<img src="avatar/0-pigeon.png" height="250">';
		
	}
	
?>