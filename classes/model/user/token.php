<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * User Token database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_User_Token extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  Auto-update columns for creation and updates
	 */
	protected $_created_column = array('column' => 'created', 'format' => TRUE);

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array('user' => array());

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'user_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'token' => array(
			'not_empty'  => NULL,
			'max_length' => array(40),
		),
		'expires' => array(
			'not_empty'  => NULL,
			'min_length' => array(10),
			'max_length' => array(10),
		),
	);

}