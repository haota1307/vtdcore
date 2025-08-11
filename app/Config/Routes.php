<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('test', 'TestController::index');

// Load module routes dynamically via ModuleManager
service('modules')->routes($routes);

// Auth routes
$routes->group('auth', static function($routes) {
	$routes->post('login', 'Auth\\AuthController::login', ['filter'=>'rate']);
	$routes->post('register', 'Auth\\AuthController::register');
	$routes->post('logout', 'Auth\\AuthController::logout', ['filter'=>'auth']);
	$routes->get('me', 'Auth\\AuthController::me', ['filter'=>'auth']);
	// token issue/revoke (session auth required)
	$routes->post('tokens', 'Auth\\TokenController::issue', ['filter'=>'auth,twofactor,rate']);
	$routes->delete('tokens/(:num)', 'Auth\\TokenController::revoke/$1', ['filter'=>'auth,twofactor']);
	// password reset
	$routes->post('password/forgot', 'Auth\\PasswordResetController::request');
	$routes->post('password/reset', 'Auth\\PasswordResetController::reset');
	$routes->post('2fa/enable', 'Auth\\TwoFactorController::enable', ['filter'=>'auth']);
	$routes->post('2fa/verify', 'Auth\\TwoFactorController::verify', ['filter'=>'auth']);
	$routes->post('2fa/disable', 'Auth\\TwoFactorController::disable', ['filter'=>'auth,twofactor']);
	$routes->post('2fa/backup-codes', 'Auth\\TwoFactorController::backupCodes', ['filter'=>'auth,twofactor']);
	$routes->post('2fa/backup-verify', 'Auth\\TwoFactorController::backupVerify', ['filter'=>'auth']);
	$routes->post('refresh/issue','Auth\\RefreshTokenController::issue', ['filter'=>'auth,twofactor,rate']);
	$routes->post('refresh/rotate','Auth\\RefreshTokenController::rotate', ['filter'=>'rate']);
	$routes->post('refresh/revoke','Auth\\RefreshTokenController::revoke', ['filter'=>'rate']);
	$routes->post('roles', 'Auth\\RoleController::create', ['filter'=>'auth,twofactor']);
	$routes->post('roles/attach/( :num)/( :num)', 'Auth\\RoleController::attach/$1/$2', ['filter'=>'auth,twofactor']);
	$routes->get('roles/( :num)', 'Auth\\RoleController::userRoles/$1', ['filter'=>'auth,twofactor']);
	$routes->post('roles/( :num)/permissions/add', 'Auth\\RoleController::addPermission/$1', ['filter'=>'auth,twofactor']);
	$routes->post('roles/( :num)/permissions/remove', 'Auth\\RoleController::removePermission/$1', ['filter'=>'auth,twofactor']);
});

// Media (basic)
$routes->group('media', static function($routes){
	$routes->post('upload', 'Media\\MediaController::upload', ['filter'=>'auth,twofactor,rate']);
	$routes->post('chunk/init', 'Media\\MediaController::chunkInit', ['filter'=>'auth,twofactor,rate']);
	$routes->post('chunk/put', 'Media\\MediaController::chunkPut', ['filter'=>'auth,twofactor,rate']);
	$routes->get('item/(:num)', 'Media\\MediaController::show/$1', ['filter'=>'auth,twofactor']);
	$routes->delete('item/(:num)', 'Media\\MediaController::delete/$1', ['filter'=>'auth,twofactor']);
	$routes->post('item/(:num)/restore', 'Media\\MediaController::restore/$1', ['filter'=>'auth,twofactor']);
	$routes->get('item/(:num)/thumb', 'Media\\MediaController::thumb/$1');
	$routes->get('item/(:num)/variant/(:segment)', 'Media\\MediaController::variant/$1/$2');
	$routes->get('list', 'Media\\MediaController::list', ['filter'=>'bearer:media.read']);
	$routes->get('list-admin', 'Media\\MediaController::list', ['filter'=>'can:admin.media.manage,twofactor']);
	
	// Enhanced media functionality
	$routes->get('search', 'Media\\MediaController::search', ['filter'=>'auth,twofactor']);
	$routes->get('stats', 'Media\\MediaController::stats', ['filter'=>'can:admin.media.manage,twofactor']);
	$routes->post('item/(:num)/move', 'Media\\MediaController::move/$1', ['filter'=>'can:admin.media.manage,twofactor']);
	$routes->delete('item/(:num)/permanent', 'Media\\MediaController::hardDelete/$1', ['filter'=>'can:admin.media.manage,twofactor']);
	$routes->post('bulk-delete', 'Media\\MediaController::bulkDelete', ['filter'=>'can:admin.media.manage,twofactor']);
	$routes->get('item/(:num)/download', 'Media\\MediaController::download/$1', ['filter'=>'auth,twofactor']);
});

