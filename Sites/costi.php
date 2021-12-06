<?php 
  // Database info
  include_once('config.php');

  include 'index.php';


// Create connection
$connection = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
$conn2 = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }

  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  
$key= $_GET['IdMassaVino'];
  $sql = mysqli_query($connection,"SELECT COUNT(IdCosto) as id FROM Costo WHERE IdMassaVino = $key" );
  $costi = mysqli_query($connection,"SELECT * FROM Costo WHERE IdMassaVino =$key  LIMIT $paginationStart, $limit",MYSQLI_USE_RESULT);
  
  $prodotti = mysqli_query($conn2,"SELECT IdProdotto, Descrizione FROM Prodotto ",MYSQLI_USE_RESULT); 
  // Get total records
  $allRecrods = mysqli_fetch_array($sql)['id'];
  
  // Calculate total pages
  $totoalPages = ceil($allRecrods / $limit);

  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;
?>

<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    <title>Costi</title>
    <!-- Required meta tags -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="style.css" >
</head>

<body>
    <div class="container mt-5 ">
        <h2 class="text-center mb-5"></h2>
         <!-- Buttons toolbar -->
        <div class="buttons-toolbar">
        <button type="button" onclick="insertClick(<?php echo $key; ?>)" data-toggle="modal" data-target="#edit_ins_Modal"  id="insert"  class="btn btnInsert  btn-outline-dark" >Inserisci</button>
        <button type="button" onclick="processoClick(<?php echo $key; ?>)" data-toggle="modal" data-target="#process_Modal"  id="insert"  class="btn btnInsert  btn-outline-dark" >Inserisci azione</button>
        </div>

       
        <!-- Datatable -->
        <table class="table table-bordered mb-5"
        id="table"
        data-show-export="true"
        data-export-types="excel"
        data-click-to-select="true"
        data-toggle="table"
        data-search="true"
        data-show-refresh="true"
        data-toolbar=".buttons-toolbar"
        >
            <thead>
                <tr class="table-secondary">
                    <th scope="col" class="hiddenCol">#</th>
                    <th scope="col" class="hiddenCol"></th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">Valore</th>
                    <th scope="col">Data</th>
                    <th scope="col" class="hiddenCol"></th>
                    <th scope="col" class="hiddenCol"></th>
                    <th scope="col" class="hiddenCol"></th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($costi as $costo): ?>
                <tr>
                    <th scope="row"><?php echo $costo['IdCosto']; ?></th>
                    <td><?php echo $costo['IdMassaVino']; ?></th>
                    <td><?php echo $costo['Descrizione']; ?></td>
                    <td><?php echo $costo['Valore']; ?></td>
                    <td><?php echo $costo['DataCosto']; ?></td>
                    <td><?php echo $costo['FlgProcesso']; ?></td>
                    <td><?php echo $costo['IdProdotto']; ?></td>
                    <td><?php echo $costo['QtaProdotto']; ?></td>
                    <td>
                    <?php if($costo['FlgProcesso'] == 0){ ?>
                        <button type="button" id="btnEdit" data-toggle="modal" data-target="#edit_ins_Modal"  class="btn btn-light editingTRbutton"><i class="fas fa-edit"></i></button>
                    <?php }else { ?>
                        <button type="button" id="btnEdit_p" data-toggle="modal" data-target="#process_Modal"  class="btn btn-light editingTRbutton_p"><i class="fas fa-edit"></i></button>
                    <?php } ?>   
                        <button type="button" id="btnDelete" onclick="deleteClick('<?php echo $costo['IdMassaVino']; ?>','<?php echo $costo['IdCosto']; ?>')" class="btn btn-light"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
           <!-- Select dropdown -->
        
        <div class="d-flex flex-row bd-highlight mb-1 ">
            <form action="" method="post">
                <select name="records-limit" id="records-limit" class="custom-select">
                    <?php foreach([10,20,50,100] as $limit) : ?>
                    <option
                        <?php if(isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $limit) echo 'selected'; ?>
                        value="<?= $limit; ?>">
                        <?= $limit; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
          
        <!-- Pagination -->
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link"
                        href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>">Previous</a>
                </li>

                <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"> <?= $i; ?> </a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
                    <a class="page-link"
                        href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?page=". $next; } ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="modal fade" id="edit_ins_Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Modifica</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="dbInsUp/costi.php"  method="POST" id="ModalForm">
                    <input class="form-control" type="hidden" id="IdMassaVino" name="IdMassaVino" value="">
                    <input class="form-control" type="hidden" id="IdCosto" name="IdCosto" value="">
                    <input class="form-control" type="hidden" id="Azione" name="Azione" value="">
                        <div class="form-group">
                            <label for="editDescrizione">Descrizione</label>
                            <input type="text" name="Descrizione" class="form-control" id="editDescrizione" placeholder="Descrizione" required>
                        </div>
                        <div class="form-group">
                            <label for="editValore">Valore</label>
                            <input type="number" min="0" pattern="^\$\d{1,3}(.\d{3})*(\,\d+)?$"  data-type="currency" name="Valore" class="form-control" id="editValore" required>
                        </div>
                        <div class="form-group">
                            <label for="editDataCosto">Data</label>
                            <input type="date" id="editDataCosto" class="datepicker" name="DataCosto"  class="form-control"> 
                        </div>
                       
                        <div class="modal-footer">
                            <a  class="btn btn-outline-secondary" data-dismiss="modal">Chiudi</a>
                            <button type="submit"  id="saveModalButton" class="btn btn-outline-success">Salva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="process_Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="dbInsUp/proc_Costi.php"  method="POST" id="ModalFormProcesso">
                    <input class="form-control" type="hidden" id="IdMassaVino_p" name="IdMassaVino" value="">
                    <input class="form-control" type="hidden" id="IdCosto_p" name="IdCosto" value="">
                    <input class="form-control" type="hidden" id="Azione_p" name="Azione" value="">
                        <div class="form-group">
                            <label for="editDescrizione_p">Descrizione</label>
                            <input type="text" name="Descrizione" class="form-control" id="editDescrizione_p" placeholder="Descrizione" required>
                        </div>
                        <div class="form-group">
                            <label for="editValore_p">Valore</label>
                            <input type="number" min="0" pattern="^\$\d{1,3}(.\d{3})*(\,\d+)?$"  data-type="currency" name="Valore" class="form-control" id="editValore_p" required>
                        </div>
                        <div class="form-group">
                            <label for="editData_p">Data</label>
                            <input type="date" id="editData_p" class="datepicker" name="DataCosto" > 
                        </div>
                        <div class="form-group">
                            <label for="editProdotti_p">Prodotti</label>
                            <select class="form-select selectInput" aria-label="Prodotti" id="editProdotti_p" name="IdProdotto">
                            <option selected>Seleziona il prodotto</option>
                                <?php foreach($prodotti as $prodotto): ?>
                                    <option value="<?php echo $prodotto['IdProdotto']; ?>"><?php echo $prodotto['Descrizione']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editQuantita_p">Quantità prodotto</label>
                            <input type="number" min="0"  name="Quantita" class="form-control" id="editQuantita_p"  required>
                        </div>
                        <div class="modal-footer">
                            <a  class="btn btn-outline-secondary" data-dismiss="modal">Chiudi</a>
                            <button type="submit"  id="saveModalButton_p" class="btn btn-outline-success" >Salva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
    
            $(".topnav a").removeClass("active");
            $('#costi').addClass('active');
            $('#costi').removeClass("hiddenTab");

            $('#edit_ins_Modal').on('hidden.bs.modal', function () {
              // $(this).find('form').trigger('reset');  
              document.getElementById("ModalForm").reset();
           })

           $('#process_Modal').on('hidden.bs.modal', function () {
              // $(this).find('form').trigger('reset');  
              document.getElementById("ModalFormProcesso").reset();
           })
        });
      

