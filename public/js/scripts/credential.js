var app = angular.module('Generator', []);
app.controller('generator_controller',[ 
    '$scope',
    '$window',
    'credential_model',
    function(
        $scope,
        $window,
        credential_model
    ){
        
        $scope.ranges = [
            { id : -1, value : 2 },
            { id : 0, value : 3 },
            { id : 1, value : 10 },
            { id : 2, value : 20 },
            { id : 3, value : 50 },
            { id : 4, value : 100 },
            { id : 5, value : 200 },
        ];

        $scope.user = {};
        $scope.connection = {};
        
        $scope.user.pagination = {};
        $scope.user.pagination.state = {};
        
        $scope.connection.pagination = {};
        $scope.connection.pagination.state= {};

        $scope.user.obj = {};
        $scope.user.pagination = {};
        $scope.user.pagination.state= {};

        $scope.connection.obj = {};
        $scope.connection.pagination = {};
        $scope.connection.pagination.state = {};

        let user_list = function( search, page, range ){
            credential_model.user_list(
                page,
                range,
                search,
                function(response){
                    $scope.user.list = [];
                    if(response.status == 200){
                        $scope.user.list = response.data;                        
                        $scope.user.showfrom = $scope.user.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.user.showto = ((page - 1) * range) + $scope.user.list.length;
                        $scope.user.search = $scope.user.search_temp;
                        $scope.user.loading = false;
                    }
                }
            );
        }

        let connection_list = function( search, page, range ){
            credential_model.connection_list(
                page,
                range,
                search,
                function(response){
                    $scope.connection.list = [];
                    if(response.status == 200){
                        $scope.connection.list = response.data;                        
                        $scope.connection.showfrom = $scope.connection.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.connection.showto = ((page - 1) * range) + $scope.connection.list.length;
                        $scope.connection.search = $scope.connection.search_temp;
                        $scope.connection.loading = false;
                    }
                }
            );
        }

        let user_count = function( search ){
            $scope.user.loading = true;
            credential_model.user_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.user.totalrows = response.data.totalrows;
                        $scope.user.pagination.state = credential_model.pagination_state($scope.user.page, $scope.user.totalrows ,$scope.user.selected.value);
                        user_list( $scope.user.search_temp, $scope.user.page, $scope.user.selected.value);
                    }
                }
            );
        }

        let connection_count = function( search ){
            $scope.connection.loading = true;
            credential_model.connection_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.connection.totalrows = response.data.totalrows;
                        $scope.connection.pagination.state = credential_model.pagination_state($scope.connection.page, $scope.connection.totalrows ,$scope.connection.selected.value);
                        connection_list( $scope.connection.search_temp, $scope.connection.page, $scope.connection.selected.value);
                    }
                }
            );
        }

        let user_created = function( username, password, firstname, lastname ){
            $scope.user.loading = true;
            $scope.user.obj.error_visibility = false;
            credential_model.user_created(
                username, 
                password, 
                firstname, 
                lastname,
                function(response){ 
                    if(response.status == 200){
                        
                        $scope.user.obj.loading_visibility = false;
                            
                        if(response.data.validity){
                            $("#addUserModal").modal('toggle'); 
                            $scope.user.obj.error_visibility = false;
                            user_count( $scope.user.search_temp);
                        }

                        if(!response.data.validity){
                            $scope.user.obj.error = response.data.message;
                            $scope.user.loading = false;
                            $scope.user.obj.loading = false;
                            $scope.user.obj.create_disabled = false;
                            $scope.user.obj.error_visibility = true;
                        }
                        
                    }
                }
            );
        }

        let user_updated = function( id, username, password, firstname, lastname ){
            $scope.user.loading = true;
            credential_model.user_updated(
                id,
                username, 
                password, 
                firstname, 
                lastname,
                function(response){ 
                    if(response.status == 200){
                        $scope.user.obj.loading_visibility = false;
                            
                        if(response.data.validity){
                            $("#addUserModal").modal('toggle'); 
                            $scope.user.obj.error_visibility = false;
                            user_count( $scope.user.search_temp);
                        }

                        if(!response.data.validity){
                            $scope.user.obj.error = response.data.message;
                            $scope.user.loading = false;
                            $scope.user.obj.loading = false;
                            $scope.user.obj.create_disabled = false;
                            $scope.user.obj.error_visibility = true;
                            $scope.user.obj.update_disabled = false;
                            $scope.user.obj.delete_disabled = false;
                        }
                    }
                }
            );
        }

        let user_deleted= function( id ){
            $scope.user.loading = true;
            credential_model.user_deleted(
                id,
                function(response){ 
                    if(response.status == 200){
                        $scope.user.obj.loading_visibility = false;

                        if(response.data.validity){
                            $("#addUserModal").modal('toggle'); 
                            $scope.user.obj.error_visibility = false;
                            user_count( $scope.user.search_temp);
                        }

                        if(!response.data.validity){
                            $scope.user.obj.error = response.data.message;
                            $scope.user.loading = false;
                            $scope.user.obj.loading = false;
                            $scope.user.obj.create_disabled = false;
                            $scope.user.obj.error_visibility = true;
                            $scope.user.obj.update_disabled = false;
                            $scope.user.obj.delete_disabled = false;
                        }
                    }
                }
            );
        }

        let logout = function( ){
            credential_model.logout(
                function(response){ 
                    if(response.status == 200){ console.log(response.data);
                        if(response.data.validity){
                            location.reload();   
                        }
                    }
                }
            );            
        }

        $scope.user.list_search = function(){  
            $scope.user.search_temp = $scope.user.search;
            user_count( $scope.user.search_temp);
        }

        $scope.user.list_tab = function(){
            $scope.user.page = 1;
            user_count($scope.user.search_temp);
        }

        $scope.connection.list_search = function(){  
            $scope.connection.search_temp = $scope.connection.search;
            connection_count( $scope.connection.search_temp);
        }

        $scope.connection.list_tab = function(){
            $scope.connection.page = 1;
            connection_count($scope.connection.search_temp);
        }

        $scope.user.selected_change = function( ){
            $scope.user.page = 1;
            user_count( $scope.user.search_temp);
        }

        $scope.connection.selected_change = function( ){
            $scope.connection.page = 1;
            connection_count( $scope.connection.search_temp);
        }

        
        $scope.user.pagination.first_click =  function(){
            if($scope.user.pagination.state.currentPage > 1){
                $scope.user.page = 1;
                user_count( $scope.user.search_temp);
            }	
        }
        
        $scope.user.pagination.previous_click =  function(){
            if($scope.user.pagination.state.currentPage > 1){
                $scope.user.page = $scope.user.pagination.state.currentPage - 1;
                user_count( $scope.user.search_temp);
            }
        }
        
        $scope.user.pagination.previouspages_click =  function(){
            if($scope.user.pagination.state.currentPage > 1){
                $scope.user.page = $scope.user.pagination.state.currentPageFrom - 1;
                user_count( $scope.user.search_temp);
            }
        }

        $scope.user.pagination.pages_click = function(value){
            $scope.user.page = value.page;
            user_count( $scope.user.search_temp);
        }

        $scope.user.pagination.nextpages_click =  function(){
            	
            if($scope.user.pagination.state.currentPage < $scope.user.pagination.state.totalPages){
                
                $scope.user.page = $scope.user.pagination.state.currentPageTo + 1;
                user_count( $scope.user.search_temp);
            }
        }
        
        $scope.user.pagination.next_click = function(){		
            if($scope.user.pagination.state.currentPage < $scope.user.pagination.state.totalPages){
                $scope.user.page = $scope.user.pagination.state.currentPage + 1,
                user_count( $scope.user.search_temp);
            }
        }
        
        $scope.user.pagination.last_click = function(){
            if( $scope.user.pagination.state.currentPage < $scope.user.pagination.state.totalPages){
                $scope.user.page = $scope.user.pagination.state.totalPages;
                user_count( $scope.user.search_temp);
            }
        }

        $scope.connection.pagination.first_click =  function(){
            if($scope.connection.pagination.state.currentPage > 1){
                $scope.connection.page = 1;
                connection_count( $scope.connection.search_temp);
            }	
        }
        
        $scope.connection.pagination.previous_click =  function(){
            if($scope.connection.pagination.state.currentPage > 1){
                $scope.connection.page = $scope.connection.pagination.state.currentPage - 1;
                connection_count( $scope.connection.search_temp);
            }
        }
        
        $scope.connection.pagination.previouspages_click =  function(){
            if($scope.connection.pagination.state.currentPage > 1){
                $scope.connection.page = $scope.connection.pagination.state.currentPageFrom - 1;
                connection_count( $scope.connection.search_temp);
            }
        }

        $scope.connection.pagination.pages_click = function(value){
            $scope.connection.page = value.page;
            connection_count( $scope.connection.search_temp);
        }

        $scope.connection.pagination.nextpages_click =  function(){
            	
            if($scope.connection.pagination.state.currentPage < $scope.connection.pagination.state.totalPages){
                
                $scope.connection.page = $scope.connection.pagination.state.currentPageTo + 1;
                connection_count( $scope.connection.search_temp);
            }
        }
        
        $scope.connection.pagination.next_click = function(){		
            if($scope.connection.pagination.state.currentPage < $scope.connection.pagination.state.totalPages){
                $scope.connection.page = $scope.connection.pagination.state.currentPage + 1,
                connection_count( $scope.connection.search_temp);
            }
        }
        
        $scope.connection.pagination.last_click = function(){
            if( $scope.connection.pagination.state.currentPage < $scope.connection.pagination.state.totalPages){
                $scope.connection.page = $scope.connection.pagination.state.totalPages;
                connection_count( $scope.connection.search_temp);
            }
        }

        $scope.logout_click = function(){
            logout();
        }

        $scope.user.obj.create_entry_click = function(){
            $("#addUserModal").modal('toggle'); 
            $scope.user.obj.create_title_show = "Create";
            $scope.user.obj.create_show = true;    
            $scope.user.obj.update_show = false;
            $scope.user.obj.delete_show = false;

            $scope.user.obj.create_disabled = false;
            $scope.user.obj.loading_visibility = false;

            $scope.user.obj.id_visibility = false;
            $scope.user.obj.created_at_visibility = false;
            $scope.user.obj.update_at_visibility = false;
            $scope.user.obj.error_visibility = false;

            $scope.user.obj.username = "";
            $scope.user.obj.password = "";
            $scope.user.obj.password_placeholder = "";
            $scope.user.obj.firstname = "";
            $scope.user.obj.lastname = "";
            
            console.log('$scope.user.obj.create_entry_click');
        }

        $scope.connection.obj.create_entry_click = function(){
            $("#addConnectionModal").modal('toggle');   
            $scope.connection.obj.create_title_show = "Create";
            $scope.connection.obj.create_show = true;    
            $scope.connection.obj.update_show = false;
            $scope.connection.obj.delete_show = false;

            $scope.connection.obj.create_disabled = false;
            $scope.connection.obj.loading_visibility = false;
            
            console.log("$scope.connection.obj.create_entry_click");
        }

        $scope.user.obj.modify_entry_click = function( obj ){
            $("#addUserModal").modal('toggle'); 
            $scope.user.obj.create_title_show = "Modify"; 
            $scope.user.obj.create_show = false; 
            $scope.user.obj.update_show = true;
            $scope.user.obj.delete_show = true;

            $scope.user.obj.update_disabled = false;
            $scope.user.obj.delete_disabled = false;
            $scope.user.obj.loading_visibility = false;

            $scope.user.obj.id_visibility = true;
            $scope.user.obj.created_at_visibility = true;
            $scope.user.obj.update_at_visibility = true;
            $scope.user.obj.error_visibility = false;

            $scope.user.obj.id = obj.id;
            $scope.user.obj.created_at = obj.created_at;
            $scope.user.obj.updated_at = obj.updated_at;
            $scope.user.obj.password_placeholder = "●●●●●●●●●●";
            $scope.user.obj.username = obj.username;
            $scope.user.obj.firstname = obj.firstname;
            $scope.user.obj.lastname = obj.lastname;

        }

        $scope.connection.obj.modify_entry_click = function( obj ){
            $("#addConnectionModal").modal('toggle');
            $scope.connection.obj.create_title_show = "Modify"; 
            $scope.connection.obj.create_show = false; 
            $scope.connection.obj.update_show = true;
            $scope.connection.obj.delete_show = true;

            $scope.connection.obj.update_disabled = false;
            $scope.connection.obj.delete_disabled = false;
            $scope.connection.obj.loading_visibility = false;
                     
            console.log("$scope.connection.obj.modify_entry_click");
        }

        $scope.user.obj.created_click = function(){

            $scope.user.obj.create_disabled = true;
            $scope.user.obj.loading_visibility = true;

            console.log("$scope.user.obj.created_click");
            $scope.user.obj.error_visibility = true;
            $scope.user.obj.error = false;

            user_created( 
                $scope.user.obj.username, 
                $scope.user.obj.password, 
                $scope.user.obj.firstname, 
                $scope.user.obj.lastname 
            );
        }

        $scope.user.obj.updated_click = function(){

            $scope.user.obj.update_disabled = true;
            $scope.user.obj.delete_disabled = true;
            $scope.user.obj.loading_visibility = true;

            $scope.user.obj.error_visibility = true;
            $scope.user.obj.error = false;

            user_updated( 
                $scope.user.obj.id,
                $scope.user.obj.username, 
                $scope.user.obj.password, 
                $scope.user.obj.firstname, 
                $scope.user.obj.lastname 
            );
        }

        $scope.user.obj.deleted_click = function(){

            $scope.user.obj.update_disabled = true;
            $scope.user.obj.delete_disabled = true;
            $scope.user.obj.loading_visibility = true;

            user_deleted( 
                $scope.user.obj.id,
            );

        }

        $scope.connection.obj.created_click = function(){

            $scope.connection.obj.create_disabled = true;
            $scope.connection.obj.loading_visibility = true;

            console.log("$scope.connection.obj.created_click");
        }

        $scope.connection.obj.update_click = function(){

            $scope.connection.obj.update_disabled = true;
            $scope.connection.obj.delete_disabled = true;
            $scope.connection.obj.loading_visibility = true;

            console.log("$scope.connection.obj.update_click");
        }

        $scope.connection.obj.delete_click = function(){

            $scope.connection.obj.update_disabled = true;
            $scope.connection.obj.delete_disabled = true;
            $scope.connection.obj.loading_visibility = true;

            console.log("$scope.connection.obj.delete_click");
        }

        $scope.user.selected = $scope.ranges[0];
        $scope.connection.selected = $scope.ranges[0];

        $scope.user.page = 1;
        $scope.connection.page = 1;

        user_count( $scope.user.search_temp);
        connection_count( $scope.connection.search_temp);


    }
]).factory('credential_model',[
    '$http',
    function($http){
        
        var service = {};

        var service = {};

        service.pagination_state = function(currentPage,totalRows,rangeRows){
		
            var buttonRange = 3;
            var totalPages = parseInt(((totalRows-1)/rangeRows) + 1);
    
            var pagination_obj = {};
    
            var currentPageFrom = (parseInt((currentPage-1)/buttonRange) * buttonRange)+1;
            var currentPageLimit = (parseInt((currentPage-1)/buttonRange)+1) * buttonRange;
            var currentPageTo = currentPageLimit < (totalPages) ? currentPageLimit : (parseInt(totalPages));
    
            pagination_obj["first"] = 1 < totalPages  ? true : false;
            pagination_obj["next"] = 1 < totalPages  ? true : false;
            pagination_obj["nextPages"] = totalPages  ? ( currentPageTo != totalPages ? true : false) : false;
            pagination_obj["pages"] = [];
            pagination_obj["previousPages"] = 1 < totalPages  ? ( currentPageFrom != 1 ? true : false) : false;
            pagination_obj["previous"] = 1 < totalPages  ? true : false;
            pagination_obj["last"] = 1 < totalPages  ? true : false;
            pagination_obj["totalPages"] = totalPages;
            pagination_obj["skipRow"] = (currentPage-1) * rangeRows;
            pagination_obj["currentPage"] = currentPage;
            pagination_obj["rangeRow"] = rangeRows < totalRows  ? true : false;
            pagination_obj["groupRange"] = parseInt((currentPage)/buttonRange) + 1;
            pagination_obj["currentPageFrom"] = currentPageFrom;
            pagination_obj["currentPageTo"] = currentPageTo;
            pagination_obj["rangeRowData"] = rangeRows;
    
            if( 1 < totalPages){
                for (var i = currentPageFrom; i <= currentPageTo; i++) {
                    pagination_obj["pages"].push({"page":i,"active": i == currentPage ? "active" : ""});
                }
            }else{ pagination_obj["pages"] = []; }
    
            return pagination_obj;
        }

        service.user_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/user',
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

        service.user_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/user/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.connection_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/connection/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.user_created = function(
            username, 
            password, 
            firstname, 
            lastname,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/create/user',
                { 
                    username : username, 
                    password : password, 
                    firstname : firstname, 
                    lastname : lastname, 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.user_updated = function(
            id,
            username, 
            password, 
            firstname, 
            lastname,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/update/user',
                { 
                    id : id,
                    username : username, 
                    password : password,  
                    firstname : firstname, 
                    lastname : lastname,
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.user_deleted = function(
            id,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/delete/user',
                { 
                    id : id 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.connection_create = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/connection/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.connection_update = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/connection/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.connection_delete = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/credential/read/connection/count',
                { 
                    search : search 
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
