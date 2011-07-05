<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Variable database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Variable extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'site_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'name' => array(
			'not_empty'  => NULL,
			'max_length' => array(120),
		),
		'value' => array(
			'max_length' => array(255),
		),
	);
	
}