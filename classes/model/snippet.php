<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Snippet database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Snippet extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'sites' => array('model' => 'site', 'through' => 'site_snippets'),
	);

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'code' => array(
			'not_empty' => NULL,
			'max_length' => array(80),
		),
		'description' => array(
			'not_empty' => NULL,
		),
		'body' => array(
			'not_empty' => NULL,
		),
		'eval' => array(
			'not_empty' => NULL,
			'min_length' => array(1),
			'max_length' => array(1),
		),
	);

	/**
	 * @var  string  holds the enabled value
	 */
	private $_enabled = NULL;

	/**
	 * Overrides ORM get magic method to allow for enabled
	 * @param   string  name of column/relationship to return
	 * @return  mixed
	 */
	public function __get( $column ) {
		if ( $column != 'enabled' ) {
			return parent::__get($column);
		} else {
			if ($this->_enabled === NULL) {
				$this->_enabled = ORM::factory('site_snippet')
					->where('site_id', '=', KMS::instance('site')->id)
					->where('snippet_id', '=', $this->id)
					->order_by('snippet_id', 'ASC')
					->find()->enabled;
			}
			return $this->_enabled;
		}
	}

}