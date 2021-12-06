<?php
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = 'CALL SubMassaVino_Delete(?,?)';

    $stmt = $conn->prepare($sql);
    if ($stmt){
            $stmt->bind_param("ss", $_GET['IdSubMassa'], $_GET['IdMassaVino']);
    }else {
            $error =  $conn->error;
               echo $error;
        }
    $stmt->execute();
  
    header("Location:/../subMassaVino.php?IdMassaVino=" . $_GET['IdMassaVino']);
?>