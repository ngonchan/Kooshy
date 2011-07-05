<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Route database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Route extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array( 'site' => array() );

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'defaults' => array( 'model' => 'site_route_default' ),
		'regexps' => array( 'model' => 'site_route_regexp' ),
	);

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
			'max_length' => array(80),
		),
		'route' => array(
			'not_empty'  => NULL,
			'max_length' => array(255),
		),
		'regexp' => array(
			'max_length' => array(255),
		),
	);

}