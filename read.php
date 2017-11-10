<?php

/**
 * Function to query information based on 
 * a parameter: in this case, nom.
 *
 */
	require "cfg/config.php";
	require "cfg/common.php";

		$connection = new PDO($dsn, $username, $password, $options);

		$sql = "SELECT name FROM animaux";
		

		$statement = $connection->prepare($sql);

		$statement->execute();

		$result = $statement->fetchAll();

	if ($result && $statement->rowCount() > 0) 
	{ ?>
		<h2>Resultats</h2>
<form method="post">
<select name="nom">
	<?php 
		foreach ($result as $Rp) 
		{ 

$nom = mb_strtoupper($Rp['name']);
?>
				<option value="<?php echo escape($Rp['name']); ?>"><?php echo escape($nom); ?>
		<?php 
		} ?>
</select>
<input type="submit" name="submit" value="View Results">
</form>
	<?php 
	} 
	else 
	{ ?>
		<blockquote>No results found for <?php echo escape($_POST['nom']); ?>.</blockquote>
	<?php
	} 
if (isset($_POST['submit'])) 
{
	
	try 
	{
		$connection = new PDO($dsn, $username, $password, $options);

		/*$sql = "SELECT *	 
						FROM animaux
						WHERE nom = :nom ";*/
$sql = "SELECT a.name, a.dateNaissance, a.id,
GROUP_CONCAT(r.heureRepas SEPARATOR ' <br/> ') heuresRepas,
GROUP_CONCAT(m.poids SEPARATOR ' + ') poids,
GROUP_CONCAT(al.name SEPARATOR ' <br/> ') nomsAliments,
GROUP_CONCAT(f.name SEPARATOR ' <br/> ') nomsFournisseurs
FROM repas r, animaux a, menus m, aliments al, fournisseurs f 
WHERE a.name = :nom
AND a.id=r.id_animaux 
AND r.id=m.id_repas 
AND m.id_aliments=al.id 
AND al.id_fournisseurs=f.id
GROUP BY a.name, a.dateNaissance";

		$nom = $_POST['nom'];

		$statement = $connection->prepare($sql);
		$statement->bindParam(':nom', $nom, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
	}
	
	catch(PDOException $error) 
	{
		echo $sql . "<br>" . $error->getMessage();
	}
}?>
		
<?php  
if (isset($_POST['submit'])) 
{
	if ($result && $statement->rowCount() > 0) 
	{ ?>
		<h2>Resultats</h2>

		<table width="100%">
			<thead>
				<tr>
					<th width="1%">#</th>
					<th>Nom</th>
					<th>Naissance</th>
					<th>Repas</th>
					<th>Fournisseur(s)</th>
					
				</tr>
			</thead>
			<tbody>
	<?php 
		foreach ($result as $Rp) 
		{ 
require "cfg/vars.php";
if ($heureUnique[0] == next($heureUnique)) {
    $heuresRep = $heureUnique[0];
    $hTxt = "<b>".$heuresRep." </b>";

}else{
$hTxt = "<b>".$heuresRep." </b>";
}
if ($fourUnique[0] == next($fourUnique)) {
    $nomsFour = $fourUnique[0];
}
if ($alimUnique[0] == next($alimUnique)) {
    $nomsAlim = $alimUnique[0];
 /*   $i=0;
    foreach ($poidsUnique as $pU){
    $i += $pU;
    }
    $poids = $i;
*/
    $poids = $poidsUnique[0];
    $pdsTxt = "<i>$poids Kg de $nomsAlim</i><br/>";

}
else{
	$i = 0;
	foreach ($alimUnique as $aU) {
     $pdsTxt .=  "<i>".$poidsUnique[$i]." Kg de " .$aU."</i><br/>";
$i++;
		}
}
?>		<tr>
				<td><?php echo escape($Rp["id"]); ?></td>
				<td><?php echo escape($Rp["name"]); ?></td>
				<td><?php echo escape($Rp["dateNaissance"]); ?></td>
				<td><?php echo "<b>".$hTxt." <br/></b>".$pdsTxt; ?></td>
				<td><?php echo $nomsFour; ?></td>
			</tr>
		<?php 
		} ?>
			</tbody>
	</table>
<a href="index.php">Back to home</a>
	<?php 
	} 
	else 
	{ ?>
		<blockquote>No results found for <?php echo escape($_POST['nom']); ?>.</blockquote>
	<?php
	} 
}?> 