<?php
	class Personnage {
		
		private $_id;
		private $_degats;
		private $_nom;
		private $_gemmes;
		private $_moneySpent;
		private $_energie;
		
		const CEST_MOI = 1; //"Vous ne pouvez pas vous frapper vous-même !<br />";
		const PERSONNAGE_TUE = 2; //"Coup fatal ! ".$perso->_nom ." a été tué sur le coup...<br />";
		const PERSONNAGE_FRAPPE = 3; //$perso->_nom." a encaissé un montant de ".$damage." de dégats.<br />";
		
		const ARRIVAGE_ECHOUE = 1;
		const ARRIVAGE_PARTIEL = 2;
		const ARRIVAGE_TOTAL = 3;
		
		public function __construct(array $data){
			$this->hydrate($data);
		}
		
		public function attack(Personnage $perso){
			
			if ($perso->id() == $this->_id){
				return self::CEST_MOI;
			} else {
				return $perso->takeDamage();
			}
			
		}
		
		public function takeDamage(){
			
			$this->_degats += 5;
			
			if ($this->_degats >= 100){
				return self::PERSONNAGE_TUE;
			} 
				
			return self::PERSONNAGE_FRAPPE;
		}
		
		public function depenseEnergie(){
			
			if ($this->_energie != 0){
				if ($this->_energie == null){
					$this->_energie = 3;
				}
				$this->_energie--;
				return $this->energie();
			} else {
				return $this->energie();
			}
		}
		
		public function acheterGemmes($nb_gem, $price){
			
			$random = rand(0, 100);
			
			if ($random <= 10){
				$this->_moneySpent += $price;
				$this->_gemmes += 0;
				return self::ARRIVAGE_ECHOUE;
			}
			
			if ($random >= 90){
				$this->_moneySpent += $price;
				$this->_gemmes += $nb_gem;
				return self::ARRIVAGE_TOTAL;
			}

			$nb_gem = $nb_gem * $random	/ 100;
			$this->_moneySpent += $price;
			$this->_gemmes += $nb_gem;
			return self::ARRIVAGE_PARTIEL;
		}
		
		public function depenserGemmes($nb_gem, $raison){
			$this->_gemmes -= $nb_gem;
			
			if ($raison == "energie"){
				$this->_energie = 3;
			}
			return $this->energie();
		}
		
		public function nomValide()
		{
			return !empty($this->_nom);
		}
		
		public function degats(){
			return $this->_degats;
		}
		
		public function gemmes(){
			return $this->_gemmes;
		}
		
		public function moneySpent(){
			return $this->_moneySpent;
		}
		
		public function energie(){
			return $this->_energie;
		}
		
		public function id(){
			return $this->_id;
		}
		
		public function nom(){
			return $this->_nom;
		}
		
		public function setDegats($degats){
			$degats = (int)$degats;
			
			if ($degats >= 0 AND $degats <= 100){
				$this->_degats = $degats;
			}
		}
		
		public function setId($id){
			$id = (int)$id;
			
			if ($id > 0){
				$this->_id = $id;
			}
		}
		
		public function setGemmes($gemmes){
			$gemmes = (int)$gemmes;
			
			if ($gemmes > 0){
				$this->_gemmes = $gemmes;
			}
		}
		
		public function setMoneySpent($moneySpent){
			$moneySpent = (float)$moneySpent;
			
			if ($moneySpent > 0){
				$this->_moneySpent = $moneySpent;
			}
		}
		
		public function setEnergie($energie){
			if (is_int($energie)){
				$this->_energie = $energie;
			}
		}
		
		public function setNom($nom){
			if (is_string($nom)){
				$this->_nom = $nom;
			}
		}
		
		public function hydrate(array $data){
			foreach($data as $key => $value){
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method)){
					$this->$method($value);
				}
			}
		}

	}	
?>