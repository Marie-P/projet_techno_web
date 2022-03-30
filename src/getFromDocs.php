<?php
header('Location: ' . $_SERVER['HTTP_REFERER']);
// fixe le délai d'expiration du programme
set_time_limit (500);
$path= "docs";
// appel de la fonction récursive qui va nous permettre d'analyser le répertoire docs
saveDir($path);

function saveDir($path){
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
				// pour garder le repertoire courant de notre recherche actuelle
				$sav_path = $path;
				// ajoute le nouveau repertoire trouvé
				$path .= "/".$entree;
				// rappelle la fonction sur ce nouveau repertoire
				saveDir($path);
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

				// connection à la bdd
				try {
			    $port="3306";
			    $db="imagesdata";
			    $user='root';
			    $pass='my-secret-pw';
			    $connect = "mysql:host=localhost:$port;dbname=$db";
			    $database = new PDO($connect, $user, $pass);
			  } catch (PDOException $e) {
			      print "Erreur !: " . $e->getMessage() . "<br/>";
			  }

				// Si le fichier n'est pas déjà enregistré dans la bdd, on l'insère
        $sql = $database->query('SELECT COUNT(*) AS nbImages FROM `imagesdata`')->fetch();
        $id = (int) $sql['nbImages'];
				$sql = $database->query("SELECT COUNT(*) AS nbImages FROM `imagesdata` WHERE `name` = '$nameFile' AND `type` = '$extension' AND `size` = '$size' AND `path` = '$pathToSave'")->fetch();
				if((int) $sql['nbImages'] == 0){
          if(in_array(strtolower($extension), $extentionAllowed)){
            $sql = $database->query('SELECT COUNT(*) AS nbImages FROM `imagesdata`')->fetch();
            $id = (int) $sql['nbImages'];
            // Ajout de cette nouvelle image dans la base de donnée
            $database->query("INSERT INTO `imagesdata`(`id`, `name`, `type`, `size`, `path`) VALUES ('$id', '$nameFile', '$extension', '$size', '$pathToSave')");
            // On enregistre l'image dans le repertoire
            $uploads_dir = getcwd() . "/utils/images/";
            $uploads_dir .= $entree;
						$currentPath = getcwd().'/'.$path_source . "</br>";
						copy($path_source,$uploads_dir);
          }
        }
				$database = null;
			}
		}
	}
  echo "</div>";
	closedir($folder);
}
?>