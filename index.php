<?php require "templates/header.php"; ?>

<?php

echo '<div class="right"><h1>';
echo date('d M Y').' </h1>';
echo '<h2>'.date('h:i:s');
echo '</h2></div>';


/**
 * Function to query information based on 
 * a parameter: in this case, nom.
 *
 */
 
require "cfg/config.php";
require "cfg/common.php";

		$connection = new PDO($dsn, $username, $password, $options);

$sql = "SELECT a.name,
GROUP_CONCAT(r.heureRepas SEPARATOR ' <br/> ') heuresRepas,
GROUP_CONCAT(m.poids SEPARATOR ' + ') poids,
GROUP_CONCAT(al.name SEPARATOR ' <br/> ') nomsAliments,
GROUP_CONCAT(f.name SEPARATOR ' <br/> ') nomsFournisseurs
FROM repas r, animaux a, menus m, aliments al, fournisseurs f 
WHERE a.id=r.id_animaux 
AND r.id=m.id_repas 
AND m.id_aliments=al.id
AND al.id_fournisseurs=f.id
GROUP BY r.heureRepas, a.name";

		$statement = $connection->prepare($sql);

		$statement->execute();

		$result = $statement->fetchAll();

	if ($result && $statement->rowCount() > 0) 
	{ 

?>
<ul>
	<li><a href="create.php"><strong>Create</strong></a> - add a user</li>
	<li><a href="read.php"><strong>Read</strong></a> - find a user</li>
</ul>
<h2>Resultats</h2>

		<table width="100%">
			<thead>
				<tr>
					<th>Nom</th>
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
				<td><?php echo escape($Rp["name"]); ?></td>
				<td><?php echo "<b>".$hTxt." <br/></b>".$pdsTxt; ?></td>
				<td><?php echo $nomsFour; ?></td>
			</tr>
		<?php 
		} ?>
			</tbody>
	</table>
	<?php 
	} 
	else 
	{ ?>
		<blockquote>No results found for <?php echo escape($_POST['nom']); ?>.</blockquote>
	<?php
	} 
include "templates/footer.php"; ?>