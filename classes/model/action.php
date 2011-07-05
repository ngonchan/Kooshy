<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Action database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Action extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'user_actions' => array('model' => 'user_action'),
		'action_users' => array('model' => 'action_user'),
		'action_roles' => array('model' => 'action_role'),
	);

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'name' => array(
			'not_empty'  => NULL,
			'min_length' => array(4),
			'max_length' => array(80),
		),
		'description' => array(
			'not_empty'  => NULL,
		),
	);

}