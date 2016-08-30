
var defaultModules = 
[
    'ui.bootstrap',
    'ngResource',
    'xeditable',
    'ngTable',
    'ngImgCrop',
    'user',
    'role',
    'permission',
    'userProfile',
    'ngFileUpload',
    'multiSelect',
    'article',
    'category',
    'selectLevelCategory',
    'file',
    'product',
    'collection',
    'order'
];

if(typeof modules != 'undefined'){
	defaultModules = defaultModules.concat(modules);
}

angular.module('core', defaultModules);

angular.module('core').run(['editableOptions',function(editableOptions) {
    editableOptions.theme = 'bs3';
}]);

