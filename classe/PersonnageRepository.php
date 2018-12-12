<?php
	class PersonnageRepository{
		
		private $_db;
		
		public function __construct($db){
			$this->setDb($db);
		}
		
		public function creerPersonnage(Personnage $perso){
			$query = $this->_db->prepare('INSERT INTO personnages(nom) VALUES(:nom)');
			$query->bindValue(':nom', $perso->nom());
			$query->execute();
			
			$perso->hydrate([
				'id' => $this->_db->lastInsertId(),
				'degats' => 0,
			]);
		}
		
		public function modifierPersonnage(Personnage $perso){
			$q = $this->_db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');
			
			$q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
			$q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

			$q->execute();
		}
		
		public function acheterGemmes(Personnage $perso){
			$q = $this->_db->prepare('UPDATE personnages SET gemmes = :gemmes, moneySpent = :moneySpent WHERE id = :id');
			
			$q->bindValue(':gemmes', $perso->gemmes(), PDO::PARAM_INT);
			$q->bindValue(':moneySpent', $perso->moneySpent(), PDO::PARAM_INT);
			$q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

			$q->execute();
		}
		
		public function effectuerAction(Personnage $perso){
			if (is_int($perso->energie())){
				$q = $this->_db->prepare('UPDATE personnages SET energie = :energie WHERE id = :id');
				
				$q->bindValue(':energie', $perso->energie(), PDO::PARAM_INT);
				$q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

				$q->execute();
			}
		}
		
		public function supprimerPersonnage(Personnage $perso){
			$this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
		}
		
		public function selectionnerPersonnage($info){
			if (is_int($info)){
			  $q = $this->_db->query('SELECT id, nom, degats FROM personnages WHERE id = '.$info);
			  $donnees = $q->fetch(PDO::FETCH_ASSOC);
			  
			  return new Personnage($donnees);
			}
			else
			{
			  $q = $this->_db->prepare('SELECT id, nom, degats, gemmes, moneySpent, energie FROM personnages WHERE nom = :nom');
			  $q->execute([':nom' => $info]);
			
			  return new Personnage($q->fetch(PDO::FETCH_ASSOC));
			}
		}
		
		public function compterPersonnage(){
			return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
		}
		
		public function recupererPersonnage(string $nom){
			 $persos = [];
    
			$q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
			$q->execute([':nom' => $nom]);
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
			{
			  $persos[] = new Personnage($donnees);
			}
			
			return $persos;
		}
		
		public function recupererTousPersonnage(){
			$persos = [];
    
			$q = $this->_db->prepare('SELECT id, nom FROM personnages ORDER BY id DESC limit 0,10');
			$q->execute();
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
			{
			  $persos[] = new Personnage($donnees);
			}
			
			return $persos;
		}
		
		public function verifierPersonnage($info){
			if (is_int($info)){
			  return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
			}
			
			$query = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
			$query->execute([':nom' => $info]);
			
			return (bool) $query->fetchColumn();
		}
				
		public function setDb(PDO $db){
			return $this->_db = $db;
		}
	}
?>