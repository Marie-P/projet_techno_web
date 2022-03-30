<?php
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
?>