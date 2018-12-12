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
	
	require 'lib/chara-handler.php';
?>

<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css">
		<title>Le royaume des gemmes</title>
	</head>
	
	<body>
		<div id="header">
			<p>Le royaume des gemmes</p>
			<span>Free to play entièrement gratuit !</span>
		</div>
		
		<div id="main">
		
			<div id="part-left">
			
				<?php
					if (isset($perso)) // Si on utilise un personnage (nouveau ou pas).
					{
				?>
				
				<div id="presentation">
					<?php
						if (file_exists('avatar/'.$perso->id().'-'.$perso->nom().'.png')){
							echo '<img src="avatar/'.$perso->id().'-'.$perso->nom().'.png" height="150"><br />';
						} else {
							echo '<img src="avatar/0-pigeon.png" height="150"><br />';
						}
						
						echo htmlspecialchars($perso->nom()).'<br />';
						echo 'Dégats : '.htmlspecialchars($perso->degats()).'<br />';
						
						if ($perso->gemmes() == null){
							echo 'Gemmes : 0 <br />';
						} else {
							echo 'Gemmes : '.htmlspecialchars((int)$perso->gemmes()).'<br />';
						}
						
						if ($perso->moneySpent() == null){
							echo 'Dépensé : 0 €<br />';
						} else {
							echo 'Dépensé : '.htmlspecialchars($perso->moneySpent()).' €<br />';
						}
					?>
					<br /><br /><div id="moreGems" class="myButton" onclick="acheterGem(); return false">Gemmes 😍</div>
					<br /><a href="?deconnexion=1" class="myButton">Déconnexion</a>
				</div>
				
				<div id="ennemis">
					<div id="UI">					
						<div class="elementUI">Energie <br /><progress value="<?php echo $perso->energie(); ?>" max="3"></progress></div>
					</div>
					<p>Ennemis très méchants (l'un d'entre eux à même mal parlé de vous tout à l'heure)</p>
					<?php
						$persos = $manager->recupererPersonnage($perso->nom());
							if (empty($persos))
							{
							  echo 'Personne à frapper !';
							}
							else
							{
							  foreach ($persos as $unPerso)
								if (file_exists("avatar/".$unPerso->id()."-".$unPerso->nom().".png")){
									echo '<div class="opponent"><div class="photoPonent">'.$unPerso->nom().'<br /><img src="avatar/'.$unPerso->id().'-'.$unPerso->nom().'.png" width="150"></div><a href="?frapper=', $unPerso->id(), '" class="myButton">Attaquer 😠</a></div>';
								} else {
									echo '<div class="opponent"><div class="photoPonent">'.$unPerso->nom().'<br /><img src="avatar/0-pigeon.png" width="150"></div><a href="?frapper=', $unPerso->id(), '" class="myButton">Attaquer 😠</a></div>';
								}
							}
							
						if (isset($message)) // On a un message à afficher ?
						{
						  echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
						}
							
					?>
				</div>
				
				<?php
					}
					else
					{
				?>
				
				<p>Félicitation ! Notre algorithme de séléction de gagnants vient de vous tirer au sort ! Vous avez l'opportunité unique de créer un personnage gratuitement sur le royaume des gemmes !</p>
				<p>Cette offre unique et temporaire prendra fin dans : <span id="compte_a_rebours"></span>, il faut donc vous dépécher pour en profiter et obtenir d'autre fabuleux bonus une fois en jeu !</p> 
				<form action="" method="post">
					<div id="perso-creation">
				
						<div class="landscape">
							<span id="displayPicture">
								<img src="avatar/0-pigeon.png" height="250">
							</span>
							<div class="name-input"> <input type="text" id="name-perso" name="nom" maxlength="50" placeholder="entrez un nom"/> </div>
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
			</div>
			
			<div id="part-right">
				<div class="nb-chara">
					<p>Déjà <?php echo $manager->compterPersonnage(); ?> personnages crée absoluement gratuitement !</p>
				</div>
				<hr>
				
				<div class="nb-chara">
					<table>
						<?php
							$persos = $manager->recupererTousPersonnage();
							if (empty($persos))
							{
							  echo 'Pas de personnages crée... Soyez le premier a profiter de notre système gratuit de création de personnages !';
							}
							else
							{
							  foreach ($persos as $unPerso)
								if (file_exists("avatar/".$unPerso->id()."-".$unPerso->nom().".png")){
									echo '<tr><td valign="middle">'.$unPerso->nom().'</td><td valign="middle"><img src="avatar/'.$unPerso->id().'-'.$unPerso->nom().'.png" width="50"></td></tr>';
								} else {
									echo '<tr><td valign="middle">'.$unPerso->nom().'</td><td valign="middle"><img src="avatar/0-pigeon.png" width="50"></td></tr>';
								}
							}
						?>
					</table>
				</div>
			</div>
			
		</div>
		<script src="js/script.js"></script>
	</body>
</html>

<?php
	if (isset($perso)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
	{
	  $_SESSION['perso'] = $perso;
	}
?>