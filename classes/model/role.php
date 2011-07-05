<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Role database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Role extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'privileges' => array( 'model' => 'action', 'through' => 'action_roles', 'far_key' => 'action_id' ),
		'users'      => array( 'model' => 'site_user', 'far_key' => 'role_id' ),
	);

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'name' => array(
			'not_empty'  => NULL,
			'max_length' => array(70),
		),
		'description' => array(
			'not_empty'  => NULL,
		),
		'level' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(3),
		),
	);

}