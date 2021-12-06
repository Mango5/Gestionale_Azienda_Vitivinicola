<?php
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $_POST['FlgDerivata'] = isset($_POST['FlgDerivata']) ? isset($_POST['FlgDerivata']) : 0;

    if ($_POST['Azione'] == "I")
        $sql = 'CALL MassaVino_Insert(?, ?, ?)';
    else $sql = 'CALL MassaVino_Update(?, ?, ?, ?, ?)';
   
    $stmt = $conn->prepare($sql);
    if ($stmt){
        if ($_POST['Azione'] == "I")
            $stmt->bind_param("ssss",$_POST['Descrizione'], $_POST['Quantita'],  $_POST['FlgDerivata']);
        else {
            $stmt->bind_param("ssssss",$_POST['Descrizione'], $_POST['Quantita'], $_POST['Giacenza'], $_POST['FlgDerivata'] ,$_POST['IdMassaVino']);
            }
    }else {
            $error =  $conn->error;
               echo $error;
        }
    $stmt->execute();
    header('Location:/../massaVino.php');
?>