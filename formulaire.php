<?php
/* Connexion à une base ODBC avec l'invocation de pilote */
$dsn = 'mysql:dbname=ZOO;host=127.0.0.1';
$user = 'root';
$password = 'rooted';
 
try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
if (isset ($_POST['valider'])){
                $nom=$_POST['nom'];
                $birth=$_POST['date'];
                $dbh->exec("INSERT INTO animaux(id,name,dateNaissance) VALUES('','$nom','$birth')");
            }
?>