<?php defined('SYSPATH') or die('No direct access allowed.');

/*
 * KMS Constants
 */
define('KMS_DATABASE', 'kms');
define('KMS_VERSION', '0.2');

/*
 * KMS Routes
 */
Route::set('kms-super-admin', 'kms-admin/super(/<action>(/<section>(/<id>(/<subid>))))')
	->defaults(array(
		'controller' => 'kms_superadmin',
		'action'     => 'index'
	));
Route::set('kms-admin', 'kms-admin(/<action>(/<section>(/<id>(/<subid>))))', array('action' => '(?!sidebar|logout)[^/]*'))
	->defaults(array(
		'controller' => 'kms_admin',
		'action'     => 'login'
	));
Route::set('kms-admin-modules', 'kms-admin(/<action>)', array('action' => '(?!logout).*'))
	->defaults(array(
		'controller' => 'kms_module',
	));
Route::set('kms-action', 'kms-action(/<action>(/<id>))')
	->defaults(array(
		'controller' => 'kms_action',
	));
Route::set('kms-asset', 'kms-asset(/<type>(/<file>))', array('file' => '.*'))
	->defaults(array(
		'controller' => 'kms_asset',
		'action'     => 'load'
	));

/*
 * KMS Startup
 */

if (!defined('SUPPRESS_REQUEST')) {
	KMS::instance('site');
}