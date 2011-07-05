<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site List database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site_List extends ORM {

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
		'name' => array(
			'not_empty'  => NULL,
			'max_length' => array(20),
		),
		'records' => array(
			'not_empty'  => NULL,
			'max_length' => array(20),
		),
	);

	/**
	 * Creates sidebar menu data for site lists
	 * @param   array  default menu data
	 * @return  array
	 */
	public function menu(array $default) {
		$keys = array_keys($default);
		$key = array_pop($keys);
		$data = $this->find_all();
		foreach ($data as $list) {
			$key += 10;
			$default[$key] = array(
				'title' => $list->name . ' List',
				'params' => array(
					'section' => 'view',
					'id' => $list->id
				)
			);
		}
		return $default;
	}

}