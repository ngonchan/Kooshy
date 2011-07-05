<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site Template database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_Template extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  string  specifies the primary_key for the database taable
	 */
	protected $_primary_key = 'template_id';

	/**
	 * @var  array  ORM belongs_to relationships
	 */
	protected $_belongs_to = array( 'site' => array(), 'template' => array() );

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'site_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'template_id' => array(
			'not_empty'  => NULL,
			'max_length' => array(11),
		),
		'enabled' => array(
			'min_length' => array(1),
			'max_length' => array(1),
		),
	);

	/**
	 * Removes a site template from the site. Does not remove it from
	 * the KMS system.
	 * @param   int  template id to remove
	 * @return  Model_Site_Template
	 */
	public function delete($id = NULL) {
		if ($id === NULL) {
			// Use the the primary key value
			$id = $this->pk();
		}

		if ( ! empty($id) OR $id === '0') {
			// Delete the object
			DB::delete($this->_table_name)
				->where($this->_primary_key, '=', $id)
				->where('site_id', '=', KMS::instance('site')->id)
				->execute($this->_db);
		}

		return $this;
	}

}