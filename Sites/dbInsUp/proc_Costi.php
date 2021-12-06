
<?php
//TO DO -- stored ad-hoc
 include_once('../config.php');
 $conn = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
 $conn2 = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
   // $idProdotto = $_POST['IdProdotto'];
  //  $giacenza = mysqli_query($conn,"SELECT Giacenza FROM Prodotto WHERE IdProdotto = $idProdotto"); 
  //  $giacenza = mysqli_fetch_array($giacenza)['Giacenza'];

 //   if ($_POST['Quantita'] > $giacenza){
  //      echo "<script> alert('Operazione non effettuata! Si è superata la giacenza per il prodotto selezionato') </script>";
   // }else{
        if ($_POST['Azione'] == "I")
            $sql = 'CALL Costo_Processo_Insert(?, ?, ?, ?, ?, ?)';
        // else $sql = 'CALL Costo_Processo_Update(?, ?, ?, ?, ?)';
    
        $stmt = $conn2->prepare($sql);
        if ($stmt){
            if ($_POST['Azione'] == "I")
            $stmt->bind_param("ssssss", $_POST['IdMassaVino'], $_POST['Descrizione'], $_POST['Valore'], $_POST['DataCosto'], $_POST['IdProdotto'], $_POST['Quantita']);
            else {
            //  $stmt->bind_param("sssss", $_POST['IdCosto'], $_POST['IdMassaVino'], $_POST['Descrizione'], $_POST['Valore'], $_POST['DataCosto']);
                }
        }else {
                $error =  $conn2->error;
                echo $error;
            }
        $stmt->execute();
        header('Location:/../costi.php?IdMassaVino='. $_POST['IdMassaVino']);
   // }
   
   
?>