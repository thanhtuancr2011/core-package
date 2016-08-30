var userProfileApp = angular.module('userProfile', []);
userProfileApp.factory('UserProfileResource',['$resource', function ($resource){
    return $resource('/api/user/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('UserProfileService', ['UserProfileResource', '$q', function (UserProfileResource, $q) {

    /**
     * Call server to update user
     * @param  {[object]} data user info
     * @return {[promise]}      promise
     */
    this.update = function(data)
    {
        var defer = $q.defer();
        var temp = new UserProfileResource(data);
        temp.$update({'id':data['id']},
            function success(data) {
                defer.resolve(data);
            },
            function error(reponse) {
               defer.resolve(reponse.data);
            });

        return defer.promise;
    };

    /**
     * Change avarta for user
     * @author Tuan <thanhtuancr2011@gmail.com>
     * @param  {Int}    id        User id
     * @param  {Object} avatar    Image
     * @return {Void}       
     */
    this.changeAvatar = function(id, avatar)
    {
        var defer = $q.defer();
        UserProfileResource.save({method:'change-avatar', file: avatar, id: id},
            function success(data) {
                defer.resolve(data);
            },
            function error(reponse) {
               defer.resolve(reponse.data);
            });

        return defer.promise;
    };

    /**
     * Change avarta for user
     * @author Tuan <thanhtuancr2011@gmail.com>
     * @param  {Int}    id        User id
     * @param  {Object} avatar    Image
     * @return {Void}       
     */
    this.changePassword = function(data)
    {
        var defer = $q.defer(); 
        var temp  = new UserProfileResource(data);
        /* Update password successfull */
        temp.$save({method: 'change-password'}, function success(data) {
            defer.resolve(data);
        },
        /* If create password is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise; 
    };
}]);
