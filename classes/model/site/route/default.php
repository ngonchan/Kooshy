<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Route Default database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Route_Default extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array( 'site_route' => array() );

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'site_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'key' => array(
			'not_empty'  => NULL,
			'max_length' => array(120),
		),
		'value' => array(
			'not_empty'  => NULL,
			'max_length' => array(255),
		),
	);

}