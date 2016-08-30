var roleApp = angular.module('role', []);
roleApp.factory('RoleResource',['$resource', function ($resource){
    return $resource('/api/role/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('RoleService', ['RoleResource', '$q', function (RoleResource, $q) {
    var that = this;
    var roles = [];

    /* Function create new role */
    this.createRoleProvider = function(data){

        /* If isset id of role then call function edit role */
        if(typeof data['id'] != 'undefined') {
            return that.editRoleProvider(data);
        }
        
        var defer = $q.defer(); 
        var temp  = new RoleResource(data);

        temp.$save({}, function success(data) {
            /* If role is created */
            if(data.status != 0) {
                roles.push(data.role);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        
        /* If create role is error */
        function error(reponse) {
            /* Resolve result */
            defer.resolve(reponse.data);
        });

        return defer.promise;  
    };

    /* Function edit role */
    this.editRoleProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new RoleResource(data);
        /* Update role successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If role is edited */
            if(data.status != 0){
                /* Each role */
                for (var key in roles) {
                    /* Set role edited */
                    if (roles[key].id == data.role.id){
                        roles[key] = data.role;
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        /* If create role is error */
        function error(reponse) {
            /* If create role is error */
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete role */
    this.deleteRole = function (id) {
        var defer = $q.defer(); 
        var temp  = new RoleResource();
        /* Delete role is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If role is deleted */
            if(data.status != 0){
                /* Each role */
                for (var key in roles) {
                    /* Delete role is deleted in array roles */
                    if (roles[key].id == id){
                        roles.splice(key, 1);
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        
        /* If delete role is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Set role to array roles */
    this.setRoles = function(data) {
        roles = data;
        return roles;
    }
    
    /* Get array roles */
    this.getRoles = function() {
        return roles;
    }

}]);
