<?php
    session_start();
    if(!empty($_SESSION["session_key"])){
        header("Location: main");
        exit;
    }
?>

<!DOCTYPE HTML>
<html ng-app="myLogin">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="resources/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/node_modules/font-awesome/css/font-awesome.css">
    <title>Login</title>
  </head>
  <body ng-controller="authentication_controller">
    
  <div class="container-fluid bg-dark" style="height:100vh; background-image: url('resources/images/wallpapers/ubuntu.png');">
      
	<div class="container" style="width: 20rem; position: absolute;  top: 50%; left: 50%; margin : 0;  -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);  ">
        
        <div class="shadow-sm p-3 mb-5 bg-white rounded" style="   ">

           <table style="width : 100%;">
           <tbody>
                <tr>
                    <td align="center">
                       <span class="text-secondary fa fa-user fa-5x" style="height : 78px; width : 80px; margin-top : -16px; border-style : solid; margin-right : 5;  border-width: 1px;"> </span>
                    </td>
                    <td>
                        <form>
                        <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Username" ng-model="login.username" ng-change="login.username_change()">
                        </div>
                        <div class="form-group">
                        <input type="password" class="form-control form-control-sm" placeholder="Password" ng-model="login.password" ng-change="login.password_change()" ng-keypress="login.key_enter($event)">
                        </div>
                        <div class="form-group" align="right">
                        </div>
                        </form>  
                
                    </td>
                </tr>
                <tr>
                    <td colspan=2 ng-show="login.loading">
                        <div class="progress" style="height: 5px; margin-top : -5px; margin-bottom : 5px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" ></div></div>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 align="center">
                       <div ng-show="login.error_visibility" class="alert {{ login.error_color }} sm"><small>{{login.error_desc}}</small></div>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 align="right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="login.login_click()" ng-disabled="login.login_btn_disabled">Login</button>
                    </td>
                </tr>
           </tbody>
           </table>
		</div>
	</div>
    </div>
    <script src="resources/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="resources/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="resources/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="resources/node_modules/angular/angular.min.js"></script>
    <script src="resources/node_modules/angular-route/angular-route.min.js"></script>
    <script src="resources/node_modules/angular-cookies/angular-cookies.min.js"></script>
    <script src="resources/node_modules/angular-animate/angular-animate.min.js"></script>
    <script src="resources/node_modules/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="resources/node_modules/angular-aria/angular-aria.min.js"></script>
    <script>

    'use strict';

    var app = angular.module('myLogin',[
    ]);

    app.controller('authentication_controller',[
	'$scope',
    '$location',
	'authentication_model',
	function (
        $scope, 
        $location, 
        authentication_model
    ) {

        $scope.login = {};

        let check_btn = function(){
            if($scope.login.username != null && $scope.login.username != "" && $scope.login.password != null && $scope.login.password != ""){
                $scope.login.login_btn_disabled = false;
            }else{
                $scope.login.login_btn_disabled = true;
            }
        }      

        $scope.login.login_click = function(){
            
            $scope.login.loading = true; 
            $scope.login.login_btn_disabled = true;
            authentication_model.login(
				$scope.login.username, 
                $scope.login.password,
				function(response){              
		            if(response.status == 200){
                     
                        if(response.data.validity){
                            $scope.login.error_color = "alert-success";
                            $scope.login.error_visibility = true;
                            $scope.login.error_desc = response.data.message;
                            location.reload();
                            return 0;
                        }

                        $scope.login.login_btn_disabled = false;
                        $scope.login.error_color = "alert-danger";
                        $scope.login.error_visibility = true;
                        $scope.login.error_desc = response.data.message;
                        $scope.login.loading = false;

                        $scope.login.username =
                        $scope.login.password = "";
                   
                        return 0;
                    }

                    $scope.login.loading = false;
                    $scope.login.error_visibility = 1;
                    $scope.login.error_desc = "Network Error !";
                    $scope.login.login_btn_disabled = false;
			});

        }

        $scope.login.username_change = function(){
            check_btn();
        }

        $scope.login.password_change = function(){
            check_btn();
        }

        $scope.login.key_enter = function(keyEvent){
            if(keyEvent.which === 13){
                $scope.login.login_click();
            }
        }

        $scope.login.login_btn_disabled = true;
        $scope.login.error_visibility = false;
    
      
    }]);

    app.factory('authentication_model',[
    '$http',
    function(
        $http,
    ){
		var service = {};
        service.login = function (
            username,
            password,
            callback
        ){
            $http.post(
                'conveyor/api/v1/auth/login',
                {
                    username : username,
                    password : password,
                } 
            ).then(
               function(response){  callback(response); },
               function(response){  callback(response); }
            );
        }

        return service;
    }]);


    </script>
  </body>
</html>


