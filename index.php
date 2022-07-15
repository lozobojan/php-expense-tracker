<?php 

    session_start();
    include './backend/functions.php';
    include './backend/connect.php';
    include './config.php';
    authorize();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ExpenseTracker | Index</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php $activePage = "dashboard"; ?>
  <?php include "./partials/navbar.php"; ?>
  <?php include "./partials/aside.php"; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-12">
                <a href="./expenses/create.php" class="btn btn-primary float-right">Dodaj novi trošak</a>
            </div>
        </div>

        <form action="index.php" method="GET" id="filterForm">
          <div class="row mb-3">
            <div class="col-2">
              <select class="form-control" name="length" id="lengthSelector">
                <option value="-1">- prikaži sve -</option>
                <option value="5" <?= isset($_GET['length']) && $_GET['length'] == 5 ? 'selected' : '' ?> >5</option>
                <option value="10" <?= isset($_GET['length']) && $_GET['length'] == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= isset($_GET['length']) && $_GET['length'] == 20 ? 'selected' : '' ?>>20</option>
              </select> 
            </div>
            
            <div class="col-2">
              <input type="date" name="date_from" class="form-control" placeholder="Datum od" value="<?= isset($_GET['date_from']) ? $_GET['date_from'] : '' ?>" >
            </div>

            <div class="col-2">
              <input type="date" name="date_to" class="form-control" placeholder="Datum do" value="<?= isset($_GET['date_to']) ? $_GET['date_to'] : '' ?>">
            </div>

            <div class="col-2">
              <select class="form-control" name="type_id" id="typeFilter">
                <option value="-1">- prikaži sve -</option>
                <?php 
                  $res = mysqli_query($db_conn, generateSelectQuery('types'));
                  while($row = mysqli_fetch_assoc($res)){
                    $idTemp = $row['id'];
                    $nameTemp = $row['name'];
                    $selected = "";
                    if(isset($_GET['type_id']) && $_GET['type_id'] == $idTemp) $selected = "selected";
                    echo "<option value=\"$idTemp\" $selected>$nameTemp</option>";
                  }
                ?>
              </select> 
            </div>

            <div class="col-3">
              <div class="row">
                <div class="col-6"> 
                    <input type="number" name="amount_from" id="amountFromFilter" class="form-control" placeholder="Iznos od" value="<?= isset($_GET['amount_from']) ? $_GET['amount_from'] : '' ?>">
                </div>

                <div class="col-6"> 
                    <input type="number" name="amount_to" id="amountToFilter" class="form-control" placeholder="Iznos do" value="<?= isset($_GET['amount_to']) ? $_GET['amount_to'] : '' ?>">
                </div>
              </div>
            </div>

            <div class="col-1">
              <div class="row">
                <div class="col-6">
                  <button class="btn btn-success btn-block"> <i class="fas fa-search"></i> </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="clearForm()" class="btn btn-danger btn-block"> <i class="fas fa-times"></i> </button>
                </div>
              </div>
            </div>
          </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tip</th>
                    <th>Podtip</th>
                    <th>Iznos</th>
                    <th>Datum</th>
                    <th>Opis</th>
                    <th>Fajlovi</th>
                </tr>
            </thead>

            <tbody>
                <?php 

                    $where = " 1=1 ";
                    if(isset($_GET['length'])) $length = " LIMIT ".$_GET['length'];
                    else $length = "";

                    if(isset($_GET['type_id']) && $_GET['type_id'] != "-1") $where .= " AND expenses.type_id = ".$_GET['type_id'];
                    if(isset($_GET['date_from']) && $_GET['date_from'] != "") $where .= " AND date >= '".$_GET['date_from']."'";
                    if(isset($_GET['date_to']) && $_GET['date_to'] != "") $where .= " AND date <= '".$_GET['date_to']."'";
                    if(isset($_GET['amount_from']) && $_GET['amount_from'] != "") $where .= " AND amount >= ".$_GET['amount_from'];
                    if(isset($_GET['amount_to']) && $_GET['amount_to'] != "") $where .= " AND amount <= ".$_GET['amount_to'];


                    $sql = "SELECT expenses.*,
                                DATE_FORMAT(date, '%d.%m.%Y %H:%i') as date_formatted, 
                                types.name as type_name,
                                subtypes.name as subtype_name,
                                (select count(*) from attachments where expense_id = expenses.id) as attachments
                            FROM expenses
                            JOIN types ON types.id = expenses.type_id 
                            JOIN subtypes ON subtypes.id = expenses.subtype_id
                            WHERE $where
                            $length
                    ";
                    $res = mysqli_query($db_conn, $sql);

                    $queryData = [];
                    while($row = mysqli_fetch_assoc($res)){
                        $queryData[] = $row;

                        $disabled = "";
                        if($row['attachments'] == 0) $disabled = "disabled";

                        $idTemp = $row['id'];

                        echo "<tr>";
                        echo "  <td>".$row['type_name']."</td>";
                        echo "  <td>".$row['subtype_name']."</td>";
                        echo "  <td>".number_format($row['amount'], 2)." €</td>";
                        // echo "  <td>".date('d.m.Y H:i:s', strtotime($row['date']) )."</td>";
                        echo "  <td>".$row['date_formatted']."</td>";
                        echo "  <td>".$row['description']."</td>";
                        echo "  <td>
                                  <button data-toggle=\"modal\" data-target=\"#attachmentsModal\" class=\"btn btn-primary btn-sm $disabled\" onclick=\"displayAttachments($idTemp)\">priloženi fajlovi</button>
                                </td>";
                        echo "</tr>";
                    }
                    
                    $_SESSION['exportData'] = $queryData;
                ?>
            </tbody>
        </table>

        <div class="row my-3">
          <div class="col-12">
            <a class="btn btn-success" href="./xlsx_export.php">Izvoz u XLSX</a>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include "./partials/footer.php"; ?>
  
  <div class="modal fade" id="attachmentsModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Priloženi fajlovi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body table-responsive">
        
         <div class="row" id="loadingIcon">
          <div class="col-12 text-center">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
          </div>
         </div>           

          <table class="table table-hover d-none" id="attachmentsTable">
            <thead>
              <tr>
                <th>Sistemsko ime</th>
                <th>Preuzmi</th>
              </tr>
            </thead>
            <tbody id="attachmentsTableBody"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<script>
  async function displayAttachments(expense_id){
    let response = await fetch("<?=$appUrl?>/expenses/get_attachments.php?expense_id="+expense_id);
    let responseJSON = await response.json();

    let tableHTML = '';
    responseJSON.forEach( (att) => {
      let downloadBtn = `<a download href="${att.path}" class="btn btn-sm btn-success" >preuzmi</a>`;
      tableHTML += `<tr> <td>${att.path}</td> <td>${downloadBtn}</td> </tr>`;
    })

    document.getElementById("attachmentsTableBody").innerHTML = tableHTML;
    document.getElementById("attachmentsTable").classList.remove('d-none');
    document.getElementById("loadingIcon").classList.add('d-none');

  }

  function clearForm(){
    window.location.href = "index.php";
    // document.getElementById("filterForm").reset();
  }
</script>

<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="dist/js/pages/dashboard.js"></script> -->

</body>
</html>
