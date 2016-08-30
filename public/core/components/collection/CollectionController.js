collectionApp.controller('CollectionController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'CollectionService', '$timeout', function ($scope, $uibModal, $filter, ngTableParams, CollectionService, $timeout) {
    /* When js didn't  loaded then hide table collection */
    $('.container-fluid').removeClass('hidden');
    $('#page-loading').css('display', 'none');

    /* Not show search in table collection */
    $scope.isSearch = false;

    /* Set data collection to scope */
    $scope.data = CollectionService.setCollections(angular.copy(window.collections));
    
    /* Use ng-table for table collection */
    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        filter: {
            title: ''
        },
        sorting: {
            title: 'asc'
        }

    }, {
        total: $scope.data.length,
        getData: function ($defer, params) {
            var orderedData = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data; /* Filter collection */
            orderedData = params.sorting() ? $filter('orderBy')(orderedData, params.orderBy()) : orderedData; /* Sort collection */
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count())); /* Paging */
        }
    })

    $scope.getModalCollection = function(id){
        var template = '/admin/collection/create?v='+ new Date().getTime();  /* Create collection */
        if(typeof id != 'undefined'){
            template = '/admin/collection/'+ id + '/edit?v=' + new Date().getTime(); /* Edit collection */
        }
        window.location.href = window.baseUrl + template;
    };

    /* Delete collection */
    $scope.removeCollection = function(id, size){
        var template = '/core/components/collection/view/DeleteCollection.html?v=' + new Date().getTime() /* Delete collection */
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalDeleteCollectionCtrl',
            size: size,
            resolve: {
                collectionId: function(){
                    return id;
                }
            }
        });

        /* After create or edit user then reset collections and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = CollectionService.getCollections();
            $scope.tableParams.reload();
        }, function () {

           });
    };

}])
.controller('CreateCollectionController', ['$scope', '$timeout', 'CollectionService', function ($scope, $timeout, CollectionService) {

    /* Show */
    $timeout(function(){
        $('.container-fluid').removeClass('hidden');
        $('#page-loading').css('display', 'none');
        var editor = CKEDITOR.replace('content');
        $('.status-collection').select2({});
    });

    /* Validate content */
    $scope.requiredContent = true;

    /* Event on change ck editor */ 
    CKEDITOR.on("instanceCreated", function(event) {
        event.editor.on("change", function () {
            $timeout(function(){
                if (event.editor.getData() != '') {
                    $scope.requiredContent = false;
                    $scope.collectionItem.content = event.editor.getData();
                } else {
                    $scope.requiredContent = true;
                    $scope.collectionItem.content = '';
                }
            });
        });
    });

    /* When user click add or edit collection */
    $scope.createCollection = function (type) {
        $scope.type = type;
        $scope.submitted = true;

        if ($scope.collectionItem.content != '') {
            $scope.requiredContent = false;
        }

        /* Stop when content is empty */
        if ($scope.requiredContent) {
            return;
        }

        $scope.replaceString = function (str) {  
        str= str.toLowerCase();  
        str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");  
        str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");  
        str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");  
        str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");  
        str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");  
        str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");  
        str= str.replace(/đ/g,"d");  
        return str;  
    }

        $('#page-loading').css('display', 'block');

        var alias = $scope.replaceString($scope.collectionItem.title);

        $scope.collectionItem.alias_title = alias.replace(/\s+/g,'-').toLowerCase();

        CollectionService.createCollectionProvider($scope.collectionItem).then(function (data){
            if(data.status == 0){
                $('#page-loading').css('display', 'none');
                $scope.errors = data.error;
            } else{
                $scope.collectionItem.id = data.collection.id;
                $scope.isSavedData = true;
            }
        })
    };

    /* When data is saved but no file then redirect to root manager */
    $scope.onRedirect = function(redirect) {
        if ($scope.type != 'Add') {
            CollectionService.updateImageCollection($scope.collectionItem).then(function (data){
                if(data.status != 0){
                    window.location.href = window.baseUrl + '/admin/collection';
                }
            });
        }
        window.location.href = window.baseUrl + '/admin/collection';
    }

    /* After file uploaded */       
    $scope.$watch('fileUploaded', function(newVal, oldVal) {
        if (angular.isDefined(newVal)) {
            $scope.collectionItem.fileUploaded = newVal.files;
            if ($scope.isSavedData) {
                // When create collection
                if ($scope.type == 'Add') {
                    CollectionService.createImageCollection($scope.collectionItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/collection';
                        }
                    });
                // When update collection
                } else {
                    CollectionService.updateImageCollection($scope.collectionItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/collection';
                        }
                    });
                }
            }
        }
    });

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        window.location.href = window.baseUrl + '/admin/' + window.type;
    };
}])
.controller('ModalDeleteCollectionCtrl', ['$scope', '$uibModalInstance', 'collectionId', 'CollectionService', function ($scope, $uibModalInstance, collectionId, CollectionService) {
    /* When user click Delete user */
    $scope.submit = function () {
        $('#page-loading').css('display', 'block');
        CollectionService.deleteCollection(collectionId).then(function (){
            $('#page-loading').css('display', 'none');
            $uibModalInstance.close();
        });
    };

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
