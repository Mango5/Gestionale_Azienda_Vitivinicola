<?php
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if ($_POST['Azione'] == "I")
        $sql = 'CALL SubMassaVino_Insert(?, ?, ?)';
    else $sql = 'CALL SubMassaVino_Update(?, ?, ?, ?, ?, ?)';
   
    $stmt = $conn->prepare($sql);
    if ($stmt){
        if ($_POST['Azione'] == "I")
            $stmt->bind_param("sss",$_POST['IdMassaVino'],$_POST['IdMassaOrigine'], $_POST['Percentuale']);
        else {
            $stmt->bind_param("ssssss",$_POST['Descrizione'], $_POST['Percentuale'], $_POST['CostoDerivato'], $_POST['QuantitaDerivata'], $_POST['IdSubMassa'] ,$_POST['IdMassaVino']);
            }
    }else {
            $error =  $conn->error;
               echo $error;
        }
    $stmt->execute();
    header('Location:/../subMassaVino.php?IdMassaVino='. $_POST['IdMassaVino']);
?>