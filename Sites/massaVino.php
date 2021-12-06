<?php 
  // Database
  include_once('config.php');
  include 'index.php';


// Create connection
$connection = new mysqli(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS, PHPGRID_DBNAME);
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }

  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;

  $sql = mysqli_query($connection,"SELECT COUNT(IdMassaVino) as id FROM MassaVino ");
  $masseVino = mysqli_query($connection,"SELECT * FROM MassaVino LIMIT $paginationStart, $limit",MYSQLI_USE_RESULT);

  // Get total records
  $allRecrods = mysqli_fetch_array($sql)['id'];
  
  // Calculate total pages
  $totoalPages = ceil($allRecrods / $limit);

  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    <title>Masse di Vino</title>
    <!-- Required meta tags -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="style.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container mt-5">

        <h2 class="text-center mb-5"></h2>
         <!-- Buttons toolbar -->
        <div class="buttons-toolbar">
        <button type="button" onclick="insertClick()" data-toggle="modal" data-target="#edit_ins_Modal"  id="insert"  class="btn  btn-outline-dark" >Inserisci</button>
    </div>

       
        <!-- Datatable -->
        <table class="table  table-bordered mb-5"
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
                    <th scope="col">Descrizione</th>
                    <th scope="col">Quantità</th>
                    <th scope="col">Giacenza</th>
                    <th scope="col" class="hiddenCol">FlgDerivata</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($masseVino as $massa): ?>
                <tr>
                    <th scope="row"><?php echo $massa['IdMassaVino']; ?></th>
                    <td><?php echo $massa['Descrizione']; ?></td>
                    <td><?php echo $massa['Quantita']; ?></td>
                    <td><?php echo $massa['Giacenza']; ?></td>
                    <td><?php echo $massa['FlgDerivata']; ?></td>
                    <td>
                        <button type="button" id="btnEdit" data-toggle="modal" data-target="#edit_ins_Modal"  class="btn btn-light  editingTRbutton"><i class="fas fa-edit"></i></button>
                        <button type="button" id="btnDelete" onclick="deleteClick('<?php echo $massa['IdMassaVino']; ?>')" class="btn btn-light"><i class="far fa-trash-alt"></i></button>
                        <?php if($massa['FlgDerivata'] == 1){ ?>
                            <button type="button" id="btnList" onclick="subMasseClick('<?php echo $massa['IdMassaVino']; ?>')"  class="btn btn-light"><i class="fa fa-list" aria-hidden="true"></i></button>
                        <?php } ?>
                        <button type="button" id="btnCosti" onclick="showClick('<?php echo $massa['IdMassaVino']; ?>')" class="btn btn-light"><i  class="fa fa-eur" aria-hidden="true"></i></button>
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
                    <h5 class="modal-title" id="ModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="dbInsUp/massa.php" method="post" id="ModalForm">
                    <input class="form-control" type="hidden" id="IdMassaVino" name="IdMassaVino" value="">
                    <input class="form-control" type="hidden" id="Azione" name="Azione" value="">
                        <div class="form-group">
                            <label for="editDescrizione">Descrizione</label>
                            <input type="text" name="Descrizione" class="form-control" id="editDescrizione" placeholder="Descrizione" required>
                        </div>
                        <div class="form-group">
                            <label for="editQuantita">Quantità</label>
                            <input type="number" name="Quantita" class="form-control" id="editQuantita" placeholder="Quantita" required>
                        </div>
                        <div class="form-group">
                            <label for="editGiacenza">Giacenza</label>
                            <input type="number"  name="Giacenza" class="form-control" id="editGiacenza" placeholder="Giacenza" >
                        </div>
                      
                        <div class="form-group">
                            <label for="editFlgDerivata">Composta da masse già esistenti</label>
                            <input type="checkbox"  id="editFlgDerivata" name="FlgDerivata" >
                        </div>
                        
                        <div class="modal-footer">
                            <a  class="btn btn-outline-secondary" data-dismiss="modal">Chiudi</a>
                            <button type="submit"  id="saveModalButton"  class="btn btn-outline-success" >Salva</button>
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
            $('#massaVino').addClass('active');

           $('#edit_ins_Modal').on('hidden.bs.modal', function () {
              // $(this).find('form').trigger('reset');  
              document.getElementById("ModalForm").reset();
           })

        });
      
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
    window.location.href = tabName + ".php";
    //$("#includedContent").load(tabName + ".php");
}

$(function() {
        //Take the data from the TR during the event button
        $('table').on('click', 'button.editingTRbutton',function (ele) {
            //the <tr> variable is use to set the parentNode from "ele
            var tr = ele.target.parentNode.parentNode;

            //I get the value from the cells (td) using the parentNode (var tr)
            var id = tr.cells[0].textContent;
            var descrizione = tr.cells[1].textContent;
            var quantita = tr.cells[2].textContent;
            var giacenza = tr.cells[3].textContent;
            var um = tr.cells[4].textContent;
            var flg =tr.cells[5].textContent;
           
            //Prefill the fields with the gathered information
            $('h5.modal-title').html('Modifica: '+ id);
            $('#editDescrizione').val(descrizione);
            $('#editQuantita').val(quantita);
            $('#editGiacenza').val(giacenza);
            $('#IdMassaVino').val(id);
            $('#Azione').val("U");
           
            if(flg == 1){
                $("#editFlgDerivata").prop("checked", true);
            }else{
                $("#editFlgDerivata").prop("checked", false);
            }
                
        });

    });

    function insertClick(){
       //Insert event
        $('h5.modal-title').html('Inserimento');
        $('#Azione').val("I");

    }   
    function deleteClick(id) {
        window.open("dbDelete/massa.php?IdMassaVino="+ id,"_self")
    }
    function showClick(params) {
        window.location.href = "costi.php?IdMassaVino="+ params;
       // window.open("/costi.php?IdMassaVino="+ params,"_self")
    }

    function subMasseClick(id) {
        window.open("/subMassaVino.php?IdMassaVino="+ id,"_self")
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