articleApp.controller('ArticleController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'ArticleService', '$timeout', function ($scope, $uibModal, $filter, ngTableParams, ArticleService, $timeout) {
    /* When js didn't  loaded then hide table article */
    $('.container-fluid').removeClass('hidden');
    $('#page-loading').css('display', 'none');

    /* Not show search in table article */
    $scope.isSearch = false;

    /* Set data article to scope */
    $scope.data = ArticleService.setArticles(angular.copy(window.articles));
    
    /* Use ng-table for table article */
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
            var orderedData = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data; /* Filter article */
            orderedData = params.sorting() ? $filter('orderBy')(orderedData, params.orderBy()) : orderedData; /* Sort article */
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count())); /* Paging */
        }
    })

    $scope.getModalArticle = function(id){
        var template = '/admin/' + window.type + '/create?v='+ new Date().getTime();  /* Create article */
        if(typeof id != 'undefined'){
            template = '/admin/' + window.type + '/'+ id + '/edit?v=' + new Date().getTime(); /* Edit article */
        }
        window.location.href = window.baseUrl + template;
    };

    /* Delete article */
    $scope.removeArticle = function(id, size){
        var template = '/core/components/article/view/DeleteArticle.html?v=' + new Date().getTime() /* Delete article */
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalDeleteArticleCtrl',
            size: size,
            resolve: {
                articleId: function(){
                    return id;
                }
            }
        });

        /* After create or edit user then reset articles and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = ArticleService.getArticles();
            $scope.tableParams.reload();
        }, function () {

           });
    };

}])
.controller('CreateArticleController', ['$scope', '$timeout', 'ArticleService', function ($scope, $timeout, ArticleService) {

    /* Show */
    $timeout(function(){
        $('.container-fluid').removeClass('hidden');
        $('#page-loading').css('display', 'none');
        var editor = CKEDITOR.replace('content');
        $('.status-article').select2({});
    });

    /* Validate content */
    $scope.requiredContent = true;

    /* Event on change ck editor */ 
    CKEDITOR.on("instanceCreated", function(event) {
        event.editor.on("change", function () {
            $timeout(function(){
                if (event.editor.getData() != '') {
                    $scope.requiredContent = false;
                    $scope.articleItem.content = event.editor.getData();
                } else {
                    $scope.requiredContent = true;
                    $scope.articleItem.content = '';
                }
            });
        });
    });

    /* When user click add or edit article */
    $scope.createArticle = function (type) {

        $scope.type = type;

        $scope.submitted = true;

        if ($scope.articleItem.content != '') {
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

        var alias = $scope.replaceString($scope.articleItem.title);

        $scope.articleItem.alias_title = alias.replace(/\s+/g,'-').toLowerCase();

        ArticleService.createArticleProvider($scope.articleItem).then(function (data){
            if(data.status == 0){
                $('#page-loading').css('display', 'none');
                $scope.errors = data.error;
            } else{
                $scope.articleItem.id = data.article.id;
                $scope.isSavedData = true;
            }
        })
    };

    /* When data is saved but no file then redirect to root manager */
    $scope.onRedirect = function(redirect) {
        if ($scope.type != 'Add') {
            ArticleService.updateImageArticle($scope.articleItem).then(function (data){
                if(data.status != 0){
                    window.location.href = window.baseUrl + '/admin/article';
                }
            });
        }
        window.location.href = window.baseUrl + '/admin/article';
    }

    /* After file uploaded */       
    $scope.$watch('fileUploaded', function(newVal, oldVal) {
        if (angular.isDefined(newVal)) {
            $scope.articleItem.fileUploaded = newVal.files;
            if ($scope.isSavedData) {
                // When create article
                if ($scope.type == 'Add') {
                    ArticleService.createImageArticle($scope.articleItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/article';
                        }
                    });
                // When update article
                } else {
                    ArticleService.updateImageArticle($scope.articleItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/article';
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
.controller('ModalDeleteArticleCtrl', ['$scope', '$uibModalInstance', 'articleId', 'ArticleService', function ($scope, $uibModalInstance, articleId, ArticleService) {
    /* When user click Delete user */
    $scope.submit = function () {
        $('#page-loading').css('display', 'block');
        ArticleService.deleteArticle(articleId).then(function (){
            $('#page-loading').css('display', 'none');
            $uibModalInstance.close();
        });
    };

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
