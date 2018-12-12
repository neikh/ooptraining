<?php
	require 'bootstrap.php';
	session_start();
	
	$db = new PDO('mysql:host=localhost;dbname=php', 'php', 'php');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$manager = new PersonnageRepository($db);

	if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
	{
	  $perso = $_SESSION['perso'];
	  $achat = $perso->acheterGemmes($_POST['nb_gem'],$_POST['price']);
	  $manager->acheterGemmes($perso);
	}

	switch ($achat){
		case 1:
			$message = "<h1>Oh non!</h1>Le bateau qui a livré vos gemmes a coulé en pleine mer, toutes vos gemmes sont perdues...<br />Seul motif de satisfaction, votre paiement est arrivé en totalité. Ouf!";
			break;
		case 2:
			$message = "<h1>Oh non!</h1>Des pirates ont attaqué le bateau qui voulait livrer vos belles gemmes et ont pillé une partie de sa cargaison... Surement vos adversaire jaloux de vos talents, vous devriez acheter encore plus de gemmes pour leur montrer qui est le patron !<br />Seul motif de satisfaction, votre paiement est arrivé en totalité. Ouf!";
			break;
		case 3:
			$message = "<h1>Oh non!</h1>Votre cargaison est arrivée en totalité malgré les très faible pro... euh, merde <h1>Groovy!</h1>Vos gemmes sont toutes arrivées !<br />Evidemment, votre paiement est arrivé en totalité. Nous sommes toujours fiables, nous!";
			break;
	}
	echo $message;
	
	
?>