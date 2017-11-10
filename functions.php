<?php
// fonctions php d'aide
include_once 'config.php';

function echoSelectOptionsFromTable($bdd, $tableName, $displayField, $withBlank=false, $selectedId=null)
{
    if ($withBlank){
        ?>
        <option></option>
        <?php
    }

    $result = $bdd->query("SELECT * FROM ".$tableName);
    while ($row = $result->fetch_object()){
        ?>
        <option
            <?= $selectedId==$row->id ? 'selected':'' ?>
            value="<?= $row->$displayField ?>"><?= $row->$displayField ?></option>
    <?php
    }
}

function insertFromPost($bdd, $tableName, $keyAndValues){
    $keys = array();
    $values = array();

    foreach ($keyAndValues as $key => $value){
        if ($value){

            // gestion des checkbox
            if ($value == 'on'){
                $value = 1;
            }

            $keys[] = $key;
            $values[] = $value;
        }
    }
    $keysStr = "(".implode(",",$keys).")";
    $valuesStr = "('".implode("','",$values)."')";

    $queryStr = "INSERT INTO $tableName $keysStr VALUES $valuesStr";

    if (!$bdd->query($queryStr)){
        printf("Erreur : %s\n", $bdd->error);
        exit();
    }

    return $bdd->insert_id;
}

function echoOneValueRow($label, $value){
    ?>
<tr>
    <th><?= $label ?></th>
    <td><?= $value ?></td>
</tr>
<?php
}

function getOfferJoinQuery(){
    return "SELECT offers.id as offer_id, offers.*, affilates.*, departments.name as department, transactions_types.name as transaction_type, offers_types.name as offer_type FROM offers
      JOIN affilates ON affilates.id = offers.affilate_id
      JOIN departments ON departments.id = offers.departement_id
      JOIN transactions_types ON transactions_types.id = offers.transaction_types_id
      JOIN offers_types ON offers_types.id = offers.offer_types_id";
}

function getOneOffer($bdd, $id){
    
    $queryStr = getOfferJoinQuery()." WHERE offers.id = ".$id;
    if (!$result = $bdd->query($queryStr)){
        printf("Erreur : %s\n", $bdd->error);
        exit();
    }
    return $result->fetch_object();
}

/*
 * Récupère toutes les offres en filtrant et triant par les options dans $_GET
 */
function getAllOffers($bdd){

    $queryStr = getOfferJoinQuery();

    // where clause
    $wheres = array();
    foreach ($_GET as $key => $value){
        if (strpos($key, 'subdividable')!==0 && 
            strpos($key, 'order')!==0 && 
            strpos($key, 'page')!==0 && 
            strpos($key, 'surface')!==0 &&
            strpos($key, 'with_picture')!==0 &&
            strpos($key, 'display_mode')!==0 &&
            $value){
            $wheres[] = "$key = '$value'";
        }
    }
    // surface min and max
    if (isset($_GET['surface_min']) && isset($_GET['surface_max']) && $_GET['surface_min'] && $_GET['surface_max']){
        $wheres[] = "surface >= ".$_GET['surface_min'];
        $wheres[] = "surface <= ".$_GET['surface_max'];
    }

    if (isset($_GET['subdividable'])){
        $s = $_GET['subdividable'];
        if ($s=='oui' || $s=='non'){
            $wheres[] = "subdividable = ".($s=='oui' ? '1' : '0');
        }
    }
    
    if (count($wheres)>0){
        $queryStr .= " WHERE ".implode(' AND ', $wheres);
    }

    // order by
    $order = getValue('order');
    $orderDir = getValue('orderDir');
    if ($order){
        if (!$orderDir){
            $orderDir = "ASC";
        }
        $queryStr .= " ORDER BY $order $orderDir";
    }

    // requete sans la pagination pour compter le nombre total de result
    if (!$result = $bdd->query($queryStr)){
        printf("Erreur : %s\n", $bdd->error);
        exit();
    }

    if (getValue('display_mode')=='map'){
        return array('offers'=>$result);
    }

    $nbPages = round($result->num_rows / NB_OFFERS_PER_PAGE + 0.5, 0, PHP_ROUND_HALF_DOWN);

    // ajout de la pagination
    $page = getValue('page');
    if (!$page){
        $page = 0;
    }
    $queryStr .= " LIMIT ".NB_OFFERS_PER_PAGE." OFFSET ".($page*NB_OFFERS_PER_PAGE);

    // requete avec la pagination en cours
    if (!$result = $bdd->query($queryStr)){
        printf("Erreur : %s\n", $bdd->error);
        exit();
    }

    return array('offers'=>$result, 'nbPages'=>$nbPages);
}

function preDump($var){
    echo '<pre>'.var_dump($var).'</pre>';
}

function getValue($key){
    if (isset($_GET[$key])){
        return $_GET[$key];
    }
    return null;
}

function uploadFile($fileIdx){

    if (!isset($_FILES[$fileIdx]) || $_FILES[$fileIdx]['name']=="") {
        return null;
    }
    $fileDesc = $_FILES[$fileIdx];
    return uploadOneFile($fileDesc);
}
function demo(){
    alert('demo');
}

function uploadOneFile($fileDesc){

    if ($fileDesc["name"]==""){
        return null;
    }

    $uploaddir = './uploads/';
    $uploadfile = $uploaddir . basename($fileDesc['name']);

    if (!move_uploaded_file($fileDesc['tmp_name'], $uploadfile)) {
        echo "Problème d'upload :\n";
        preDump($fileDesc);

        exit;
    }
    return $uploadfile;
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function getAllCities($bdd){
    $result = $bdd->query('select city from offers');
    $cities = array();
    while ($row = $result->fetch_object()){
        $cities[] = $row->city;
    }
    return $cities;
}