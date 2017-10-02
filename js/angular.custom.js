var app = angular.module("app", ['ui.bootstrap']);

app.controller('DemoController', ['$scope', '$http', function ($scope, $http) {

    $scope.dataList = [];
    $scope.searchSpec = {};
    $scope.searchSpec.pageSize = 15;
    $scope.maxSize = 5;
    $scope.message = "";
    $scope.searchSpec.currentPage = 1;

    $scope.applyFilter = function (pagination) {

        $scope.pageNo = $scope.searchSpec.pageSize*($scope.searchSpec.currentPage -1    );

        if (!pagination) {
            $scope.searchSpec.currentPage = 1;
            $scope.dataList = [];
        }
        $scope.showloader = true;
        $http.post("Fetch.php", $scope.searchSpec).success(function (res) {
            if(res.totalRecords) {
                $scope.totalRecords = res.totalRecords;
                $scope.dataList = res.data;
            }else{
                $scope.message = res;
            }
            $scope.showloader = false;
        });

    };

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
        $scope.applyFilter(true);
    };

    $scope.applyFilter(false);

}]);

