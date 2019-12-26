<?php
    session_start();
    if(empty($_SESSION["session_key"])){
        header("Location: /");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en" ng-app="Generator">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Conveyor</title>

  <div class="container">

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top" ng-controller="generator_controller">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">CONVEYOR</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="">
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
          <?php echo $_SESSION["fullname"] ?><i class="fas fa-user-circle fa-fw"></i>
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
      <li class="nav-item">
        <a class="nav-link" href="main">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
   
      <li class="nav-item active">
        <a class="nav-link" href="generator">
          <i class="fas fa-fw fa-barcode"></i>
          <span>Generator</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Home</a>
          </li>
          <li class="breadcrumb-item active">Barcode Generator</li>
        </ol>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="unscanned" data-toggle="tab" href="#outbox" role="tab" aria-controls="outbox" aria-selected="true" ng-click="unscanned.list_tab()">Unscanned</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="scanned-tab" data-toggle="tab" href="#outboxlog" role="tab" aria-controls="outboxlog" aria-selected="false" ng-click="scanned.list_tab()">Scanned</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="batch-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox" aria-selected="false" ng-click="batch.list_tab()">Batch</a>
          </li>
        </ul>
        <div class="tab-content">
          
          <!-- Unscanned Tab Start -->
          <div class="tab-pane fade show active" id="outbox" role="tabpanel" aria-labelledby="unscanned">
            <br>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                          <label>
                            <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id"  ng-model="unscanned.selected" ng-change="unscanned.selected_change()">
                            </select> Rows
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination">
                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-primary btn-sm" ng-click="unscanned.list_search()"> <i class="fas fa-fw fa-search"></i></a>
                            </li>
                            <li class="paginate_button page-item active">
                                <input type="search" class="form-control form-control-sm" placeholder="" ng-model="unscanned.search">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">

                        <div class="progress" ng-show="unscanned.loading">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                        </div>

                        <table class="table table-bordered table-hover dataTable table-sm" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                        <thead>
                            <tr>
                              <th>Barcode Id</th>
                              <th>Created Date</th>
                              <th>Code</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="x in unscanned.list">
                              <td>{{ x.barcode_id }}</td>
                              <td>{{ x.created_at }}</td>
                              <td>{{ x.sortercode }}</td>
                              <td>{{ x.status }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                          Showing {{ unscanned.showfrom }} to {{ unscanned.showto }} of {{ unscanned.totalrows }} entries
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm ">
                            <li class="page-item" ng-show="unscanned.pagination.state.first" ng-click="unscanned.pagination.first_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="unscanned.pagination.state.previous" ng-click="unscanned.pagination.previous_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="unscanned.pagination.state.previousPages" ng-click="unscanned.pagination.previouspages_click()">
                              <a href="" class="page-link">..</a>
                            </li>
                            <li class="page-item {{ x.active }}" ng-repeat="x in unscanned.pagination.state.pages" ng-click="unscanned.pagination.pages_click(x)">
                              <a href="" class="page-link">{{ x.page }}</a>
                            </li>
                            <li class="page-item">
                              <a href="" class="page-link" ng-show="unscanned.pagination.state.nextPages" ng-click="unscanned.pagination.nextpages_click()">..</a>
                            </li>
                            <li class="page-item next" ng-show="unscanned.pagination.state.next" ng-click="unscanned.pagination.next_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" ng-show="unscanned.pagination.state.last" ng-click="unscanned.pagination.last_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-right"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>   
            <br> 
          </div>
          <!-- Unscanned Tab End -->
          
          <!-- Scanned Tab Start -->
          <div class="tab-pane fade" id="outboxlog" role="tabpanel" aria-labelledby="scanned-tab">
            <br>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                          <label>
                            <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id"  ng-model="scanned.selected" ng-change="scanned.selected_change()">
                            </select> Rows
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-primary btn-sm" ng-click="scanned.list_search()"> <i class="fas fa-fw fa-search"></i></a>
                            </li>
                            <li class="paginate_button page-item active">
                                <input type="search" class="form-control form-control-sm" placeholder="" ng-model="scanned.search">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">

                        <div class="progress" ng-show="scanned.loading">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                        </div>

                        <table class="table table-bordered table-hover dataTable table-sm" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                        <thead>
                            <tr>
                              <th>Barcode Id</th>
                              <th>Created Date</th>
                              <th>Code</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="x in scanned.list">
                              <td>{{ x.barcode_id }}</td>
                              <td>{{ x.created_at }}</td>
                              <td>{{ x.sortercode }}</td>
                              <td>{{ x.status }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                          Showing {{ scanned.showfrom }} to {{ scanned.showto }} of {{ scanned.totalrows }} entries
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                            <li class="page-item" ng-show="scanned.pagination.state.first" ng-click="scanned.pagination.first_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="scanned.pagination.state.previous" ng-click="scanned.pagination.previous_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="scanned.pagination.state.previousPages" ng-click="scanned.pagination.previouspages_click()">
                              <a href="" class="page-link">..</a>
                            </li>
                            <li class="page-item {{ x.active }}" ng-repeat="x in scanned.pagination.state.pages" ng-click="scanned.pagination.pages_click(x)">
                              <a href="" class="page-link">{{ x.page }}</a>
                            </li>
                            <li class="page-item">
                              <a href="" class="page-link" ng-show="scanned.pagination.state.nextPages" ng-click="scanned.pagination.nextpages_click()">..</a>
                            </li>
                            <li class="page-item next" ng-show="scanned.pagination.state.next" ng-click="scanned.pagination.next_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" ng-show="scanned.pagination.state.last" ng-click="scanned.pagination.last_click()">
                              <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-right"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>   
            <br> 
          </div>
          <!-- Scanned Tab End -->

          <!-- Batch Tab Start -->
          <div class="tab-pane fade" id="inbox" role="tabpanel" aria-labelledby="batch-tab">
            <br>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                          <label>
                            <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id"  ng-model="batch.selected" ng-change="batch.selected_change()">
                            </select> Rows
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                          
                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-secondary btn-sm" data-toggle="modal" ng-click="batch.obj.create_entry_click()"> <i class="fas fa-fw fa-plus"></i></a>
                            </li>

                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-primary btn-sm" ng-click="batch.list_search()"> <i class="fas fa-fw fa-search"></i></a>
                            </li>
                          
                            <li class="paginate_button page-item active">
                                <input type="search" class="form-control form-control-sm" placeholder="" ng-model="batch.search">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-sm-12">

                        <div class="progress" ng-show="batch.loading">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                        </div>

                        <table class="table table-bordered dataTable table-hover table-sm" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Batch Id</th>
                              <th>Created Date</th>
                              <th>Code</th>
                              <th>Description</th>
                              <th class="text-center"><i class="fas fa-fw fa-info-circle"></i></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="x in batch.list">
                              <td><a href="" ng-click="batch.show_barcodes( x.batch_id )">{{ x.batch_id }}</a></td>
                              <td>{{ x.created_at }}</td>
                              <td>{{ x.sortercode }}</td>
                              <td>{{ x.description }}</td>
                              <td class="text-center" ng-click="batch.obj.modify_entry_click( x )"><a href=""><i class="fas fa-fw fa-edit"></i></a></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                          Showing {{ batch.showfrom }} to {{ batch.showto }} of {{ batch.totalrows }} entries
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                            <li class="page-item" ng-show="batch.pagination.state.first" ng-click="batch.pagination.first_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="batch.pagination.state.previous" ng-click="batch.pagination.previous_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="batch.pagination.state.previousPages" ng-click="batch.pagination.previouspages_click()">
                                <a href="" class="page-link">..</a>
                            </li>
                            <li class="page-item {{ x.active }}" ng-repeat="x in batch.pagination.state.pages" ng-click="batch.pagination.pages_click(x)">
                                <a href="" class="page-link">{{ x.page }}</a>
                            </li>
                            <li class="page-item">
                                <a href="" class="page-link" ng-show="batch.pagination.state.nextPages" ng-click="batch.pagination.nextpages_click()">..</a>
                            </li>
                            <li class="page-item next" ng-show="batch.pagination.state.next" ng-click="batch.pagination.next_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" ng-show="batch.pagination.state.last" ng-click="batch.pagination.last_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-right"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
          </div>
          <!-- Batch Tab End -->

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
  <!-- <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a> -->

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

    <!-- Modal -->
    <div class="modal fade" id="addBatchModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ batch.obj.create_title_show }} Barcode Batch</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        
        <form>

          <div class="form-group row" ng-show="batch.obj.loading_visibility">
            <div class="col-sm-12">
              <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
              </div>
            </div>
          </div>

          <div class="form-group row" ng-show="batch.obj.created_at_visibility">
            <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Created at</label>
            <div class="col-sm-9">
              <input type="input" class="form-control form-control-sm" id="inputPassword" ng-model="batch.obj.created_at" readonly>
            </div>
          </div>

          <div class="form-group row" ng-show="batch.obj.update_at_visibility">
            <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Updated at</label>
            <div class="col-sm-9">
              <input type="input" class="form-control form-control-sm" id="inputPassword" ng-model="batch.obj.updated_at" readonly>
            </div>
          </div>

          <div class="form-group row" ng-show="batch.obj.batch_id_visibility">
            <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Batch Id</label>
            <div class="col-sm-9">
              <input type="input" class="form-control form-control-sm" id="inputPassword" ng-model="batch.obj.batch_id" readonly>
            </div>
          </div>

          <div class="form-group row" ng-show="batch.obj.sortercode_visibility">
            <label for="inputDescription" class="col-sm-3 col-form-label col-form-label-sm">Sorted Code</label>
            <div class="col-sm-9">
                <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in sortercode track by item.id" ng-model="batch.obj.sortercode" ng-disabled="batch.obj.sortercode_disabled">
                </select>
            </div>
          </div>

          <div class="form-group row" ng-show="batch.obj.max_range_visibility">
            <label for="inputDescription" class="col-sm-3 col-form-label col-form-label-sm">Range</label>
            <div class="col-sm-9">
                <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id" ng-model="batch.obj.max_range" ng-disabled="batch.obj.max_range_disabled">
                </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputDescription" class="col-sm-3 col-form-label col-form-label-sm">Description</label>
            <div class="col-sm-9">
              <textarea type="textarea" class="form-control form-control-sm" id="inputDescription" placeholder="Descrption" rows="2" ng-model="batch.obj.description"></textarea>
            </div>
          </div>
          
        </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-primary" ng-disabled="batch.obj.delete_disabled" ng-show="batch.obj.delete_show" ng-click="batch.obj.delete_click()"><i class="fas fa-fw fa-trash"></i> Delete</button>
          <button type="button" class="btn btn-sm btn-primary" ng-disabled="batch.obj.update_disabled" ng-show="batch.obj.update_show" ng-click="batch.obj.update_click()"><i class="fas fa-fw fa-edit"></i> Update</button>
          <button type="button" class="btn btn-sm btn-primary" ng-disabled="batch.obj.create_disabled" ng-show="batch.obj.create_show" ng-click="batch.obj.created_click()"><i class="fas fa-fw fa-plus-circle"></i> Add</button>
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
  <!-- <script src="../vendor/datatables/jquery.dataTables.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.js"></script> -->

  <!-- Custom scripts for all pages-->
  <!-- <script src="js/sb-admin.min.js"></script> -->

  <!-- Demo scripts for this page-->
  <!-- <script src="js/demo/datatables-demo.js"></script> -->

  <!-- <script src="js/jquery.min.js"></script> -->


  <script src="js/angular.min.js"></script>
  <script src="js/angular-route.min.js"></script>
  <script src="js/angular-cookies.min.js"></script>
  <script src="js/angular-animate.min.js"></script>
  <script src="js/angular-sanitize.min.js"></script>
  <script src="js/angular-momentjs.min.js"></script>

  <script src="js/scripts/generator.js"></script>

</body>

</html>

