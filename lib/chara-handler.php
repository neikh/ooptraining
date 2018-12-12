<?php
	$db = new PDO('mysql:host=localhost;dbname=php', 'php', 'php');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.
	$manager = new PersonnageRepository($db);
	if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage.
	{
	  $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.
	  
	  if (!$perso->nomValide())
	  {
		$message = 'Le nom choisi est invalide.';
		unset($perso);
	  }
	  elseif ($manager->verifierPersonnage($perso->nom()))
	  {
		$message = 'Le nom du personnage est déjà pris.';
		unset($perso);
	  }
	  else
	  {
		$manager->creerPersonnage($perso);
	  }
	}
	elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
	{
	  if ($manager->verifierPersonnage($_POST['nom'])) // Si celui-ci existe.
	  {
		$perso = $manager->selectionnerPersonnage($_POST['nom']);
	  }
	  else
	  {
		$message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
	  }
	} elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
	{
	  if (!isset($perso))
	  {
		$message = 'Merci de créer un personnage ou de vous identifier.';
	  }
	  
	  else
	  {
		if (!$manager->verifierPersonnage((int) $_GET['frapper']))
		{
		  $message = 'Le personnage que vous voulez frapper n\'existe pas !';
		}
		
		else
		{
		  $persoAFrapper = $manager->selectionnerPersonnage((int) $_GET['frapper']);

			$retour = $perso->attack($persoAFrapper);
			$energie = $perso->depenseEnergie();
			$manager->effectuerAction($perso);
			
			if ($energie > 0){
				
			  switch ($retour)
			  {
				case Personnage::CEST_MOI :
				  $message = 'Mais... pourquoi voulez-vous vous frapper ???';
				  break;
				
				case Personnage::PERSONNAGE_FRAPPE :
				  $message = 'Le personnage a bien été frappé !';
				  
				  $manager->modifierPersonnage($perso);
				  $manager->modifierPersonnage($persoAFrapper);
				  
				  break;
				
				case Personnage::PERSONNAGE_TUE :
				  $message = 'Vous avez tué ce personnage ! Toutes ses gemmes ont été perdues... Mais il pourra les récupérer en payant plus de gemmes !';
				  
				  $manager->modifierPersonnage($perso);
				  $manager->supprimerPersonnage($persoAFrapper);
				  
				  break;
			  }
			} else {
					if ($perso->gemmes() >= 50){
						$message = '<br />Vous n\'avez plus d\'énérgie...<br /> Voulez-vous recharger votre barre contre 50 gemmes ?<br /><br /><a href="#" id="spendGem" class="myButton" onclick="spendGem(50); return false">Recharger</a>';
					} else {
						$message = '<br />Vous n\'avez plus d\'énérgie...<br /> Et vous n\'avez pas de gemmes pour recharger votre barre :\'(<br /><br /><div id="moreGems" class="myButton" onclick="acheterGem(); return false">Dépenser 😍</div>';
					}
			}
		}
	  }
	}
?>