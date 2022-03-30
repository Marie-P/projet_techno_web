<?php
  require('connect.php');
  // on vérifie si on a un paramètre dans le get qui s'appelle page et on vérifie s'il n'est pas vide
  if(isset($_GET['page']) && !empty($_GET['page'])){ 
    $currentPage = (int) $_GET['page'];
  } else { // si c'est vide, on dit qu'on est à la page 1
    $currentPage = 1;
  }
  if(is_numeric($_POST['idToDelete'])){
    $images = $database->query('SELECT * FROM `imagesdata`')->fetchAll(PDO::FETCH_ASSOC);
    $id = $_POST['idToDelete'];
    $database->query("DELETE FROM `imagesData` where `id`= '$id'");
    for ($i=$id; $i < sizeof($images); $i++) {
        $database->query("UPDATE `imagesdata` SET `id`='$i' WHERE `id` = $i + 1");
      }
    }
  require_once('catalogueImage.php'); 
  if($_POST['deleteAll']){
    $images = deleteAll($nbImages);
  }
?>
<script>
  function back(){
    document.location = document.location.origin ;
  }
</script>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/style.css"> </link>
  </head>
  <body>
    <button onclick="back()">Retour page précédente</button></br></br> 
    <div id="result"></div>

    <center>
      <form action="" method="POST">
        <input type="submit" name="deleteAll" value="Pour supprimer toutes les images" />
      </form>
    </center>

    <center>
      <div style="width:65%;">
        <div class="wrapper" style="border:solid;border-color:rgb(61, 56, 48);">
          <?php foreach($images as $image){ ?>
              <div class=image style="border:thick double;border-color:rgb(61, 56, 48);" method="post">
                <form style="float:right;" action="" method="POST">
                  <?= ('<input class=todelete type="hidden" name="idToDelete" value="'.$image['id'].'"/>');?>
                  <input class=todelete type="submit"  value="X" />
                </form> 
                <?= ('<img src="/utils/images/'.$image['name'].'.'.$image['type'].'"/><br/>'); ?>
                <div style="text-align: justify;font-size: 10px;">
                  <b>Name :</b> <?php echo $image['name'] ?> <br> 
                  <b> Type : </b> <?php echo $image['type'] ?> <br> 
                  <b> Taille : </b> <?php echo $image['size'] ?>
                </div>
              </div>
          <?php } ?>
        </div>
        <div>
          <?php if ($currentPage != 1){ ?> <!-- on affiche "précédente" si on est pas sur la première page -->
            <a href="/src/toModif.php/?page=<?= $currentPage - 1 ?>">Précédente</a>
          <?php } ?>
          <?php for($page = 1; $page <= $nbPages; $page++){ ?> <!-- on affiche tous les numéros de pages, lorsqu'il est celui en cours, on le notifie en "active" -->
              <a href="/src/toModif.php/?page=<?= $page ?>" class="page-link <?= ($currentPage == $page) ? "active" : "" ?>"><?= $page ?></a>
          <?php } ?>
          <?php if ($currentPage != $nbPages){ ?> <!-- on affiche "suivante" si on est pas sur la dernière page -->
            <a href="/src/toModif.php/?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
          <?php } ?>
        </div>
      </div>
      </center>
  </body>
</html>


<?php
  function deleteAll($nbImages) {
    try {
      $port="3306";
      $db="lecturerecursive";
      $user='user';
      $pass='my-secret-pw';
      $connect = "mysql:host=localhost:$port;dbname=$db";
      $database = new PDO($connect, $user, $pass);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
    }
    $images = $database->query('SELECT * FROM `imagesdata`')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($images as $image) {
      $database->query("DELETE FROM `imagesData` where `id`= $image[id]");
    }
    $query = $database->prepare('SELECT * FROM `imagesdata` ORDER BY id DESC');
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
?>