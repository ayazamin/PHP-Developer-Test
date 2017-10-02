<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="Text/HTML"/>
    <meta charset="UTF-8"/>
    <title>Assignment</title>

    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>

    <script src="js/angular.min.js"></script>
    <script src="js/ui-bootstrap-tpls-1.3.2.min.js"></script>
    <script src="js/angular.custom.js"></script>
</head>


<body>
<div ng-app="app">
    <div class="container" ng-controller="DemoController">

        <form class="navbar-form" role="search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="srch-term" ng-model="searchSpec.filter">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit" ng-click="applyFilter(false);">Search</button>
                </div>
            </div>
        </form>

        <div class="error" >
            {{message}}
        </div>

        <div>
            <div class="totalRecord pull-left" ng-hide="showloader">{{totalRecords}} Result Found</div>
            <div class="pull-right" ng-if="totalRecords > searchSpec.pageSize">
                <uib-pagination boundary-links="true"
                                boundary-link-numbers="true"
                                items-per-page="searchSpec.pageSize" total-items="totalRecords"
                                ng-change="setPage(searchSpec.currentPage)" ng-model="searchSpec.currentPage"
                                rotate="true" max-size="maxSize"
                                class="pagination-sm pull-right hidden-xs" previous-text="Prev &lsaquo;"
                                next-text="Next &rsaquo;" first-text="First &laquo;"
                                last-text="Last &raquo;">

                </uib-pagination>
            </div>
        </div>

        <div class="loader" ng-show="showloader"></div>
        <table class="table table-bordered" ng-hide="showloader">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="data in dataList">
                <td>{{pageNo + $index +1}}</td>
                <td> {{data.name}}</td>
                <td>{{data.description}}</td>
            </tr>
            </tbody>
        </table>

        <div class="pull-right" ng-if="totalRecords > searchSpec.pageSize">
            <uib-pagination boundary-links="true"
                            boundary-link-numbers="true"
                            items-per-page="searchSpec.pageSize" total-items="totalRecords"
                            ng-change="setPage(searchSpec.currentPage)" ng-model="searchSpec.currentPage"
                            rotate="true" max-size="maxSize"
                            class="pagination-sm pull-right hidden-xs" previous-text="Prev &lsaquo;"
                            next-text="Next &rsaquo;" first-text="First &laquo;"
                            last-text="Last &raquo;">

            </uib-pagination>
        </div>
    </div>
</div>
</body>
</html>