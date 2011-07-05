<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Session manager for KMS application
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS_Session {

	/**
	 * @var KMS_Session singleton instance for class
	 */
	protected static $_instance;

	/**
	 * @var Session session data for class
	 */
	protected $_data = array();

	/**
	 * Creates a new instance of the class
	 */
	public function __construct() {
		$this->_data = Session::instance()->get(KMS::Config()->session_key, array());
		Session::instance()->bind(KMS::Config()->session_key, $this->_data);
	}

	/**
	 * Deletes a key from the session data
	 * @param   string       name of the key to delete
	 * @return  KMS_Session
	 */
	public function delete($key) {
		$args = func_get_args();

		foreach ($args as $key) {
			if (!isset($this->_data[$key])) continue;
			unset($this->_data[$key]);
		}

		return $this;
	}

	/**
	 * Deletes all session data for KMS system
	 * @return  KMS_Session
	 */
	public function destroy() {
		$this->_data = array();
		return $this;
	}

	/**
	 * Creates / Updates session data for KMS system
	 * @param   string       $key name of the session key to create / update
	 * @param   mixed        $value data to store in the session
	 * @return  KMS_Session
	 */
	public function set($key, $value) {
		$this->_data[$key] = $value;
		return $this;
	}

	/**
	 * Gets a value from an array using a dot separated path.
	 *
	 *     // Get the value of $array['foo']['bar']
	 *     $value = KMS::Session()->path($array, 'foo.bar');
	 *
	 * Using a wildcard "*" will search intermediate arrays and return an array.
	 *
	 *     // Get the values of "color" in theme
	 *     $colors = KMS::Session()->path($array, 'theme.*.color');
	 *
	 *     // Using an array of keys
	 *     $colors = KMS::Session()->path($array, array('theme', '*', 'color'));
	 *
	 * @param   mixed   key path string (delimiter separated) or array of keys
	 * @param   mixed   default value if the path is not set
	 * @return  type
	 */
	public function path($path, $default = NULL) {
		return arr::path($this->_data, $path, $default);
	}

	/**
	 * Retrieve a single key from an array. If the key does not exist in the
	 * array, the default value will be returned instead.
	 *
	 *     // Get the value "username" from $_POST, if it exists
	 *     $username = KMS::Session()->get($_POST, 'username');
	 *
	 *     // Get the value "sorting" from $_GET, if it exists
	 *     $sorting = KMS::Session()->get($_GET, 'sorting');
	 *
	 * @param   string  key name
	 * @param   mixed   default value
	 * @return  type
	 */
	public function get($key, $default = NULL) {
		return array_key_exists($key, $this->_data) ? $this->_data[$key] : $default;
	}

	/**
	 * Get and delete a variable from the session array.
	 *
	 *     $bar = KMS::Session()->get_once('bar');
	 *
	 * @param   string  variable name
	 * @param   mixed   default value to return
	 * @return  mixed
	 */
	public function get_once($key, $default = NULL) {
		$value = $this->get($key, $default);
		unset($this->_data[$key]);
		return $value;
	}

	/**
	 * Creates and returns a singleton instance of the class
	 * @return  KMS_Session
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new KMS_Session();
		}
		return self::$_instance;
	}

}
