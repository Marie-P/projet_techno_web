<?php
  require_once('src/connect.php');

  if($_POST['prompt'] == "my-secret-pw"){
    echo $_SERVER['HTTP_ORIGIN'] . '/src/toModif.php';
    header("Location: " . $_SERVER['HTTP_ORIGIN'] . '/src/toModif.php');
  }

  // on vérifie si on a un paramètre dans le get qui s'appelle page et on vérifie s'il n'est pas vide
  if(isset($_GET['page']) && !empty($_GET['page'])){ 
    $currentPage = (int) $_GET['page'];
  } else { // si c'est vide, on dit qu'on est à la page 1
    $currentPage = 1;
  }
  if(isset($_POST["reload"])){
    require_once('src/getFromDocs.php');
  }
  if(isset($_POST["valider"])){
    require_once('src/saveNewImage.php'); 
  }
  require_once('src/catalogueImage.php'); 
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> </link>
  </head>
  <body>
    <div style="background-color:rgb(253, 199, 122);border:solid;border-color:rgb(253, 199, 122);">
    <form method="post">
      <input type="password" name="prompt" style="float:right;"/>
      <input type="submit" value="Admin" style="float:right;">
    </form>
    </div>
    <div style="text-align:center;background-color:rgb(253, 199, 122);border:solid;border-color:rgb(253, 199, 122);">
      <h1>Catalogue d'Images</h1>
    </div>
  </div>
  
  <center>
    <div>
      <form method="post" action="" enctype="multipart/form-data">
        <input style="float:right;height:30px" type="submit" name="reload" value="Recharger des images à partir de docs"></input>
        <input type="file" name="monfichier"/>
        <input formaction="src/saveNewImage.php" name="valider" type="submit" value="Upload"/>
        <p> Seuls les formats .jpeg, .png sont acceptés et la taille maximale est de 8Mo ! </p>
      </form>
    </div>
  </center>
    <?php require_once('src/lectureRecursive.php'); ?>
    <center>
      <div style="float:right;padding:20px;width:55%;">
        <div class="wrapper" style="border:solid;border-color:rgb(61, 56, 48);width:100%;height:100%;">
          <?php foreach($images as $image){ ?>
            <div class=image style="border:thick double;border-color:rgb(61, 56, 48);" >
              <?= ('<img src="/utils/images/'.$image['name'].'.'.$image['type'].'"/><br/>'); ?>
            </div>
          <?php } ?>
        </div>
        <div>
          <?php if ($currentPage != 1){ ?> <!-- on affiche "précédente" si on est pas sur la première page -->
            <a href="./?page=<?= $currentPage - 1 ?>">Précédente</a>
          <?php } ?>
          <?php for($page = 1; $page <= $nbPages; $page++){ ?> <!-- on affiche tous les numéros de pages, lorsqu'il est celui en cours, on le notifie en "active" -->
              <a href="./?page=<?= $page ?>" class="page-link <?= ($currentPage == $page) ? "active" : "" ?>"><?= $page ?></a>
          <?php } ?>
          <?php if ($currentPage != $nbPages){ ?> <!-- on affiche "suivante" si on est pas sur la dernière page -->
            <a href="./?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
          <?php } ?>
        </div>
      </div>
    </center> 
    <script>
      var coll = document.getElementsByClassName("collapsible");
      var i;
      for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
          this.size = 0;
          this.classList.toggle("active");
          var content = this.nextElementSibling;
          if (content.style.maxHeight){
            content.style.maxHeight = null;
          } else {
            this.size += content.scrollHeight;
            for (let i = 0; i < content.getElementsByClassName("content").length; i++) {
              this.size += content.getElementsByClassName("content")[i].scrollHeight; 
            }
            content.style.maxHeight = this.size + "px";
          }
        });
      }
    </script>
  </body>
</html>