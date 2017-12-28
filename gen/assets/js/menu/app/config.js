$(function(){
	var muumApp = angular.module(_MOUDLE_NAME);
	if(!muumApp){
		console.e("no app");
		return;
	}
	// CONFIG ROUTE
	muumApp.config(["$routeProvider","$locationProvider","$sceDelegateProvider","$httpProvider",function($routeProvider,$locationProvider,$sceDelegateProvider,$httpProvider) {
		$locationProvider.html5Mode(false).hashPrefix("!");
		$sceDelegateProvider.resourceUrlWhitelist([
			"self",
			_HOST+"/**",
		]);
		$httpProvider.interceptors.push("sessionInterceptor");
		$routeProvider.
		/*when('/test',{
			templateUrl : 'mgr/test',
			controller : 'TestController',
		}).*/
		when("/data/demo/:id/:mode",{
			templateUrl : function (params) {
				return 'mgr/demo' + "?id="+params.id + "&mode=" + params.mode;
			},
			controller : 'DemoController'
		}).
		when('/menu/feature_list',{
			templateUrl : 'featureList',
			controller : 'FeatureListController',
		}).
		when('/menu/feature_add',{
			templateUrl : 'featureAdd',
			controller : 'FeatureAddController',
		}).
		when('/menu/feature_edit/:id',{
			templateUrl : 'featureAdd',
			controller : 'FeatureAddController',
		}).
		when('/menu/menu_edit',{
			templateUrl : 'menuEdit',
			controller : 'MenuEditController',
		}).
		when("/delivery/receipt",{
			templateUrl : 'mgr/receiptHome',
			controller : 'ReceiptController',
		}).
		when("/delivery/bargain",{
			templateUrl : 'mgr/bargainHome',
			controller : 'BargainController',
		}).
		when("/delivery/enroute",{
			templateUrl : 'mgr/enrouteHome',
			controller : 'EnrouteController',
		}).
		when("/delivery/management",{
			templateUrl : 'mgr/managementHome',
			controller : 'ManagementController',
		}).
		when("/delivery/abnormal",{
			templateUrl : 'mgr/abnormalHome',
			controller : 'AbnormalController',
		}).
		when("/data/user",{
			templateUrl : 'mgr/userHome',
			controller : 'UserController',
		}).
		when("/sign/editPwd",{
			templateUrl : 'mgr/editPassword',
			controller : 'EditPwdController',
		}).
		when("/user/home", {
			template: "<div>…</div>",
			controller: "HomeController"
		}).
		when("/sign/login", {
			template: "<div>…</div>",
			controller: "LoginController"
		}).
		when("/sign/logout",{
			template: "<br>",
			controller: "LogoutController"
		}).
		when("/",{
			redirectTo : "/user/home",
		}).
        when("/data/efficiency",{
            templateUrl : 'mgr/efficiencyHome',
            controller : 'EfficiencyController',
        }).
        when("/data/testdata",{
            templateUrl : 'mgr/testdataHome',
            controller : 'TestdataController',
        }).
        when("/data/testdata",{
            templateUrl : 'mgr/testdataHome',
            controller : 'TestdataController',
        }).
        when("/data/affiche",{
            templateUrl : 'mgr/afficheHome',
            controller : 'AfficheController',
        }).
        when("/data/room",{
            templateUrl : 'mgr/roomHome',
            controller : 'RoomController',
        }).
        when("/data/check",{
            templateUrl : 'mgr/checkHome',
            controller : 'CheckController',
        }).
        when("/data/checkpending",{
            templateUrl : 'mgr/checkpendingHome',
            controller : 'CheckpendingController',
        }).
        when("/data/area",{
            templateUrl : 'mgr/areaHome',
            controller : 'AreaController',
        }).
        when("/data/checkpending",{
            templateUrl : 'mgr/checkpendingHome',
            controller : 'CheckpendingController',
        }).
        when("/data/area",{
            templateUrl : 'mgr/areaHome',
            controller : 'AreaController',
        }).
        when("/data/roomuser",{
            templateUrl : 'mgr/roomuserHome',
            controller : 'RoomuserController',
        }).
        otherwise({
			redirectTo: "/"
		});
	}]);
});