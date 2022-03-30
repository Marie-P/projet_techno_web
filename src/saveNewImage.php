<?php
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  $file = $_FILES["monfichier"]["name"];
  $fileInfos = new SplFileInfo($file);
  $extension = $fileInfos->getExtension();
  $name = $fileInfos->getBasename('.'.$extension);
  $pathToSave = dirname(getcwd());
  $pathToSave .= "/utils/images/".$_FILES["monfichier"]["name"];
  $size = $_FILES["monfichier"]["size"];
  if($size < 8000000 && (!strcmp(strtolower($extension), "png") || !strcmp(strtolower($extension), "jpeg") || !strcmp(strtolower($extension), "jpg"))) {
    require_once('connect.php');
    $sql = $database->query('SELECT COUNT(*) AS nbImages FROM `imagesdata`')->fetch();
    $id = (int) $sql['nbImages'];
    // Ajout de cette nouvelle image dans la base de donnée
    $database->query("INSERT INTO `imagesData`(`id`, `name`, `type`, `size`, `path`) VALUES ('$id', '$name', '$extension', '$size', '$pathToSave')");
    // On enregistre l'image dans le repertoire
    rename($_FILES["monfichier"]["tmp_name"], "$pathToSave");
  }
?>