<?php
    session_start();
    if(empty($_SESSION["session_key"])){
        header("Location: /");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en" ng-app="Dashboard">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Conveyor</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">

  <link href="../css/dashboard.css" rel="stylesheet">

  <!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->


</head>

<body id="page-top" ng-controller="dashboard_controller">

  <div class="container">


  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="/sms/main">CONVEYOR</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
     
     
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION["fullname"]." "; ?><i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="main">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="generator">
          <i class="fas fa-fw fa-barcode"></i>
          <span>Generator</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="credential">
          <i class="fas fa-fw fa-key"></i>
          <span>Credential</span></a>
      </li>

    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Home</a>
          </li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>

         <!-- Icon Cards-->
         <div class="row">

          <div class="col-md-4 col-xl-3">
              <div class="card bg-c-blue order-card">
                  <div class="card-block">
                      <h6 class="m-b-20">{{ quota_sort.t0 }}</h6>
                      <h2 class="text-right"><i class="fa fa-barcode f-left"></i><span>{{ quota_sort.q0 }}</span></h2>
                      <p class="m-b-0">Total Sort Orders</p>
                  </div>
              </div>
          </div>
          
          <div class="col-md-4 col-xl-3">
              <div class="card bg-c-green order-card">
                  <div class="card-block">
                      <h6 class="m-b-20">{{ quota_sort.t1 }}</h6>
                      <h2 class="text-right"><i class="fa fa-barcode f-left"></i><span>{{ quota_sort.q1 }}</span></h2>
                      <p class="m-b-0">Total Sort Orders</p>
                  </div>
              </div>
          </div>
          
          <div class="col-md-4 col-xl-3">
              <div class="card bg-c-yellow order-card">
                  <div class="card-block">
                      <h6 class="m-b-20">{{ quota_sort.t2 }}</h6>
                      <h2 class="text-right"><i class="fa fa-barcode f-left"></i><span>{{ quota_sort.q2 }}</span></h2>
                      <p class="m-b-0">Total Sort Orders</p>
                  </div>
              </div>
          </div>
          
          <div class="col-md-4 col-xl-3">
              <div class="card bg-c-pink order-card">
                  <div class="card-block">
                      <h6 class="m-b-20">{{ quota_sort.t3 }}</h6>
                      <h2 class="text-right"><i class="fa fa-barcode f-left"></i><span>{{ quota_sort.e0 }}</span></h2>
                      <p class="m-b-0">Total Sort Orders</p>
                  </div>
              </div>
          </div>

        </div>

         <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-12 col-sm-12 mb-12" >
            <div class="card text-white o-hidden" >
              <div class="panel panel-default">
                <div class="panel-heading">Line Chart</div>
                <div class="panel-body">
                  <canvas id="line" class="chart chart-line" chart-data="data" chart-labels="labels" chart-click="onClick" chart-hover="onHover" chart-series="series" chart-options="options" chart-dataset-override="datasetOverride">
                  </canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-3 mb-3">
            <div class="card text-white o-hidden">
              <div class="card-header bg-primary">
                <h6>Luz-Sorter Logs</h6>
              </div>
              <div class="card-body bg-dark" style="height : 250px; color: #45A945;">
                <small class="card-text" ng-repeat="x in q_0">$ BC<i class="fas fa-arrow-right"></i>{{ x.value }}</br></small>
              </div>
            </div>
          </div>



          <div class="col-xl-3 col-sm-3 mb-3">
              <div class="card text-white o-hidden ">
                <div class="card-header bg-primary">
                  <h6>Viz-Sorter Logs</h6>
                </div>
                <div class="card-body bg-dark" style="height : 250px; color: #45A945;">
                  <small class="card-text" ng-repeat="x in q_1">$ BC<i class="fas fa-arrow-right"></i>{{ x.value }}</br></small>
                </div>
              </div>
            </div>

            
            <div class="col-xl-3 col-sm-3 mb-3">
                <div class="card text-white o-hidden ">
                  <div class="card-header bg-primary">
                    <h6>Min-Sorter Logs</h6>
                  </div>
                  <div class="card-body bg-dark" style="height : 250px; color: #45A945;">
                    <small class="card-text" ng-repeat="x in q_2">$ BC<i class="fas fa-arrow-right"></i>{{ x.value }}</br></small>
                  </div>
                </div>
              </div>

              

              <div class="col-xl-3 col-sm-3 mb-3">
                  <div class="card text-white o-hidden ">
                    <div class="card-header bg-primary">
                      <h6>Error Logs</h6>
                    </div>
                    <div class="card-body bg-dark" style="height : 250px; color: #45A945;">
                      <small class="card-text" ng-repeat="x in e_0">$ BC<i class="fas fa-arrow-right"></i>{{ x.value }}</br></small>
                    </div>
                  </div>
                </div>

        </div>    

      </div>
      <!-- /.container-fluid -->

      <div class="text-center bg-light">
        <div class="copyright">
          <small><span>Copyright © Conveyor Sorter ProjecDesign 2020</span></small>
        </div>
      </div>


    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="" ng-click="logout_click()">Logout</a>
        </div>
      </div>
    </div>
  </div>


  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script> -->

  <!-- Page level plugin JavaScript-->
  <!-- <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.js"></script> -->

  <!-- Custom scripts for all pages-->
  <!-- <script src="js/sb-admin.min.js"></script> -->

  <!-- Demo scripts for this page-->
  <!-- <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script> -->

  <script src="js/angular.min.js"></script>
  <script src="js/angular-route.min.js"></script>
  <script src="js/angular-cookies.min.js"></script>
  <script src="js/angular-animate.min.js"></script>
  <script src="js/angular-sanitize.min.js"></script>
  <script src="js/angular-momentjs.min.js"></script>

  <script type="text/javascript" src="resources/node_modules/chart.js/dist/Chart.min.js"></script>
  <script type="text/javascript" src="resources/node_modules/angular-chart.js/angular-chart.js"></script>

  <script type="text/javascript" src="resources/node_modules/ngmqtt/browserMqtt.js"></script>
  <script type="text/javascript" src="resources/node_modules/ngmqtt/ngmqtt.js"></script>

  <script src="js/scripts/dashboard.js"></script>

</body>

</html>

