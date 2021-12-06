<?php
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_POST['Azione'] == "I")
        $sql = 'CALL Prodotto_Insert(?, ?, ?, ?, ?)';
    else $sql = 'CALL Prodotto_Update(?, ?, ?, ?, ?, ?)';
   
    $stmt = $conn->prepare($sql);
    if ($stmt){
        if ($_POST['Azione'] == "I")
            $stmt->bind_param("sssss",$_POST['Descrizione'], $_POST['Giacenza'], $_POST['Tipologia'], $_POST['DataRifornimento'] , $_POST['Note']);
        else {
            $stmt->bind_param("ssssss",$_POST['Descrizione'], $_POST['Giacenza'], $_POST['Tipologia'], $_POST['DataRifornimento'], $_POST['Note'] ,$_POST['IdProdotto']);
            }
    }else {
            $error =  $conn->error;
               echo $error;
        }
    $stmt->execute();
    header('Location:/../magazzino.php');
?>