<?php defined('SYSPATH') or die('No direct script access.');
/**
 * List parser for content loader. Takes the shortcodes from the content
 * and returns variables for the content loader to parse in snippets.
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS_List {

	/**
	 * @var string db name for the list
	 */
	protected $_list;

	/**
	 * @var ORM module of the loaded in data
	 */
	protected $_list_data;

	/**
	 * @var array columns for the given list
	 */
	protected $_list_columns;

	/**
	 * @var string raw param string of the list from the shortcode
	 */
	protected $_param_string;

	/**
	 *
	 * @var Pagination the pagination object for the list results
	 */
	protected $_pagination;

	/** LIST DEFAULTS **/
	/**
	 * var string name of the list
	 */
	protected $_name;

	/**
	 * var int number of items to list per page
	 */
	protected $_per_page = 20;

	/**
	 * var int number of items to limit the results to
	 */
	protected $_limit = -1;

	/**
	 * var string sorting options for the list
	 */
	protected $_sort = 'id:asc';

	/**
	 * Creates and returns a new KMS_List
	 * @param   array     preg_match results from shortcode
	 * @return  KMS_List
	 */
	public static function factory( $params ) {
		return new KMS_List($params);
	}

	/**
	 * Creates and returns a new KMS_List
	 * @param  array  preg_match results from shortcode
	 */
	public function __construct( $params ) {
		$this->_param_string = strtolower($params[1]);

		// get list
		$this->_name = $this->_list = preg_replace('/^(.+?)[\s$].*/', '$1', $this->_param_string);
		$this->_param_string = trim(preg_replace('/^' . preg_quote($this->_name, '/') . '/', '', $this->_param_string));
		//$this->_list = 'list_' . KMS::instance('site')->id . '_' . $this->_list;

		// get params
		foreach (explode(',', preg_replace('/\s/', '', $this->_param_string)) as $param) {
			list($key, $value) = explode('=', $param, 2);
			$this->_set_param($key, $value);
		}

		// set sorting
		$this->_set_sorting();

		// reset param string (for future development)
		$this->_param_string = strtolower($params[1]);
		$this->_load_list();
	}

	/**
	 * Provides the KMS_Content class with clean variables
	 * @return  array
	 */
	public function load() {
		return array($this->_name => (object) array(
			'list_name'  => $this->_list,
			'columns'    => $this->_list_columns,
			'data'       => $this->_list_data,
			'pagination' => $this->_pagination
		));
	}

	/**
	 * Helper method for parsing and setting list parameters
	 * @param  string  parameter to set
	 * @param  string  value to set parameter to
	 */
	protected function _set_param( $key, $value ) {
		$params = array('per_page', 'limit', 'sort', 'name');
		if (!in_array($key, $params)) return;
		if ($value == 'true') $value = TRUE;
		else if ($value == 'false') $value = FALSE;
		$key = '_' . $key;
		$this->$key = $value;
	}

	/**
	 * Helper function to parse the sort parameter into a
	 * parameter that the DB builder can use.
	 */
	protected function _set_sorting() {
		$sorting = array();
		foreach (explode(';', $this->_sort) as $sort) {
			$sort = explode(':', $sort, 2);
			if (empty($sort[1])) $sort[1] = 'asc';
			$sorting[$sort[0]] = strtoupper($sort[1]);
		}
		$this->_sort = $sorting;
	}

	/**
	 * Loads in the database results for the list
	 */
	protected function _load_list() {
		$model = ORM::factory('list')->load($this->_list);
		$total_rows = $model->count_all();
		$total_rows = min($total_rows, ($this->_limit > 0 ? $this->_limit : $total_rows));

		$this->_pagination = Pagination::factory(array(
			'total_items' => $total_rows,
			'items_per_page' => min($this->_per_page, $total_rows),
		));

		foreach ($this->_sort as $column => $direction) {
			$model->order_by($column, $direction);
		}
		$model->limit($this->_pagination->items_per_page);
		$model->offset($this->_pagination->offset);

		$this->_list_columns = array_keys($model->columns());
		$this->_pagination = $this->_pagination;
		$this->_list_data = $model->find_all();
	}

}
