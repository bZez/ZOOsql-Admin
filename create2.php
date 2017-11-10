<html>
    <head><title>Formulaire de saisie </title></head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
    <body>
        <h1>Inscrivez vous !!!</h1>
        <h2>Entrez les données demandées :</h2>
            <form name="inscription" method="post" action="#"> </br>
            <center>
           Nom: <input type="text" name="nom"/> </br>
            </br>
            Naissance : <input type="text" name="date" value="<?php echo date('Y-m-d h:i:s'); ?>"/> </br>
            </br>
            Heure: <input type="text" name="hRepas"/> </br>
            </br>
            Régime: <input type="text" name="aliments"/> </br>
<input type="text" name="aliments"/> 
            </br>
            <input type="submit" name="valider" value="Valider">
            </form>
            </center>
    </body>
</html>
 
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
		$heure=$_POST['hRepas'];
		echo $nom.' ajouté !<br/> Né(e) le: '.$birth;
                $dbh->exec("INSERT INTO animaux(id,name,dateNaissance) VALUES('','$nom','$birth')");
	if (isset ($_POST['hRepas'])){
                echo '<br/>Repas de '.$heure.' ajouté !';
                $dbh->exec("INSERT INTO repas(id,id_animaux,heureRepas) '','14','$heure'");
            }
      }
?>
