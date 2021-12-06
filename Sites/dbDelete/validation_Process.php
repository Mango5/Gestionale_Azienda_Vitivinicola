
<?php
//TO DO -- stored ad-hoc
 include_once('config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $idProdotto = $_POST['IdProdotto'];
    $giacenza = mysqli_query($conn,"SELECT Giacenza FROM Prodotto WHERE IdProdotto = $idProdotto"); 
    $giacenza = mysqli_fetch_array($giacenza)['Giacenza'];

    if ($_POST['Quantita'] > $giacenza){
        echo json_encode(array("result"=>false)); // echo "<script> alert('Operazione non effettuata! Si è superata la giacenza per il prodotto selezionato') </script>";
    }else{
        echo json_encode(array("result"=>true));
    }
   
   
?>