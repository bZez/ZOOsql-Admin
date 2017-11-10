<?php

$nom = mb_strtoupper($Rp['name']);
$nomsFour = $Rp['nomsFournisseurs'];
$nomsAlim = $Rp['nomsAliments'];
$heuresRep = $Rp['heuresRepas'];
$poids = $Rp['poids'];
$heureUnique = explode (" <br/> ", $heuresRep);
$fourUnique = explode(" <br/> ", $nomsFour);
$alimUnique = explode(" <br/> ", $nomsAlim);
$poidsUnique = explode(" + ", $poids);
$pdsTxt = '';
$cmTxt = '';
$hTxt ='';