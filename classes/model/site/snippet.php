<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Snippet database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Snippet extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  string  specifies the primary_key for the database taable
	 */
	protected $_primary_key = 'snippet_id';

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array( 'site' => array(), 'snippet' => array() );

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'site_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'snippet_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'enabled' => array(
			'min_length' => array(1),
			'max_length' => array(1),
		),
	);

}