<?php
// fixe le délai d'expiration du programme
set_time_limit (500);
$path= "docs";

echo '<div style="width: 400px;height:400px;border: solid; overflow-y: scroll;float:left;border-color:rgb(61, 56, 48);">';

// appel de la fonction récursive qui va nous permettre d'analyser le répertoire docs
explorerDir($path);
echo '</div>';

function explorerDir($path){
	// Pour ouvrir le dossier du chemin $path 
	$folder = opendir($path);
	
	// on récupère le nom de la prochaine entrée du dossier et si elle n'est pas vide on entre dans la boucle
	while($entree = readdir($folder))
	{		
		// on ne touche pas aux repertoires . et .. (l'une désigne le repertoire courant l'autre le repertoire parent)
		if($entree != "." && $entree != "..")
		{
			// test si le fichier est un dossier
			if(is_dir($path."/".$entree))
			{
				// Afficher les répertoires
        echo "<button class='collapsible'></button> " . $entree .  " ";

        echo "<div class='content'>";
				// pour garder le repertoire courant de notre recherche actuelle
				$sav_path = $path;
				// ajoute le nouveau repertoire trouvé
				$path .= "/".$entree;
				// rappelle la fonction sur ce nouveau repertoire
				explorerDir($path);
				// pour revenir sur le parent
				$path = $sav_path;
			}
			else{
				// on récupère le chemin entier en plus du fichier
				$path_source = $path."/".$entree;				
				
				// On récupère les informations du fichier
				$fileInfos = new SplFileInfo($entree);
				$extentionAllowed = array("png", "jpeg", "jpg");
				$extension = $fileInfos->getExtension();
				$nameFile = $fileInfos->getBasename('.'.$extension);
				$pathToSave =  getcwd() . '/' . $path_source;
				$size =  filesize($path_source);
				//Si c'est un .png ou un .jpeg		
				//Alors je ferais quoi ? Devinez !
				//...

				if(in_array(strtolower($extension), $extentionAllowed)) // On affiche le nom du fichier avec son image
          echo "<img style='height: 15px;border: solid;' src='" . $path_source."' /> ";
					
				else // On affiche le nom du fichier
          echo "<img src='utils/fichier.png' style='height: 20px;'/> ";
        echo $entree . "</br>";
				$database = null;
			}
		}
	}
  echo "</div>";
	closedir($folder);
}
?>