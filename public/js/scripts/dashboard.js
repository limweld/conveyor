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

        $scope.q_0 = [];
        $scope.q_1 = [];
        $scope.q_2 = [];
        $scope.e_0 = [];

        $scope.connection = [];

        let options = {
            clientId: "test",
            protocolId: 'MQTT',
            protocolVersion: 4,
            username: "",
            password: ""
        };

        let quota_list = function( ){
            dashboard_model.quota_list(
                function(response){

                    if(response.status == 200){
                        $scope.quota_sort.list = response.data.quota;
                    
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

        let connection_list = function( search, page, range ){
            dashboard_model.connection_list(
                page,
                range,
                search,
                function(response){ 
                    if(response.status == 200){
                        $scope.connection = response.data; 

                        options.username = $scope.connection[4].username;
                        options.password = $scope.connection[4].password;
                        
                        ngmqtt.connect('ws://' + $scope.connection[4].ip_address +':'+ $scope.connection[4].port , options);				
                        
                        ngmqtt.listenConnection(
                            "dashboard_controller", 
                            function(){
                                console.log("connected");
                                ngmqtt.subscribe('code_messages');
                                ngmqtt.subscribe($scope.connection[0].topic);
                                ngmqtt.subscribe($scope.connection[1].topic);
                                ngmqtt.subscribe($scope.connection[2].topic);
                                ngmqtt.subscribe($scope.connection[3].topic);
                            }
                        );
                
                        ngmqtt.listenMessage("dashboard_controller", function(topic, message){
                            let mess = JSON.parse(message);

                            if(topic == "quota_0"){ $scope.q_0 = dashboard_model.que_cut( $scope.q_0, { "topic": topic, "value" : mess } , 10 ); }
                            if(topic == "quota_1"){ $scope.q_1 = dashboard_model.que_cut( $scope.q_1, { "topic": topic, "value" : mess } , 10 ); }
                            if(topic == "quota_2"){ $scope.q_2 = dashboard_model.que_cut( $scope.q_2, { "topic": topic, "value" : mess } , 10 ); }
                            if(topic == "quota_error"){ $scope.e_0 = dashboard_model.que_cut( $scope.e_0, { "topic": topic, "value" : mess } , 10 ); }

                        });
                
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

        $interval(function() {
            quota_list();
        }, 300);

        $scope.quota_sort.q0 = 0;
        $scope.quota_sort.q1 = 0;
        $scope.quota_sort.q2 = 0;

        $scope.quota_sort.t3 = 'Errors';

        $scope.quota_sort.e0 = 0;

        connection_list('qu',1,5);
    }
]).factory('dashboard_model',[
    '$http',
    function($http){
        
        var service = {};

        service.que_cut = function( list, value, range ){
            
            list.push( value );

            if( list.length == range ){
                list.shift()
            }
            
            return list;
        }

        service.connection_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/connection',
                { 
                    page : page,
                    range : range,
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

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
