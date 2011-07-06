<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Privilege Manager for KMS system.
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS_Privilege {

	/**
	 * @var  string  Default user to check privileges against. Generally the logged in user.
	 */
	protected $_default_user;

	/**
	 * @var  Model_User  Loaded user to check privileges against.
	 */
	protected $_user;

	/**
	 * @var  array  Holds the user privileges
	 */
	protected $_users = array();

	/**
	 * Initializes the object and load in logged in user
	 */
	protected function __construct() {
		$user = KMS::Session()->get('user');
		if ($user !== NULL) {
			$this->_default_user = $user;
			$this->user($user->id);
		}
	}

	/**
	 * Creates and returns a new KMS_Content object
	 * @return  KMS_Content
	 */
	public static function factory() {
		return new KMS_Privilege();
	}

	/**
	 * Load a user into the privilege object
	 * @param   int  ID for the user you want to add
	 * @return  KMS_Privilege
	 */
	public function user($id) {
		$user = ORM::factory('user', $id);
		if ($user->loaded()) {
			$this->_user = $user;
			$this->_users[$user->id] = $this->get();
		} else {
			throw new KMS_Exception('The user id (:id:) was not found', array(':id:' => $id));
		}
		return $this;
	}

	/**
	 * Checks if loaded user has privilege to preform action(s)
	 * @param   mixed    Name or array of names to check for
	 * @param   boolean  Resets the user back to the default loaded user
	 * @return  boolean
	 */
	public function has( $action, $reset_user = TRUE ) {
		$allowed = FALSE;

		if ( is_array($action) ) {
			foreach ( $action as $a ) {
				$allowed = $this->has($a, FALSE);
				if ( $allowed === FALSE )
					break; // missing a privilege
			}
		} else if ( !empty($this->_user) && !empty($this->_users[$this->_user->id]) ) {
			$allowed = in_array($action, $this->_users[$this->_user->id]);
		} else { // for all non users and open actions
			$allowed = ORM::factory('action')->where('open', '=', TRUE)->and_where('name', '=', $action)->find()->loaded();
		}

		if ( $reset_user ) {
			$this->_user = $this->_default_user;
		}

		return $allowed;
	}

	/**
	 * Checks if loaded user has any of the passed in privileges
	 * @param   array    Actions to check for
	 * @return  boolean
	 */
	public function has_any( array $actions ) {
		$allowed = FALSE;
		foreach ( $actions as $action ) {
			$allowed = $this->has($action, FALSE);
			if ( $allowed === TRUE )
				break; // found a privilege
		}

		$this->_user = $this->_default_user;
		return $allowed;
	}

	/**
	 * Checks is loaded user is in a group. Pass in an array
	 * of names to check if a user is in any of the groups.
	 * @param   mixed    The name of the group or an array of names
	 * @return  boolean
	 */
	public function in_group( $group_name ) {
		if ( !is_array($group_name) ) {
			$group_name = array($group_name);
		}

		$role = $this->_user->role;
		$role->where_open();
		foreach ($group_name as $r) {
			$role->or_where('name', '=', $r);
		}
		$role->where_close()->find();
		return $role->loaded();
	}

	/**
	 * Returns whether or not the logged in use is a super user
	 * @return  boolean
	 */
	public function is_super() {
		if (empty($this->_user)) return FALSE;
		return (bool) $this->_user->super;
	}

	/**
	 * Gets and returns a list of privileges for loaded user
	 * @return  array
	 */
	public function get() {
		$privileges = array();

		if ($this->_user->super) {
			foreach (ORM::factory('action')->find_all() as $action) {
				$privileges[] = $action->name;
			}
		} else {
			foreach ($this->_user->role->find()->privileges->find_all() as $action) {
				$privileges[] = $action->name;
			}
			foreach ($this->_user->privileges->find_all() as $action) {
				$privileges[] = $action->name;
			}
			foreach (ORM::factory('action')->where('open', '=', TRUE)->find_all() as $action) {
				$privileges[] = $action->name;
			}
		}

		return array_unique($privileges);
	}

	/**
	 * Gets and returns a list of privileges for loaded user and
	 * details how the user has them.
	 * @todo    Clean up database calls
	 * @return  array
	 */
	public function get_details() {
		$privileges = array();

		if ($this->_user->super) {
			foreach (ORM::factory('action')->find_all() as $action) {
				$privileges[$action->name] = 'Super User';
			}
		} else {
			foreach ($this->_user->role->find()->privileges->find_all() as $action) {
				$privileges[$action->name] = 'Role Privilege';
			}
			foreach ($this->_user->privileges->find_all() as $action) {
				$privileges[$action->name] = 'User Privilege';
			}
			foreach (ORM::factory('action')->where('open', '=', TRUE)->find_all() as $action) {
				$privileges[$action->name] = 'Global Privilege';
			}
		}
		ksort($privileges);

		$out = array(); //array('super' => array(), 'global' => array(), 'user' => array(), 'role' => array());
		foreach ($privileges as $name => $key)
			$out[] = (object) array_merge( ORM::factory('action', array('name' => $name))->as_array(), array('type' => $key) );

		return $out;
	}

}
