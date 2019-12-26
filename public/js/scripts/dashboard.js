var app = angular.module('Dashboard', ['ngmqtt']).run(function(){
    console.log("Angular application started");
});

app.controller('dashboard_controller',[ 
    '$scope',
    '$timeout',
    '$interval', 
    'ngmqtt',
    'dashboard_model',
    function(
        $scope,
        $timeout,
        $interval, 
        ngmqtt,
        dashboard_model
    ){
        $scope.quota_sort = {};
        $scope.error_sort = {};
        $scope.logs_stream = {};

        let quota_list = function( ){
            dashboard_model.quota_list(
                function(response){
                   // $scope.quota_sort.list = [];
                    if(response.status == 200){
                        $scope.quota_sort.list = response.data.quota;
                    
                        //console.log($scope.quota_sort.list);

                        $scope.quota_sort.q0 = ($scope.quota_sort.list[0] == null) ? 0 : $scope.quota_sort.list[0].total;
                        $scope.quota_sort.q1 = ($scope.quota_sort.list[1] == null) ? 0 : $scope.quota_sort.list[1].total;
                        $scope.quota_sort.q2 = ($scope.quota_sort.list[2] == null) ? 0 : $scope.quota_sort.list[2].total;

                        $scope.quota_sort.t0 = ($scope.quota_sort.list[0] == null) ? "" : $scope.quota_sort.list[0].title;
                        $scope.quota_sort.t1 = ($scope.quota_sort.list[1] == null) ? "" : $scope.quota_sort.list[1].title;
                        $scope.quota_sort.t2 = ($scope.quota_sort.list[2] == null) ? "" : $scope.quota_sort.list[2].title;

                        $scope.quota_sort.e0 = response.data.error[0].total;
                    }
                }
            );
        }


        let logout = function( ){
            dashboard_model.logout(
                function(response){ 
                    if(response.status == 200){ 
                        if(response.data.validity){
                            location.reload();   
                        }
                    }
                }
            );            
        }

        $scope.logout_click = function(){
            logout();
        }

        $scope.data_reload = function () {
            quota_list();
            $timeout(function(){
                    $scope.data_reload();
            },10000)
        };

        $scope.quota_sort.q0 = 0;
        $scope.quota_sort.q1 = 0;
        $scope.quota_sort.q2 = 0;

        $scope.quota_sort.t3 = 'Errors';

        $scope.quota_sort.e0 = 0;

        $scope.data_reload();

        
    }
]).factory('dashboard_model',[
    '$http',
    function($http){
        
        var service = {};

        service.quota_list = function(
			callback		
		){
			$http.get(
                'conveyor/api/v1/dashboard/read/quota',
                {  
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.logout = function(
            callback
        ){
            $http.get(
                'conveyor/api/v1/auth/logout',
                {}
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);	
        }
        
        return service;
        
    }
]);
