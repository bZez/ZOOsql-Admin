<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */
require "cfg/config.php";
	require "cfg/common.php";

if (isset($_POST['submit']))
{
	
	

	try 
	{
		$connection = new PDO($dsn, $username, $password, $options);
		
		$new_user = array(
			"name" => $_POST['nom'],
			"dateNaissance"  => $_POST['dateNaissance'],
		);
		$sql = sprintf(
				"INSERT INTO %s (%s) values (%s)",
				"animaux",
				implode(", ", array_keys($new_user)),
				":" . implode(", :", array_keys($new_user))
		);
		$statement = $connection->prepare($sql);
		$statement->execute($new_user);
try 
	{
		$connection = new PDO($dsn, $username, $password, $options);
		$new_menu = array(
			"id"  => '',
			"id_repas"  => '',
			"id_aliments"  => $_POST['nom_al'],
			"poids"  => $_POST['poids'],
		);
		$sql = sprintf(
				"INSERT INTO %s (%s) values (%s)",
				"menus",
				implode(", ", array_keys($new_menu)),
				":" . implode(", :", array_keys($new_menu))
		);
		$statement = $connection->prepare($sql);
		$statement->execute($new_menu);
	}
	catch(PDOException $error) 
	{
		echo $sql . "<br>" . $error->getMessage();
	}
	}

	catch(PDOException $error) 
	{
		echo $sql . "<br>" . $error->getMessage();
	}
	
}
?>

<?php require "templates/header.php"; ?>

<?php 
if (isset($_POST['submit']) && $statement) 
{ ?>
	<blockquote><?php echo $_POST['nom']; ?> ajouté avec succès.</blockquote>
<?php 
} 
		$connection = new PDO($dsn, $username, $password, $options);

		$sql = "SELECT * FROM aliments";
		

		$statement = $connection->prepare($sql);

		$statement->execute();

		$result = $statement->fetchAll();

	if ($result && $statement->rowCount() > 0) 
	{ ?>
		<h2>Resultats</h2>
<form method="post">
	<label for="nom">Nom de l'animal</label>
	<input type="text" name="nom" id="nom">
	<label for="age">Naissance</label>
	<input type="text" name="dateNaissance" id="dateNaissance" value="<?php echo date('Y-m-d h:i:s'); ?>" >
	<label for="nom_al">Aliments</label>
<select name="nom_al">
	<?php 
		foreach ($result as $Rp) 
		{ 

$nom = mb_strtoupper($Rp['name']);
?>
				<option name="id_al" id="id_al" value="<?php echo escape($Rp['id']); ?>"><?php echo escape($nom); ?>
		<?php 
		} ?>
</select>
	<label for="poids">Quantité</label>
	<input type="text" name="poids" id="poids">
	<input type="submit" name="submit" value="Submit">
</form>
	<?php 
	} 
	else 
	{ ?>
		<blockquote>No results found for <?php echo escape($_POST['nom']); ?>.</blockquote>
	<?php
	} 

?>
<a href="index.php">Retour Accueil</a>

<?php require "templates/footer.php"; ?>