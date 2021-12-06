<?php
 include_once('../config.php');
 
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_POST['Azione'] == "I")
        $sql = 'CALL Costo_Insert(?, ?, ?, ?)';
    else $sql = 'CALL Costo_Update(?, ?, ?, ?, ?)';

   
    $stmt = $conn->prepare($sql);
    if ($stmt){
        if ($_POST['Azione'] == "I")
            $stmt->bind_param("ssss", $_POST['IdMassaVino'], $_POST['Descrizione'], $_POST['Valore'], $_POST['DataCosto']);
        else {
            $stmt->bind_param("sssss", $_POST['IdCosto'], $_POST['IdMassaVino'], $_POST['Descrizione'], $_POST['Valore'], $_POST['DataCosto']);
            }
    }else {
            $error =  $conn->error;
               echo $error;
        }
       
    $stmt->execute();
    header('Location:/../costi.php?IdMassaVino='. $_POST['IdMassaVino']);
?>