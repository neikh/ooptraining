<?php
	require 'bootstrap.php';
	session_start();
	
	$db = new PDO('mysql:host=localhost;dbname=php', 'php', 'php');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$manager = new PersonnageRepository($db);

	if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
	{
		$perso = $_SESSION['perso'];
		
		if (isset($_POST['gem_used'])){
			$perso->depenserGemmes($_POST['gem_used'], 'energie');
			$manager->acheterGemmes($perso);
		}
	}
	
	echo '<div class="elementUI">Energie <br /><progress value="'.$perso->energie().'" max="3"></progress></div>';
	
	
?>