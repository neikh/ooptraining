function getXMLHttpRequest() {
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	
	return xhr;
}

function compte_a_rebours(sec = 600)
{
    var compte_a_rebours = document.getElementById("compte_a_rebours");

    var total_secondes = sec;

    var prefixe = "";
    if (total_secondes < 0)
    {
        prefixe = ""; // On modifie le préfixe si la différence est négatif
        total_secondes = Math.abs(total_secondes); // On ne garde que la valeur absolue
    }

    if (total_secondes > 0)
    {
        var jours = Math.floor(total_secondes / (60 * 60 * 24));
        var heures = Math.floor((total_secondes - (jours * 60 * 60 * 24)) / (60 * 60));
        minutes = Math.floor((total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60))) / 60);
        secondes = Math.floor(total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60 + minutes * 60)));

        var et = "et";
        var mot_jour = "jours,";
        var mot_heure = "heures,";
        var mot_minute = "minutes";
        var mot_seconde = "secondes";

        if (jours == 0)
        {
            jours = '';
            mot_jour = '';
        }
        else if (jours == 1)
        {
            mot_jour = "jour,";
        }

        if (heures == 0)
        {
            heures = '';
            mot_heure = '';
        }
        else if (heures == 1)
        {
            mot_heure = "heure,";
        }

        if (minutes == 0)
        {
            minutes = '';
            mot_minute = '';
        }
        else if (minutes == 1)
        {
            mot_minute = "minute";
        }

        if (secondes == 0)
        {
            secondes = '';
            mot_seconde = '';
            et = '';
        }
        else if (secondes == 1)
        {
            mot_seconde = "seconde";
        }

        if (minutes == 0 && heures == 0 && jours == 0)
        {
            et = "";
        }

        compte_a_rebours.innerHTML = prefixe + jours + ' ' + mot_jour + ' ' + heures + ' ' + mot_heure + ' ' + minutes + ' ' + mot_minute + ' ' + et + ' ' + secondes + ' ' + mot_seconde;
		sec--;
		return sec;
    }
    else
    {
        compte_a_rebours.innerHTML = 'Compte à rebours terminé.';
    }
}

var sec = 600;
function decrease(){
	sec = compte_a_rebours(sec);
}

setInterval("decrease()", 1000);

document.getElementById("name-perso").addEventListener('keyup', () => searchName(document.getElementById("name-perso").value), false);

function searchName(nom){
	var xhr = getXMLHttpRequest();
					
	xhr.onreadystatechange = async function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById("displayPicture").innerHTML = xhr.responseText;
		}
	};
	
	var url = 'charaUser.php';
	var params = 'nom='+nom;
	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

function acheterGem(){
	var xhr = getXMLHttpRequest();
					
	xhr.onreadystatechange = async function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById("ennemis").innerHTML = xhr.responseText;
		}
	};
	
	var url = 'gems.php';
	xhr.open('POST', url, true);
	var params = '';
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

function buy(nb_gem, price){
	var xhr = getXMLHttpRequest();
					
	xhr.onreadystatechange = async function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById("achat_effectue").innerHTML = xhr.responseText;
		}
	};
	
	var url = 'gems_buyed.php';
	var params = 'nb_gem='+nb_gem+"&price="+price;
	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

function spendGem(nb_gem){
	var xhr = getXMLHttpRequest();
					
	xhr.onreadystatechange = async function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById("UI").innerHTML = xhr.responseText;
		}
	};
	
	var url = 'ui.php';
	var params = 'gem_used='+nb_gem;
	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
}