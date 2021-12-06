<?php
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = 'CALL MassaVino_Delete(?)';
   
    $stmt = $conn->prepare($sql);
    if ($stmt){
            $stmt->bind_param("s", $_GET['IdMassaVino']);
    }else {
            $error =  $conn->error;
               echo $error;
        }
    $stmt->execute();
    header('Location:/../massaVino.php');
?>