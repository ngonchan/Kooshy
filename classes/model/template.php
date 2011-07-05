<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Template database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Template extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'sites' => array('model' => 'site', 'through' => 'site_templates'),
	);

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'name' => array(
			'not_empty'  => NULL,
			'max_length' => array(80),
		),
		'body' => array(
			'not_empty'  => NULL,
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
				$this->_enabled = ORM::factory('site_template')
					->where('site_id', '=', KMS::instance('site')->id)
					->where('template_id', '=', $this->id)
					->find()->enabled;
			}
			return $this->_enabled;
		}
	}

}