$(function() {
        //Take the data from the TR during the event button
        $('table').on('click', 'button.editingTRbutton',function (ele) {
            //the <tr> variable is use to set the parentNode from "ele
            var tr = ele.target.parentNode.parentNode;

            //I get the value from the cells (td) using the parentNode (var tr)
            var idCosto = tr.cells[0].textContent;
            var idMassaVino = tr.cells[1].textContent;
            var descrizione = tr.cells[2].textContent;
            var valore = tr.cells[3].textContent;
            var data = tr.cells[4].textContent;
           

            //Prefill the fields with the gathered information
            $('h5.modal-title').html(' ');
            $('#editDescrizione').val(descrizione);
            $('#editValore').val(valore);
            $('#editDataCosto').val(data);
            $('#IdCosto').val(idCosto);
            $("#IdMassaVino").val(idMassaVino);
            $('#Azione').val("U");
        });

         //Take the data from the TR during the event button
         $('table').on('click', 'button.editingTRbutton_p',function (ele) {
            //the <tr> variable is use to set the parentNode from "ele
            var tr = ele.target.parentNode.parentNode;

            //I get the value from the cells (td) using the parentNode (var tr)
            var idCosto = tr.cells[0].textContent;
            var idMassaVino = tr.cells[1].textContent;
            var descrizione = tr.cells[2].textContent;
            var valore = tr.cells[3].textContent;
            var data = tr.cells[4].textContent;
            var idProdotto = tr.cells[6].textContent;
            var qtaProdotto = tr.cells[7].textContent;

            //Prefill the fields with the gathered information
            $('h5.modal-title').html('');
            $('#editDescrizione_p').val(descrizione);
            $('#editValore_p').val(valore);
            $('#editData_p').val(data);
            $('#IdCosto_p').val(idCosto);
            $("#IdMassaVino_p").val(idMassaVino);
            $('#Azione_p').val("U");
            $('#editProdotti_p').val(idProdotto);
            $('#editQuantita_p').val(qtaProdotto);

            $('#editProdotti_p').prop('disabled', true); 
            $('#editQuantita_p').prop('disabled', true); 
        });
    });


function insertClick(id){
       //Insert event
        $('h5.modal-title').html('Inserimento');
        $('#Azione').val("I");
        $('#IdMassaVino').val(id);

    }   
    function processoClick(id){
       //Insert event
       // $('h5.modal-title').html('Inserimento');
        $('#Azione_p').val("I");
        $('#IdMassaVino_p').val(id);
    }   

   /* function saveProcessoClick(){
        var form = $('#ModalFormProcesso');
        var data = $(form).serializeArray();
      
       var idProdotto = data[6].value;
       var qta = data[7].value;
      
       $.ajax({
            url: "validation_Process.php",
            type: "GET",   
            dataType: "html", 
            success:function(x){
                console.log(x.result);
            }
        });
    
    }
*/
    function deleteClick(idMassa, idCosto) {
      
        window.open("dbDelete/costi.php?IdMassaVino="+ idMassa+"&IdCosto="+ idCosto,"_self")
    }

    </script>
      <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin@1.10.1/tableExport.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
  
  


</body>

</html>