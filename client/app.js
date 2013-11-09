
var app = angular.module('myApp', ['ngGrid']);

app.controller('MyCtrl', function($scope, $http) {

    $scope.server = "../";
    $scope.records = [];
    $scope.selectedItems = [];
    $scope.tables = [];
    $scope.columnDefs = undefined;
    $scope.basesLabel = "Loading bases...";
    $scope.path = [""];

    $scope.gridOptions = { 
    	data: 'records',
        selectedItems: $scope.selectedItems,
        multiSelect: false ,
        columnDefs:  'columnDefs',
        enablePaging: true,
        showFooter: true,
        //totalServerItems: 'totalServerItems',
        enableCellSelection: true,
        enableRowSelection: false,
        enableCellEdit: true,
    };

    $scope.load_base = function(base) {
        $scope.basesLabel = "Loading table...";
      
        $scope.base = base;
        $http.get($scope.server + base).success( function(data) {
            
            $scope.tables = data;
        });
    }

    $scope.load = function(table) {
        $scope.table = table;
        $scope.tableSelected = table;
        $scope.path = [$scope.server,$scope.base, $scope.table];
        $scope.columnDefs = [];
        if(!table) { 
            return;
        }
	    $http.get($scope.server + $scope.base + '/' + table).success( function(data) {
	    	$scope.columnDefs = [];
            // Change colums
			for(var k in data[0]) {
				$scope.columnDefs.push({"field":k})
			};
            // Change data
    		$scope.records = data;
    	});
	}

    $http.get($scope.server).success( function(data) {
    	
        $scope.bases = data;
        $scope.basesLabel = "Tables ▾";
    });

});