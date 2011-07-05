<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * List database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_List extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  int  holder for id to find
	 */
	private $_passed_id;

	/**
	 * Creates a new instance of a class
	 * @param  int  optional id to load
	 */
	public function __construct($id = NULL) {
		$this->_passed_id = $id;
		if (!empty($this->_preload_data))
			parent::__construct($id);
	}

	/**
	 * Loads up a specific list table ORM instance
	 * @param   string      name of the list to load
	 * @return  Model_List
	 */
	public function load($list_name) {
		$list = KMS::instance('site')->lists->where('name', '=', $list_name)->find();
		if (!$list->loaded()) throw new KMS_Exception ('Unable to load list `:list:`', array(':list:' => $list_name));

		$this->_table_name = 'list_' . KMS::instance('site')->id . '_' . strtolower($list->name);
		parent::__construct($this->_passed_id);

		return $this;
	}

	/**
	 * Returns list table columns
	 * @return  array
	 */
	public function columns() {
		return $this->_table_columns;
	}

	/**
	 * Returns list table name
	 * @return  string
	 */
	public function table_name() {
		return $this->_table_name;
	}

	/**
	 * Helper method to create a friendly table column name
	 * @param   string  name of the column
	 * @return  string
	 */
	private function _column_name($name) {
		$name = trim($name); // trim name
		$name = strtolower($name); // force lowercase
		$name = preg_replace('/[^a-z0-9\s\_]/', '', $name); // remove special characters
		$name = preg_replace('/\s+/', '_', $name); // change spaces to underscores
		$name = preg_replace('/\_+/', '_', $name); // clean up underscores
		return $name;
	}

	/**
	 * Creates a new list database table
	 * @param  string  name of the database table
	 * @param  array   names columns to create
	 * @param  array   types of columns to create
	 */
	public function create($table_name, $column_names, $column_types) {
		if (count($column_names) != count($column_types))
			throw new KMS_Exception ( 'Unable to create table. The number of columns do not match' );

		$table_name = $this->_column_name($table_name);
		foreach ($column_names as $key => $value) {
			$column_names[$key] = $this->_column_name($value);
		}

		$types = array(
			'integer'      => '`:name:` INT( 11 ) NOT NULL',
			'decimal'      => '`:name:` DECIMAL( 13, 2 ) NOT NULL',
			'text'         => '`:name:` VARCHAR( 255 ) NOT NULL',
			'long'         => '`:name:` MEDIUMTEXT NOT NULL',
			'long-wysiwyg' => '`:name:` LONGTEXT NOT NULL'
		);

		$sql = "CREATE TABLE `{$table_name}` (\n\t`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
		foreach ($column_types as $key => $type) {
			$sql .= "\n\t" . __($types[$type], array(':name:' => $column_names[$key])) . ',';
		}
		$sql = substr($sql, 0, -1);
		$sql .="\n) ENGINE = InnoDB;";
		DB::query('', $sql)->execute(KMS_DATABASE);
	}

	/**
	 * Deletes a database list table
	 */
	public function drop() {
		$sql = "DROP TABLE `{$this->_table_name}`";
		$this->_db->query('', $sql);
	}

}