// Example bearer-protected route
$routes->get('profile', static function(){
	$request = service('request');
	if (!isset($request->user)) {
		return service('response')->setStatusCode(401)->setJSON(['error'=>'No bearer user']);
	}
	return service('response')->setJSON([
		'user' => $request->user,
		'abilities' => $request->tokenAbilities ?? []
	]);
}, ['filter'=>'bearer']);

// OpenAPI dynamic
$routes->get('openapi.json','Docs\\OpenApiController::index');
$routes->get('health','HealthController::index');

// Audit logs (requires permission maybe in future)
	$routes->get('audit/logs','Audit\\AuditController::index', ['filter'=>'can:audit.view,twofactor']);

// Admin panel routes (UI)
$routes->group('admin', static function($routes){
	// Auth routes (no auth required)
	$routes->get('auth/login', 'Admin\\AuthController::login');
	$routes->post('auth/process-login', 'Admin\\AuthController::processLogin');
	$routes->get('auth/forgot-password', 'Admin\\AuthController::forgotPassword');
	$routes->post('auth/process-forgot-password', 'Admin\\AuthController::processForgotPassword');
	$routes->get('auth/reset-password', 'Admin\\AuthController::resetPassword');
	$routes->post('auth/process-reset-password', 'Admin\\AuthController::processResetPassword');
	$routes->get('auth/logout', 'Admin\\AuthController::logout', ['filter'=>'auth']);
	
	// Protected admin routes
	$routes->group('', ['filter'=>['auth','twofactor']], static function($routes){
		$routes->get('/', 'Admin\\DashboardController::index');
		$routes->get('users', 'Admin\\UsersController::index', ['filter'=>'can:admin.users.view']);
		$routes->post('users', 'Admin\\UsersController::create', ['filter'=>'can:admin.users.manage']);
		$routes->get('users/(:num)', 'Admin\\UsersController::show/$1', ['filter'=>'can:admin.users.manage']);
		$routes->put('users/(:num)', 'Admin\\UsersController::update/$1', ['filter'=>'can:admin.users.manage']);
		$routes->delete('users/(:num)', 'Admin\\UsersController::delete/$1', ['filter'=>'can:admin.users.manage']);
		$routes->post('users/(:num)/toggle', 'Admin\\UsersController::toggle/$1', ['filter'=>'can:admin.users.manage']);
		$routes->post('users/(:num)/reset-password', 'Admin\\UsersController::resetPassword/$1', ['filter'=>'can:admin.users.manage']);
		$routes->get('roles', 'Admin\\RolesController::index', ['filter'=>'can:admin.roles.view']);
		$routes->post('roles', 'Admin\\RolesController::create', ['filter'=>'can:admin.roles.manage']);
		$routes->get('roles/(:num)', 'Admin\\RolesController::show/$1', ['filter'=>'can:admin.roles.view']);
		$routes->put('roles/(:num)', 'Admin\\RolesController::edit/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->delete('roles/(:num)', 'Admin\\RolesController::delete/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->get('roles/(:num)/permissions', 'Admin\\RolesController::permissions/$1');
		$routes->get('test-auth', 'Admin\\RolesController::testAuth');
		$routes->get('test-simple', 'Admin\\RolesController::testSimple');
		$routes->post('roles/(:num)/permissions', 'Admin\\RolesController::updatePermissions/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->get('roles/(:num)/users', 'Admin\\RolesController::users/$1', ['filter'=>'can:admin.roles.view']);
		$routes->post('roles/(:num)/users', 'Admin\\RolesController::assignUser/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->delete('roles/(:num)/users/(:num)', 'Admin\\RolesController::removeUser/$1/$2', ['filter'=>'can:admin.roles.manage']);
		$routes->get('roles/(:num)/data', 'Admin\\RolesController::getRoleData/$1', ['filter'=>'can:admin.roles.view']);
		$routes->get('roles/search-users', 'Admin\\RolesController::searchUsers', ['filter'=>'can:admin.roles.manage']);
		$routes->get('roles/(:num)/export-users', 'Admin\\RolesController::exportUsers/$1', ['filter'=>'can:admin.roles.view']);
		$routes->post('roles/(:num)/import-users', 'Admin\\RolesController::importUsers/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->post('roles/(:num)/bulk-remove-users', 'Admin\\RolesController::bulkRemoveUsers/$1', ['filter'=>'can:admin.roles.manage']);
		$routes->get('media', 'Admin\\MediaController::index', ['filter'=>'can:admin.media.manage']);
		$routes->get('settings', 'Admin\\SettingsController::index', ['filter'=>'can:admin.settings.manage']);
		$routes->get('audit', 'Admin\\AuditLogsController::index', ['filter'=>'can:admin.audit.view']);
		
		// Sidebar Demo Routes
		$routes->get('sidebar-demo', 'Admin\\SidebarDemoController::index');
		$routes->get('sidebar-demo/custom', 'Admin\\SidebarDemoController::custom');
		$routes->get('sidebar-demo/debug', 'Admin\\SidebarDemoController::debug', ['filter'=>'can:admin.dashboard']);
	});
});
