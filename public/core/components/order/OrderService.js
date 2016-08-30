var orderApp = angular.module('order', []);
orderApp.factory('OrderResource',['$resource', function ($resource){
    return $resource('/api/order/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('OrderService', ['OrderResource', '$q', function (OrderResource, $q) {
    var that = this;
    var listsOrder = [];

    /* Set order to array listsOrder */
    this.setOrders = function(data) {
        listsOrder = data;
        return listsOrder;
    }
    
    /* Get array listsOrder */
    this.getOrders = function() {
        return listsOrder;
    }

}]);
