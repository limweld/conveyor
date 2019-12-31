var app = angular.module(
        'Dashboard', 
        [
            'ngmqtt',
            'chart.js'
        ]
    ).run(function(){
        console.log("Angular application started");
    }
);

app.config(['ChartJsProvider', function (ChartJsProvider) {
    ChartJsProvider.setOptions({
      chartColors: ['#FF5370', '#4099ff', '#2ed8b6', '#ffcb80'],
      responsive: true
    });
}])

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
                                ngmqtt.subscribe('code_messages');
                                ngmqtt.subscribe($scope.connection[0].topic);
                                ngmqtt.subscribe($scope.connection[1].topic);
                                ngmqtt.subscribe($scope.connection[2].topic);
                                ngmqtt.subscribe($scope.connection[3].topic);
                            }
                        );
                
                        ngmqtt.listenMessage("dashboard_controller", function(topic, message){

                            let mess = new TextDecoder("utf-8").decode(message);

                            let data_list = JSON.parse(mess);

                            if(topic == "quota_0"){ $scope.q_0 = data_list }
                            if(topic == "quota_1"){ $scope.q_1 = data_list }
                            if(topic == "quota_2"){ $scope.q_2 = data_list }
                            if(topic == "quota_error"){ $scope.e_0 = data_list }

                        });
                
                    }
                }
            );
        }

        let quota_list_hourly = function( ){
            dashboard_model.quota_list_hourly(
                function(response){
                    if(response.status == 200){
                        let obj = response.data;
                        $scope.labels = obj.labels;
                        $scope.series = obj.series;
                        $scope.data = obj.data;
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

        $scope.labels = [];
        $scope.series = [];
        $scope.data = [];
        $scope.onClick = function (points, evt) {
          //console.log(points, evt);
        };
        $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }, { yAxisID: 'y-axis-2' }];
        $scope.options = {
          scales: {
            yAxes: [
              {
                id: 'y-axis-1',
                type: 'linear',
                display: true,
                position: 'left'
              },
              {
                id: 'y-axis-2',
                type: 'linear',
                display: false,
                position: 'right'
              }
            ]
          }
        };

        $scope.logout_click = function(){
            logout();
        }

        $interval(function() {
            $scope.today = Date.now();
        }, 1000);

        $interval(function() {
            quota_list();
        }, 3600);

        $interval(function() {
            quota_list_hourly();
        }, 7200);

        $scope.quota_sort.q0 = 0;
        $scope.quota_sort.q1 = 0;
        $scope.quota_sort.q2 = 0;

        $scope.quota_sort.t3 = 'Errors';

        $scope.quota_sort.e0 = 0;

        quota_list();
        quota_list_hourly();
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

        service.quota_list_hourly = function(
			callback		
		){
			$http.get(
                'conveyor/api/v1/dashboard/read/quota/hourly',
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
