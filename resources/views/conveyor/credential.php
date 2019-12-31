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
        <a class="nav-link dropdown-toggle no-arrow" href="#" id="userDropdown"  aria-expanded="false">
            {{ today | date : "MMM d, y" }} {{ today | date : "h:mm:ss a" }}
        </a>     
      </li>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <b><?php echo $_SESSION["fullname"]." "; ?><i class="fas fa-user-circle fa-fw"></i></b>
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
   
      <li class="nav-item">
        <a class="nav-link" href="generator">
          <i class="fas fa-fw fa-barcode"></i>
          <span>Generator</span></a>
      </li>

      <li class="nav-item active">
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
          <li class="breadcrumb-item active">Credentials</li>
        </ol>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user_tab" role="tab" aria-selected="true" ng-click="user.list_tab()">User</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="connection-tab" data-toggle="tab" href="#connection_tab" role="tab" aria-selected="false" ng-click="connection.list_tab()">Connection</a>
          </li>
        </ul>
        <div class="tab-content">
          
   
          <!-- user Tab Start -->
          <div class="tab-pane fade show active" id="user_tab" role="tabpanel" aria-labelledby="user-tab">
            <br>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                          <label>
                            <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id"  ng-model="user.selected" ng-change="user.selected_change()">
                            </select> Rows
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                          
                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-secondary btn-sm" data-toggle="modal" ng-click="user.obj.create_entry_click()"> <i class="fas fa-fw fa-plus"></i></a>
                            </li>

                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-primary btn-sm" ng-click="user.list_search()"> <i class="fas fa-fw fa-search"></i></a>
                            </li>
                          
                            <li class="paginate_button page-item active">
                                <input type="search" class="form-control form-control-sm" placeholder="" ng-model="user.search">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-sm-12">

                        <div class="progress" ng-show="user.loading">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                        </div>

                        <table class="table table-bordered table-hover table-sm" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Username</th>
                              <th>Created Date</th>
                              <th>Fullname</th>
                             
                              <th class="text-center"><i class="fas fa-fw fa-info-circle"></i></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="x in user.list">
                              <td>{{ x.username }}</td>
                              <td>{{ x.created_at }}</td>
                              <td>{{ x.lastname }}, {{ x.firstname }}</td>
                              <td class="text-center" ng-click="user.obj.modify_entry_click( x )"><a href=""><i class="fas fa-fw fa-edit"></i></a></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                          Showing {{ user.showfrom }} to {{ user.showto }} of {{ user.totalrows }} entries
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                            <li class="page-item" ng-show="user.pagination.state.first" ng-click="user.pagination.first_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="user.pagination.state.previous" ng-click="user.pagination.previous_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="user.pagination.state.previousPages" ng-click="user.pagination.previouspages_click()">
                                <a href="" class="page-link">..</a>
                            </li>
                            <li class="page-item {{ x.active }}" ng-repeat="x in user.pagination.state.pages" ng-click="user.pagination.pages_click(x)">
                                <a href="" class="page-link">{{ x.page }}</a>
                            </li>
                            <li class="page-item">
                                <a href="" class="page-link" ng-show="user.pagination.state.nextPages" ng-click="user.pagination.nextpages_click()">..</a>
                            </li>
                            <li class="page-item next" ng-show="user.pagination.state.next" ng-click="user.pagination.next_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" ng-show="user.pagination.state.last" ng-click="user.pagination.last_click()">
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
          <!-- user Tab End -->

          <!-- connection Tab Start -->
          <div class="tab-pane fade " id="connection_tab" role="tabpanel" aria-labelledby="connection-tab">
          <br>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                          <label>
                            <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" ng-options="item.value for item in ranges track by item.id"  ng-model="connection.selected" ng-change="connection.selected_change()">
                            </select> Rows
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">

                            <li class="paginate_button page-item previous " id="dataTable_previous">
                                <a href="#" class=" btn btn-secondary btn-sm" data-toggle="modal" ng-click="connection.obj.create_entry_click()"> <i class="fas fa-fw fa-plus"></i></a>
                            </li>

                            <li class="paginate_button page-item previous " id="dataTable_previous">
                              <a href="#" class=" btn btn-primary btn-sm" ng-click="connection.list_search()"> <i class="fas fa-fw fa-search"></i></a>
                            </li>
                          
                            <li class="paginate_button page-item active">
                                <input type="search" class="form-control form-control-sm" placeholder="" ng-model="connection.search">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-sm-12">

                        <div class="progress" ng-show="connection.loading">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                        </div>

                        <table class="table table-bordered table-hover table-sm" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Topic</th>
                              <th>Description</th>
                              <th>Created Date</th>
                              <th class="text-center"><i class="fas fa-fw fa-info-circle"></i></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="x in connection.list">
                              <td>{{ x.topic }}</td>
                              <td>{{ x.description }}</td>
                              <td>{{ x.created_at }}</td>
                              <td class="text-center" ng-click="connection.obj.modify_entry_click( x )"><a href=""><i class="fas fa-fw fa-edit"></i></a></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                          Showing {{ connection.showfrom }} to {{ connection.showto }} of {{ connection.totalrows }} entries
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" >
                          <ul class="pagination pagination-sm">
                            <li class="page-item" ng-show="connection.pagination.state.first" ng-click="connection.pagination.first_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="connection.pagination.state.previous" ng-click="connection.pagination.previous_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-left"></i></a>
                            </li>
                            <li class="page-item" ng-show="connection.pagination.state.previousPages" ng-click="connection.pagination.previouspages_click()">
                                <a href="" class="page-link">..</a>
                            </li>
                            <li class="page-item {{ x.active }}" ng-repeat="x in connection.pagination.state.pages" ng-click="connection.pagination.pages_click(x)">
                                <a href="" class="page-link">{{ x.page }}</a>
                            </li>
                            <li class="page-item">
                                <a href="" class="page-link" ng-show="connection.pagination.state.nextPages" ng-click="connection.pagination.nextpages_click()">..</a>
                            </li>
                            <li class="page-item next" ng-show="connection.pagination.state.next" ng-click="connection.pagination.next_click()">
                                <a href="" class="page-link"><i class="fas fa-fw fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" ng-show="connection.pagination.state.last" ng-click="connection.pagination.last_click()">
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
          <!-- connection Tab End -->

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

    <!--Add user Modal -->
    <div class="modal fade" id="addUserModal" role="dialog" >
      <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ user.obj.create_title_show }} User</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <form>

              <div class="form-group row" ng-show="user.obj.loading_visibility">
                <div class="col-sm-12">
                  <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                  </div>
                </div>
              </div>

              <div class="form-group row" ng-show="user.obj.id_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Entry Id</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_id" ng-model="user.obj.id" readonly>
                </div>
              </div>

              <div class="form-group row" ng-show="user.obj.created_at_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Created at</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_created_at" ng-model="user.obj.created_at" readonly>
                </div>
              </div>

              <div class="form-group row" ng-show="user.obj.update_at_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Updated at</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_updated_at" ng-model="user.obj.updated_at" readonly>
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Username</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_username" ng-model="user.obj.username" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control form-control-sm" id="user_obj_password" ng-model="user.obj.password" placeholder="{{ user.obj.password_placeholder }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Firstname</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_firstname" ng-model="user.obj.firstname" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Lastname</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="user_obj_lastname" ng-model="user.obj.lastname" >
                </div>
              </div>

              <div class="form-group row" ng-show="user.obj.error_visibility">
                <div class="col-sm-12" style="text-align: center">
                  <label for="inputDate" class="alert alert-danger col-sm-12 col-form-label col-form-label-sm">{{ user.obj.error }}</label>
                </div>
              </div>

            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="user.obj.delete_disabled" ng-show="user.obj.delete_show" ng-click="user.obj.deleted_click()"><i class="fas fa-fw fa-trash"></i> Delete</button>
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="user.obj.update_disabled" ng-show="user.obj.update_show" ng-click="user.obj.updated_click()"><i class="fas fa-fw fa-edit"></i> Update</button>
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="user.obj.create_disabled" ng-show="user.obj.create_show" ng-click="user.obj.created_click()"><i class="fas fa-fw fa-plus-circle"></i> Add</button>
          </div>
        </div>
      
      </div>
    </div>
    <!--Add End Modal -->


   <!--Add connection Modal -->
   <div class="modal fade" id="addConnectionModal" role="dialog" >
      <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ connection.obj.create_title_show }} Connection</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <form>
              <div class="form-group row" ng-show="connection.obj.loading_visibility">
                <div class="col-sm-12">
                  <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                  </div>
                </div>
              </div>

              <div class="form-group row" ng-show="connection.obj.id_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Entry Id</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_id" ng-model="connection.obj.id" readonly>
                </div>
              </div>

              <div class="form-group row" ng-show="connection.obj.created_at_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Created at</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_created_at" ng-model="connection.obj.created_at" readonly>
                </div>
              </div>

              <div class="form-group row" ng-show="connection.obj.update_at_visibility">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Updated at</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_updated_at" ng-model="connection.obj.updated_at" readonly>
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Topic</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_topic" ng-model="connection.obj.topic" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Ip Address</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_ip_address" ng-model="connection.obj.ip_address" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Port</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_port" ng-model="connection.obj.port" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Username</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_username" ng-model="connection.obj.username" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control form-control-sm" id="connection_obj_password" ng-model="connection.obj.password" placeholder="{{ connection.obj.password_placeholder }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Protocol Type</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_protocol_type" ng-model="connection.obj.protocol_type" >
                </div>
              </div>

              <div class="form-group row">
                <label for="inputDate" class="col-sm-3 col-form-label col-form-label-sm">Description</label>
                <div class="col-sm-9">
                  <input type="input" class="form-control form-control-sm" id="connection_obj_description" ng-model="connection.obj.description" >
                </div>
              </div>

              <div class="form-group row" ng-show="connection.obj.error_visibility">
                <div class="col-sm-12" style="text-align: center">
                  <label for="inputDate" class="alert alert-danger col-sm-12 col-form-label col-form-label-sm">{{ connection.obj.error }}</label>
                </div>
              </div>


            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="connection.obj.delete_disabled" ng-show="connection.obj.delete_show" ng-click="connection.obj.delete_click()"><i class="fas fa-fw fa-trash"></i> Delete</button>
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="connection.obj.update_disabled" ng-show="connection.obj.update_show" ng-click="connection.obj.update_click()"><i class="fas fa-fw fa-edit"></i> Update</button>
            <button type="button" class="btn btn-sm btn-primary" ng-disabled="connection.obj.create_disabled" ng-show="connection.obj.create_show" ng-click="connection.obj.created_click()"><i class="fas fa-fw fa-plus-circle"></i> Add</button>
          </div>
        </div>
      
      </div>
    </div>
    <!--Add connection Modal -->



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

  <script src="js/scripts/credential.js"></script>

</body>

</html>

