<?
$pageUpper = strtoupper($app);
?>
$(function(){
	var <?=$app?>App = angular.module("<?=$app?>App");
	if(!<?=$app?>App){
		console.e("no app");
		return;
	}
	// CONFIG ROUTE
	<?=$app?>App.config(["$routeProvider","$locationProvider","$sceDelegateProvider","$httpProvider",function($routeProvider,$locationProvider,$sceDelegateProvider,$httpProvider) {
		$locationProvider.html5Mode(false).hashPrefix("!");
		$sceDelegateProvider.resourceUrlWhitelist([
			"self",
			_HOST+"/**",
		]);
		$httpProvider.interceptors.push("sessionInterceptor");
		$routeProvider.
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
        when("/data/test",{
            templateUrl : 'mgr/testHome',
            controller : 'TestController',
        }).
        otherwise({
			redirectTo: "/"
		});
	}]);
});