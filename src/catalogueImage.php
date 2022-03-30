<?php
  // On récupère le nombre d'image total
  $sql = $database->query('SELECT COUNT(*) AS nbImages FROM `imagesdata`')->fetch();
  // On sécurise en castant en un entier 
  $nbImages = (int) $sql['nbImages'];
  // On détermine le nombre total d'images par page
  $imagesDisplayed = 6;
  // On arrondi à l'entier supérieur, pour garder ceux qui sont en plus des pages
  $nbPages = ceil($nbImages/$imagesDisplayed);
  // Pour avoir la première image de la page
  $first = ($currentPage * $imagesDisplayed) - $imagesDisplayed; // La personne qui se trouve en haut de la page
  // On récupère les informations de la première image de la page et les images suivante en fonction de la limite fixé
  $query = $database->prepare('SELECT * FROM `imagesdata` ORDER BY id DESC LIMIT :first, :imagesDisplayed'); // on commence à :first jusqu'au numéro :imagesDisplayed
  $query->bindValue(':first', $first, PDO::PARAM_INT);
  $query->bindValue(':imagesDisplayed', $imagesDisplayed, PDO::PARAM_INT);
  $query->execute();
  $images = $query->fetchAll(PDO::FETCH_ASSOC);
?>