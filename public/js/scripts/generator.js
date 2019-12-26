var app = angular.module('Generator', []);
app.controller('generator_controller',[ 
    '$scope',
    '$window',
    'generator_model',
    function(
        $scope,
        $window,
        generator_model
    ){
        
        $scope.sortercode = [
            { id : 1, value : 'LUZ' },
            { id : 2, value : 'VIZ' },
            { id : 3, value : 'MIN' },
        ];

        $scope.ranges = [
    //        { id : 0, value : 3 },
            { id : 1, value : 10 },
            { id : 2, value : 20 },
            { id : 3, value : 50 },
            { id : 4, value : 100 },
            { id : 5, value : 200 },
        ];

        $scope.unscanned = {};
        $scope.scanned = {};
        $scope.batch = {};
        $scope.errors = {};

        $scope.unscanned.pagination = {};
        $scope.unscanned.pagination.state = {};
        
        $scope.scanned.pagination = {};
        $scope.scanned.pagination.state= {};

        $scope.errors.obj = {};
        $scope.errors.pagination = {};
        $scope.errors.pagination.state= {};

        $scope.batch.obj = {};
        $scope.batch.pagination = {};
        $scope.batch.pagination.state = {};

        let batch_list = function( search, page, range ){
            generator_model.batch_list(
                page,
                range,
                search,
                function(response){
                    $scope.batch.list = [];
                    if(response.status == 200){
                        $scope.batch.list = response.data;
                        $scope.batch.showfrom = $scope.batch.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.batch.showto = ((page - 1) * range) + $scope.batch.list.length;
                        $scope.batch.search = $scope.batch.search_temp;
                        $scope.batch.loading = false;
                    }
                }
            );
        }

        let unscanned_list = function( search, page, range ){
            generator_model.unscanned_list(
                page,
                range,
                search,
                function(response){
                    $scope.unscanned.list = [];
                    if(response.status == 200){
                        $scope.unscanned.list = response.data;
                        $scope.unscanned.showfrom = $scope.unscanned.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.unscanned.showto = ((page - 1) * range) + $scope.unscanned.list.length;
                        $scope.unscanned.search = $scope.unscanned.search_temp;
                        $scope.unscanned.loading = false;
                    }
                }
            );            
        }

        let scanned_list = function( search, page, range ){
            generator_model.scanned_list(
                page,
                range,
                search,
                function(response){
                    $scope.scanned.list = [];
                    if(response.status == 200){
                        $scope.scanned.list = response.data;                        
                        $scope.scanned.showfrom = $scope.scanned.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.scanned.showto = ((page - 1) * range) + $scope.scanned.list.length;
                        $scope.scanned.search = $scope.scanned.search_temp;
                        $scope.scanned.loading = false;
                    }
                }
            );
        }

        let errors_list = function( search, page, range ){
            generator_model.errors_list(
                page,
                range,
                search,
                function(response){
                    $scope.errors.list = [];
                    if(response.status == 200){
                        $scope.errors.list = response.data;                        
                        $scope.errors.showfrom = $scope.errors.list.length == 0 ? 0 : ((page - 1) * range) + 1;
                        $scope.errors.showto = ((page - 1) * range) + $scope.errors.list.length;
                        $scope.errors.search = $scope.errors.search_temp;
                        $scope.errors.loading = false;
                    }
                }
            );
        }

        let batch_count = function( search ){
            $scope.batch.loading = true;
            generator_model.batch_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.batch.totalrows = response.data.totalrows; 
                        $scope.batch.pagination.state = generator_model.pagination_state($scope.batch.page, $scope.batch.totalrows ,$scope.batch.selected.value);   
                        batch_list( $scope.batch.search_temp, $scope.batch.page, $scope.batch.selected.value);
                    }
                }
            );
        }

        let unscanned_count = function( search ){
            $scope.unscanned.loading = true;
            generator_model.unscanned_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.unscanned.totalrows = response.data.totalrows;    
                        $scope.unscanned.pagination.state = generator_model.pagination_state($scope.unscanned.page, $scope.unscanned.totalrows ,$scope.unscanned.selected.value);
                        unscanned_list( $scope.unscanned.search_temp, $scope.unscanned.page, $scope.unscanned.selected.value);
                    }
                }
            );            
        }

        let scanned_count = function( search ){
            $scope.scanned.loading = true;
            generator_model.scanned_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.scanned.totalrows = response.data.totalrows;
                        $scope.scanned.pagination.state = generator_model.pagination_state($scope.scanned.page, $scope.scanned.totalrows ,$scope.scanned.selected.value);
                        scanned_list( $scope.scanned.search_temp, $scope.scanned.page, $scope.scanned.selected.value);
                    }
                }
            );
        }

        let errors_count = function( search ){
            $scope.errors.loading = true;
            generator_model.errors_count(
                search,
                function(response){
                    if(response.status == 200){
                        $scope.errors.totalrows = response.data.totalrows;
                        $scope.errors.pagination.state = generator_model.pagination_state($scope.errors.page, $scope.errors.totalrows ,$scope.errors.selected.value);
                        errors_list( $scope.errors.search_temp, $scope.errors.page, $scope.errors.selected.value);
                    }
                }
            );
        }

        let batch_created = function( range, sortercode, description ){
            generator_model.batch_created(
                range,
                sortercode,
                description,
                function(response){
                    if(response.status == 200){
                        if(response.data){
                            $scope.batch.page = 1;
                            batch_count( $scope.batch.search_temp);
                            $("#addBatchModal").modal('toggle');
                        }
                    }
                }
            );            
        }

        let batch_updated = function( batch_id, description ){
            generator_model.batch_updated(
                batch_id,
                description,
                function(response){
                    if(response.status == 200){
                        if(response.data){
                            batch_count( $scope.batch.search_temp);
                            $("#addBatchModal").modal('toggle');
                        }
                    }
                }
            );            
        }

        let batch_deleted = function( batch_id ){
            generator_model.batch_deleted(
                batch_id,
                function(response){
                    if(response.status == 200){
                        if(response.data != 0){
                            batch_count( $scope.batch.search_temp);
                            $("#addBatchModal").modal('toggle');
                        }
                    }
                }
            );            
        }

        let errors_deleted = function( errors_id ){
            generator_model.errors_deleted(
                errors_id,
                function(response){ console.log(response.data);
                    if(response.status == 200){
                        if(response.data != 0){
                            errors_count( $scope.errors.search_temp);
                            $("#addErrorsModal").modal('toggle');
                        }
                    }
                }
            );            
        }

        let logout = function( ){
            generator_model.logout(
                function(response){ 
                    if(response.status == 200){ 
                        if(response.data.validity){
                            location.reload();   
                        }
                    }
                }
            );            
        }

        $scope.batch.list_search = function(){
            $scope.batch.search_temp = $scope.batch.search;
            batch_count( $scope.batch.search_temp);
        }

        $scope.batch.list_tab = function(){
            $scope.batch.page = 1;
            batch_count($scope.batch.search_temp);
        }

        $scope.scanned.list_search = function(){
            $scope.scanned.search_temp = $scope.scanned.search;
            scanned_count( $scope.scanned.search_temp);
        }

        $scope.scanned.list_tab = function(){
            $scope.scanned.page = 1;
            scanned_count($scope.scanned.search_temp);
        }

        $scope.unscanned.list_search = function(){  
            $scope.unscanned.search_temp = $scope.unscanned.search;
            unscanned_count( $scope.unscanned.search_temp);
        }

        $scope.unscanned.list_tab = function(){
            $scope.unscanned.page = 1;
            unscanned_count($scope.unscanned.search_temp);
        }

        $scope.errors.list_search = function(){  
            $scope.errors.search_temp = $scope.errors.search;
            errors_count( $scope.errors.search_temp);
        }

        $scope.errors.list_tab = function(){
            $scope.errors.page = 1;
            errors_count($scope.errors.search_temp);
        }
        
        $scope.unscanned.pagination.first_click =  function(){
            if($scope.unscanned.pagination.state.currentPage > 1){
                $scope.unscanned.page = 1;
                unscanned_count( $scope.unscanned.search_temp);
            }	
        }
        
        $scope.unscanned.pagination.previous_click =  function(){
            if($scope.unscanned.pagination.state.currentPage > 1){
                $scope.unscanned.page = $scope.unscanned.pagination.state.currentPage - 1;
                unscanned_count( $scope.unscanned.search_temp);
            }
        }
        
        $scope.unscanned.pagination.previouspages_click =  function(){
            if($scope.unscanned.pagination.state.currentPage > 1){
                $scope.unscanned.page = $scope.unscanned.pagination.state.currentPageFrom - 1;
                unscanned_count( $scope.unscanned.search_temp);
            }
        }

        $scope.unscanned.pagination.pages_click = function(value){
            $scope.unscanned.page = value.page;
            unscanned_count( $scope.unscanned.search_temp);
        }

        $scope.unscanned.pagination.nextpages_click =  function(){
            	
            if($scope.unscanned.pagination.state.currentPage < $scope.unscanned.pagination.state.totalPages){
                
                $scope.unscanned.page = $scope.unscanned.pagination.state.currentPageTo + 1;
                unscanned_count( $scope.unscanned.search_temp);
            }
        }
        
        $scope.unscanned.pagination.next_click = function(){		
            if($scope.unscanned.pagination.state.currentPage < $scope.unscanned.pagination.state.totalPages){
                $scope.unscanned.page = $scope.unscanned.pagination.state.currentPage + 1,
                unscanned_count( $scope.unscanned.search_temp);
            }
        }
        
        $scope.unscanned.pagination.last_click = function(){
            if( $scope.unscanned.pagination.state.currentPage < $scope.unscanned.pagination.state.totalPages){
                $scope.unscanned.page = $scope.unscanned.pagination.state.totalPages;
                unscanned_count( $scope.unscanned.search_temp);
            }
        }


        $scope.scanned.pagination.first_click =  function(){
            if($scope.scanned.pagination.state.currentPage > 1){
                $scope.scanned.page = 1;
                scanned_count( $scope.scanned.search_temp);
            }	
        }
        
        $scope.scanned.pagination.previous_click =  function(){
            if($scope.scanned.pagination.state.currentPage > 1){
                $scope.scanned.page = $scope.scanned.pagination.state.currentPage - 1;
                scanned_count( $scope.scanned.search_temp);
            }
        }
        
        $scope.scanned.pagination.previouspages_click =  function(){
            if($scope.scanned.pagination.state.currentPage > 1){
                $scope.scanned.page = $scope.scanned.pagination.state.currentPageFrom - 1;
                scanned_count( $scope.scanned.search_temp);
            }
        }

        $scope.scanned.pagination.pages_click = function(value){
            $scope.scanned.page = value.page;
            scanned_count( $scope.scanned.search_temp);
        }

        $scope.scanned.pagination.nextpages_click =  function(){
            	
            if($scope.scanned.pagination.state.currentPage < $scope.scanned.pagination.state.totalPages){
                
                $scope.scanned.page = $scope.scanned.pagination.state.currentPageTo + 1;
                scanned_count( $scope.scanned.search_temp);
            }
        }
        
        $scope.scanned.pagination.next_click = function(){		
            if($scope.scanned.pagination.state.currentPage < $scope.scanned.pagination.state.totalPages){
                $scope.scanned.page = $scope.scanned.pagination.state.currentPage + 1,
                scanned_count( $scope.scanned.search_temp);
            }
        }
        
        $scope.scanned.pagination.last_click = function(){
            if( $scope.scanned.pagination.state.currentPage < $scope.scanned.pagination.state.totalPages){
                $scope.scanned.page = $scope.scanned.pagination.state.totalPages;
                scanned_count( $scope.scanned.search_temp);
            }
        }


        $scope.batch.pagination.first_click =  function(){
            if($scope.batch.pagination.state.currentPage > 1){
                $scope.batch.page = 1;
                batch_count( $scope.batch.search_temp);
            }	
        }
        
        $scope.batch.pagination.previous_click =  function(){
            if($scope.batch.pagination.state.currentPage > 1){
                $scope.batch.page = $scope.batch.pagination.state.currentPage - 1;
                batch_count( $scope.batch.search_temp);
            }
        }
        
        $scope.batch.pagination.previouspages_click =  function(){
            if($scope.batch.pagination.state.currentPage > 1){
                $scope.batch.page = $scope.batch.pagination.state.currentPageFrom - 1;
                batch_count( $scope.batch.search_temp);
            }
        }

        $scope.batch.pagination.pages_click = function(value){
            $scope.batch.page = value.page;
            batch_count( $scope.batch.search_temp);
        }

        $scope.batch.pagination.nextpages_click =  function(){
            	
            if($scope.batch.pagination.state.currentPage < $scope.batch.pagination.state.totalPages){
                
                $scope.batch.page = $scope.batch.pagination.state.currentPageTo + 1;
                batch_count( $scope.batch.search_temp);
            }
        }
        
        $scope.batch.pagination.next_click = function(){		
            if($scope.batch.pagination.state.currentPage < $scope.batch.pagination.state.totalPages){
                $scope.batch.page = $scope.batch.pagination.state.currentPage + 1,
                batch_count( $scope.batch.search_temp);
            }
        }
        
        $scope.batch.pagination.last_click = function(){
            if( $scope.batch.pagination.state.currentPage < $scope.batch.pagination.state.totalPages){
                $scope.batch.page = $scope.batch.pagination.state.totalPages;
                batch_count( $scope.batch.search_temp);
            }
        }
       
        $scope.errors.pagination.first_click =  function(){
            if($scope.errors.pagination.state.currentPage > 1){
                $scope.errors.page = 1;
                errors_count( $scope.errors.search_temp);
            }	
        }
        
        $scope.errors.pagination.previous_click =  function(){
            if($scope.errors.pagination.state.currentPage > 1){
                $scope.errors.page = $scope.errors.pagination.state.currentPage - 1;
                errors_count( $scope.errors.search_temp);
            }
        }
        
        $scope.errors.pagination.previouspages_click =  function(){
            if($scope.errors.pagination.state.currentPage > 1){
                $scope.errors.page = $scope.errors.pagination.state.currentPageFrom - 1;
                errors_count( $scope.errors.search_temp);
            }
        }

        $scope.errors.pagination.pages_click = function(value){
            $scope.errors.page = value.page;
            errors_count( $scope.errors.search_temp);
        }

        $scope.errors.pagination.nextpages_click =  function(){
            	
            if($scope.errors.pagination.state.currentPage < $scope.errors.pagination.state.totalPages){
                
                $scope.errors.page = $scope.errors.pagination.state.currentPageTo + 1;
                errors_count( $scope.errors.search_temp);
            }
        }
        
        $scope.errors.pagination.next_click = function(){		
            if($scope.errors.pagination.state.currentPage < $scope.errors.pagination.state.totalPages){
                $scope.errors.page = $scope.errors.pagination.state.currentPage + 1,
                errors_count( $scope.errors.search_temp);
            }
        }
        
        $scope.errors.pagination.last_click = function(){
            if( $scope.errors.pagination.state.currentPage < $scope.errors.pagination.state.totalPages){
                $scope.errors.page = $scope.errors.pagination.state.totalPages;
                errors_count( $scope.errors.search_temp);
            }
        }

        $scope.batch.selected_change = function( ){
            $scope.batch.page = 1;
            batch_count( $scope.batch.search_temp);
        }

        $scope.unscanned.selected_change = function( ){
            $scope.unscanned.page = 1;
            unscanned_count( $scope.unscanned.search_temp);
        }

        $scope.scanned.selected_change = function( ){
            $scope.scanned.page = 1;
            scanned_count( $scope.scanned.search_temp);
        }

        $scope.errors.selected_change = function( ){
            $scope.errors.page = 1;
            errors_count( $scope.errors.search_temp);
        }

        $scope.batch.show_barcodes = function( value ){
            $window.open('barcode/read/batch/'+value, '_blank');
        }

        $scope.batch.obj.create_entry_click = function(){
            $("#addBatchModal").modal('toggle'); 
            $scope.batch.obj.create_title_show = "Create";
            $scope.batch.obj.create_show = true;    
            $scope.batch.obj.update_show = false;
            $scope.batch.obj.delete_show = false;

            $scope.batch.obj.created_at_visibility = false;
            $scope.batch.obj.update_at_visibility = false;
            $scope.batch.obj.batch_id_visibility = false;
            $scope.batch.obj.sortercode_visibility = true;
            $scope.batch.obj.max_range_visibility = true;

            $scope.batch.obj.sortercode_disabled = false;
            $scope.batch.obj.max_range_disabled = false;
        
            $scope.batch.obj.sortercode = $scope.sortercode[0];
            $scope.batch.obj.max_range = $scope.ranges[0];
            $scope.batch.obj.description = "";

            $scope.batch.obj.create_disabled = false;
            $scope.batch.obj.loading_visibility = false;

        }

        $scope.batch.obj.modify_entry_click = function( obj ){
            $("#addBatchModal").modal('toggle');
            $scope.batch.obj.create_title_show = "Modify"; 
            $scope.batch.obj.create_show = false; 
            $scope.batch.obj.update_show = true;
            $scope.batch.obj.delete_show = true;
            
            $scope.batch.obj.created_at_visibility = true;
            $scope.batch.obj.update_at_visibility = true;
            $scope.batch.obj.batch_id_visibility = true;
            $scope.batch.obj.sortercode_visibility = true;
            $scope.batch.obj.max_range_visibility = true;

            $scope.batch.obj.sortercode_disabled = true;
            $scope.batch.obj.max_range_disabled = true;

            $scope.batch.obj.created_at = obj.created_at; 
            $scope.batch.obj.updated_at = obj.updated_at;
            $scope.batch.obj.batch_id = obj.batch_id;

            $scope.batch.obj.sortercode = $scope.sortercode[$scope.sortercode.findIndex( record => record.value === obj.sortercode )];
            $scope.batch.obj.max_range = $scope.ranges[$scope.ranges.findIndex( record => record.value === obj.total_rows )];

            $scope.batch.obj.description = obj.description;

            $scope.batch.obj.update_disabled = false;
            $scope.batch.obj.delete_disabled = false;
            $scope.batch.obj.loading_visibility = false;

        }

        $scope.errors.obj.modify_entry_click = function( obj ){
            $("#addErrorsModal").modal('toggle'); 
            $scope.errors.obj.create_title_show = "Modify"; 
            $scope.errors.obj.delete_show = true;
            $scope.errors.obj.loading_visibility = false;
            $scope.errors.obj.delete_disabled = false;


            $scope.errors.obj.created_at = obj.created_at;
            $scope.errors.obj.id = obj.id;
            $scope.errors.obj.barcode = obj.barcode;

        }

        $scope.batch.obj.created_click = function(){
            
            $scope.batch.obj.create_disabled = true;
            $scope.batch.obj.loading_visibility = true;

            batch_created(  
                $scope.batch.obj.max_range.value,
                $scope.batch.obj.sortercode.value,
                $scope.batch.obj.description 
            );
        }


        $scope.batch.obj.update_click = function(){
            $scope.batch.obj.update_disabled = true;
            $scope.batch.obj.delete_disabled = true;
            $scope.batch.obj.loading_visibility = true;
          
            batch_updated( $scope.batch.obj.batch_id, $scope.batch.obj.description);
            
        }

        $scope.batch.obj.delete_click = function(){
            $scope.batch.obj.update_disabled = true;
            $scope.batch.obj.delete_disabled = true;
            $scope.batch.obj.loading_visibility = true;

            batch_deleted($scope.batch.obj.batch_id);
        }

        $scope.errors.obj.delete_click = function(){
            $scope.errors.obj.delete_disabled = true;
            $scope.errors.obj.loading_visibility = true;

            errors_deleted($scope.errors.obj.id);
        }

        $scope.logout_click = function(){
            logout();
        }

        $scope.scanned.selected = $scope.ranges[0];
        $scope.unscanned.selected = $scope.ranges[0];
        $scope.batch.selected = $scope.ranges[0];
        $scope.errors.selected = $scope.ranges[0];

        $scope.batch.page = 1;
        $scope.unscanned.page = 1;
        $scope.scanned.page = 1;
        $scope.errors.page = 1;

        batch_count( $scope.batch.search_temp);
        scanned_count( $scope.scanned.search_temp);
        unscanned_count( $scope.unscanned.search_temp);
        errors_count( $scope.errors.search_temp);

    }
]).factory('generator_model',[
    '$http',
    function($http){
        
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

		service.batch_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/batch',
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


        service.scanned_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/scanned',
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

        service.unscanned_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/unscanned',
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

        service.errors_list = function(
            page,
            range,
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/errors',
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

		service.batch_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/batch/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.scanned_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/scanned/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.unscanned_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/unscanned/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.errors_count = function(
            search,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/read/errors/count',
                { 
                    search : search 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.batch_created = function(
            range,
            sortercode,
            description,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/create',
                { 
                    range : range,
                    sortercode : sortercode,
                    description : description 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.batch_updated = function(
            batch_id,
            description,
			callback		
		){
			$http.post(
                'conveyor/api/v1/generator/update',
                { 
                    batch_id : batch_id,
                    description : description 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.batch_deleted = function(
            batch_id,
            callback		
		){
			$http.post(
                'conveyor/api/v1/generator/delete',
                { 
                    batch_id : batch_id 
                }
			).then(
			   function(response){ callback(response); }, 
			   function(response){ callback(response); }
			);			
        }

        service.errors_deleted = function(
            errors_id,
            callback		
		){
			$http.post(
                'conveyor/api/v1/generator/errors/delete',
                { 
                    errors_id : errors_id 
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
