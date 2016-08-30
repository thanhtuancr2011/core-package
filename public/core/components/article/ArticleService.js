var articleApp = angular.module('article', []);
articleApp.factory('ArticleResource',['$resource', function ($resource){
    return $resource('/api/article/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('ArticleService', ['ArticleResource', '$q', function (ArticleResource, $q) {
    var that = this;
    var articles = [];

    /* Function create new article */
    this.createArticleProvider = function(data){

        /* If isset id of article then call function edit article */
        if(typeof data['id'] != 'undefined') {
            return that.editArticleProvider(data);
        }
        
        var defer = $q.defer(); 
        var temp  = new ArticleResource(data);

        temp.$save({}, function success(data) {
            /* If article is created */
            if(data.status != 0) {
                articles.push(data.article);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        
        /* If create article is error */
        function error(reponse) {
            /* Resolve result */
            defer.resolve(reponse.data);
        });

        return defer.promise;  
    };

    /* Function edit article */
    this.editArticleProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new ArticleResource(data);
        /* Update article successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If article is edited */
            if(data.status != 0){
                /* Each article */
                for (var key in articles) {
                    /* Set article edited */
                    if (articles[key].id == data.article.id){
                        articles[key] = data.article;
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        /* If create article is error */
        function error(reponse) {
            /* If create article is error */
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete article */
    this.deleteArticle = function (id) {
        var defer = $q.defer(); 
        var temp  = new ArticleResource();
        /* Delete article is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If article is deleted */
            if(data.status != 0){
                /* Each article */
                for (var key in articles) {
                    /* Delete article is deleted in array articles */
                    if (articles[key].id == id){
                        articles.splice(key, 1);
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        
        /* If delete article is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Function edit article */
    this.createImageArticle = function(data){
        var defer = $q.defer(); 
        var temp  = new ArticleResource(data);
        /* Update article successfull */
        temp.$save({method: 'create-image-article'}, function success(data) {
            /* If article is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create article is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit article and update images of article */
    this.updateImageArticle = function(data){
        var defer = $q.defer(); 
        var temp  = new ArticleResource(data);
        /* Update article successfull */
        temp.$save({method: 'update-image-article'}, function success(data) {
            /* If article is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create article is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Set article to array articles */
    this.setArticles = function(data) {
        articles = data;
        return articles;
    }
    
    /* Get array articles */
    this.getArticles = function() {
        return articles;
    }

}]);
