<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Content database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Content extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array( 'site' => array() );

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'site_id' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(11),
		),
		'uri' => array(
			'max_length' => array(120),
		),
		'title' => array(
			'not_empty'  => NULL,
			'max_length' => array(120),
		),
		'body' => array(
			'not_empty'  => NULL,
		),
		'mime_type' => array(
			'not_empty'  => NULL,
			'max_length' => array(40),
		),
		'meta_keywords' => array(
			'max_length' => array(160),
		),
		'meta_description' => array(),
	);

	/**
	 * @var  array  validation filters
	 */
	protected $_filters = array(
		 'uri' => array('trim' => array('/'))
	);

	/**
	 * @var  array  default data sorting
	 */
	protected $_sorting = array('uri' => 'ASC');

}