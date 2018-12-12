<?php 
	require 'bootstrap.php';
	session_start();
	
	if (isset($_GET['deconnexion']))
	{
	  session_destroy();
	  header('Location: page.php');
	  exit();
	}
	if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
	{
	  $perso = $_SESSION['perso'];
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>TP : Mini jeu de combat</title>

		<meta charset="utf-8" />
	</head>
	
	<body>
	
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
			  
			  $retour = $perso->attack($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
			  
			  switch ($retour)
			  {
				case Personnage::CEST_MOI :
				  $message = 'Mais... pourquoi voulez-vous vous frapper ???';
				  break;
				
				case Personnage::PERSONNAGE_FRAPPE :
				  $message = 'Le personnage a bien été frappé !';
				  
				  $manager->update($perso);
				  $manager->update($persoAFrapper);
				  
				  break;
				
				case Personnage::PERSONNAGE_TUE :
				  $message = 'Vous avez tué ce personnage !';
				  
				  $manager->update($perso);
				  $manager->delete($persoAFrapper);
				  
				  break;
			  }
			}
		  }
		}
	?>

    <p>Nombre de personnages créés : <?php echo $manager->compterPersonnage(); ?></p>
		<?php
			if (isset($message)) // On a un message à afficher ?
			{
			  echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
			}
			if (isset($perso)) // Si on utilise un personnage (nouveau ou pas).
			{
		?>
			<p><a href="?deconnexion=1">Déconnexion</a></p>
			<fieldset>
			  <legend>Mes informations</legend>
			  <p>
				Nom : <?php echo htmlspecialchars($perso->nom()); ?><br />
				Dégâts : <?php echo $perso->degats(); ?>
			  </p>
			</fieldset>
			
			<fieldset>
			  <legend>Qui frapper ?</legend>
			  <p>
		<?php
			$persos = $manager->recupererPersonnage($perso->nom());
				if (empty($persos))
				{
				  echo 'Personne à frapper !';
				}
				else
				{
				  foreach ($persos as $unPerso)
					echo '<a href="?frapper=', $unPerso->id(), '">', htmlspecialchars($unPerso->nom()), '</a> (dégâts : ', $unPerso->degats(), ')<br />';
				}
		?>
			  </p>
			</fieldset>
		<?php
			}
			else
			{
		?>
			<form action="" method="post">
			
				<div id="perso-creation">
				
					<div class="landscape">
						<img src="avatar/0-pigeon.png" height="250">
						<div class="name-input"> <input type="text" name="nom" maxlength="50" placeholder="entrez un nom"/> </div>
					</div>
					
					<div class="form-button">
						<input type="submit" value="Créer ce personnage" name="creer" /><br />
						<input type="submit" value="Utiliser ce personnage" name="utiliser" />
					</div>
					
				</div>
				
			</form>
		<?php
			}
		?>
  </body>
</html>

<?php
	if (isset($perso)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
	{
	  $_SESSION['perso'] = $perso;
	}
?>