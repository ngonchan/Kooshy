<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * User database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_User extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'actions'    => array( 'model' => 'user_action' ),
		'privileges' => array( 'model' => 'action', 'through' => 'action_users', 'far_key' => 'action_id'),
		'sites'      => array( 'model' => 'site', 'through' => 'site_users' ),
		'role'       => array( 'model' => 'role', 'through' => 'site_users' ),
		'tokens'     => array( 'model' => 'user_token' ),
	);

	/**
	 * @var  array  specify which relationships to autoload
	 */
	protected $_load_with = array('role');

	/**
	 * @var  array  data columns to ignore
	 */
	protected $_ignored_columns = array('password_confirm');

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'username' => array(
			'not_empty'  => NULL,
			'min_length' => array(6),
			'max_length' => array(40),
		),
		'password' => array(
			'not_empty'  => NULL,
			'min_length' => array(6),
			'matches'    => array('password_confirm'),
			'regex'      => array('/^(\S*)$/'),
		),
		'first_name' => array(
			'not_empty'  => NULL,
			'max_length' => array(80),
		),
		'last_name' => array(
			'not_empty'  => NULL,
			'max_length' => array(80),
		),
		'email' => array(
			'not_empty'  => NULL,
			'max_length' => array(100),
			'email'      => NULL,
		),
		'active' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(1),
		),
	);

	/**
	 * @var  array  validation callbacks
	 */
	protected $_callbacks = array(
		'username' => array( 'check_username' ),
	);

	/**
	 * Validation callback to verify that the entered username
	 * does not already exist in the site database
	 * @param   Validate  Validation object
	 * @param   string    data field to validate against
	 * @return  void
	 */
	public function check_username(Validate $validate, $field) {
		if ( !isset($this->_changed['username']) ) return;
		$username_exists = KMS::instance('site')->users->where('username', '=', $validate[$field])->find();

		if ($username_exists->loaded()) {
			$validate->error($field, 'already_exists', array($validate[$field]));
		}
	}

}