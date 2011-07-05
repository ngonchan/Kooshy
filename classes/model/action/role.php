<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Action Roles database model. Holds actions that role's have privileges to.
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Action_Role extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array( 'actions' => array('model' => 'action') );

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'action_id' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(11),
		),
		'role_id' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(11),
		),
	);

}