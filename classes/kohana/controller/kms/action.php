<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin data processing controller for the KMS system
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_Controller_KMS_Action extends Controller {

	/**
	 * @var  string  name of action being preformed
	 */
	protected $_action;

	/**
	 * @var  array  data to process
	 */
	protected $_data;

	/**
	 * @var  string  action details to store in the actions database
	 */
	protected $_details = '';

	/**
	 * @var  string  action identifier to identify which database table was altered
	 */
	protected $_identifier = '';

	/**
	 * @var  string  uri to redirect to after completion
	 */
	protected $_redirect;

	/**
	 * @var  array  data to store in the `ua` session key
	 */
	protected $_session;

	/**
	 * @var  boolean  stores the action in the database if successful
	 */
	protected $_store = TRUE;

	/**
	 * @var  array  user information for who is processing an action
	 */
	protected $_user;

	/**
	 * Sets up initial object parameters for processing the action
	 */
	public function before() {
		if (Request::$current->action == 'cleanup') return;
		$this->_action = ORM::factory('action', array('name' => Request::$current->action));
		if (!$this->_action->loaded()) throw new KMS_Exception('Unknow action `:action:`', array(':action:' => Request::$current->action));

		$this->_data = Security::xss_clean(arr::get($GLOBALS, '_' . arr::get($_SERVER, 'REQUEST_METHOD')));
		$this->_data['site_id'] = KMS::instance('site')->id; // force current site
		$this->_user = KMS::Session()->get('user');

		// check for privilege
		if (!KMS::instance('privilege')->has($this->_action->name)) {
			KMS::stop('You do not have a high enough level of permission to preform this action!');
		}

		$this->_redirect = Route::url('kms-admin', array('action' => 'login'));

		// default session values
		$this->_session = array(
			'status'      => NULL,
			'last_action' => NULL,
			'expires'     => NULL,
			'message'     => array(
				'class' => 'success',
				'text'  => NULL,
			),
			'fields'      => array(),
		);
	}

	/**
	 * Stores action in the database and redirects user
	 * @param  boolean  redirects to _redirect variable if true
	 */
	public function after($redirect = TRUE) {
		if (Request::$current->action == 'cleanup') return;

		// update UA and User sessions
		$this->_session['last_action'] = $this->_action->name;
		$this->_session['fields'] = $this->_data;
		$this->_session['expires'] = time() + 5;
		$this->_session['status'] = $this->_store ? 'success' : 'failed';
		KMS::Session()->set('ua', $this->_session);
		KMS::Session()->set('user', $this->_user);

		if ($this->_store) {
			$store = ORM::factory('user_action');
			$store->site_id = KMS::instance('site')->id;
			$store->user_id = $this->_user->id;
			$store->action_id = $this->_action->id;
			$store->details = $this->_details;
			$store->identifier = $this->_identifier;
			$store->save();
		}
		if ($redirect) Request::$current->redirect( $this->_redirect );
	}

	/**
	 * Updates message for `ua` session information
	 * @param  string  text to store in the session
	 * @param  string  class for the message (success, error, attention, information)
	 */
	protected function _message($text, $class = 'success') {
		$this->_session['message'] = array(
			'class' => $class,
			'text'  => $text,
		);
	}

	/**
	 * Removes the `ua` session after it expires
	 */
	public function action_cleanup() {
		$ua = KMS::Session()->get('ua');
		if ($ua === NULL) return;
		if (empty($ua['expires']) || $ua['expires'] < time()) {
			KMS::Session()->delete('ua');
		}
	}

	/**
	 * Logs a user into KMS system
	 */
	public function action_login() {
		$user = KMS::instance('site')->users
			->where('username', '=', arr::get($this->_data, 'username'))
			->where('password', '=', sha1(arr::get($this->_data, 'password')))
			->where('active', '=', TRUE)
			->find();
		if (!$user->loaded()) {
			$user = ORM::factory('user', array(
				'username' => arr::get($this->_data, 'username'),
				'password' => sha1(arr::get($this->_data, 'password')),
				'active'   => TRUE,
				'super'    => TRUE
			));
		}

		if (!$user->loaded()) {
			$this->_message('Invalid username or password', 'error');
			$this->_store = FALSE;
		} else {
			$this->_user = $user;
			$this->_details = "User account `{$this->_user->username}` logged in";
			$this->_identifier = "users.{$this->_user->id}";
		}
	}

	/**
	 * Logs a user out of KMS system
	 */
	public function action_logout() {
		$this->_details = "User account `{$this->_user->username}` logged out";
		$this->_identifier = "users.{$this->_user->id}";
		$this->after(FALSE);
		KMS::Session()->destroy();
		Request::$current->redirect( $this->_redirect );
	}

	/**
	 * Adds content to site
	 */
	public function action_content_add() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'content', 'section' => 'add'));
		$this->_data['site_id'] = KMS::instance('site')->id;
		$content = KMS::instance('site')->content;
		$content->values($this->_data);
		$this->_store = $content->check();
		if ($this->_store) {
			try {
				$content->save();
				$this->_message("The content <strong>{$content->title}</strong> was successfully created");
				$this->_details = "The content `{$content->title}` was created";
				$this->_identifier = "site_contents.{$content->id}";
				$this->_redirect = Route::url('kms-admin', array('action' => 'content'));
			} catch (Exception $e) {
				$this->_message('The URI (' . arr::get($this->_data, 'uri') . ') is already in use. Please enter a unique URI.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($content->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Edits content on a site
	 */
	public function action_content_edit() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'content', 'section' => 'edit', 'id' => arr::get($this->_data, 'id')));
		$this->_data['site_id'] = KMS::instance('site')->id;
		$content = KMS::instance('site')->content->find(arr::get($this->_data, 'id'));
		if (!$content->loaded()) KMS::stop('Unable to load content for editing!');
		unset($this->_data['id']); // do not update primary key
		$content->values($this->_data);
		$this->_store = $content->check();
		if ($this->_store) {
			try {
				$content->save();
				$this->_message("The content <strong>{$content->title}</strong> was successfully updated");
				$this->_details = "The content `{$content->title}` was updated";
				$this->_identifier = "site_contents.{$content->id}";
			} catch (Exception $e) {
				$this->_message('The URI (' . arr::get($this->_data, 'uri') . ') is already in use. Please enter a unique URI.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($content->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Deletes content on a site
	 */
	public function action_content_delete() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'content', 'section' => 'overview'));
		$content = KMS::instance('site')->content->find(arr::get($this->_data, 'id'));
		$this->_message("The content <strong>{$content->title}</strong> was successfully deleted");
		$this->_details = "The content `{$content->title}` was deleted";
		$this->_identifier = "site_contents.{$content->id}";
		$content->delete();
	}

	/**
	 * Creates a list on a site
	 */
	public function action_list_add() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'overview'));
		$this->_data['records'] = 0; //set default record count

		$site_list = KMS::instance('site')->lists;
		$site_list->values($this->_data);
		$table_name = 'list_' . $this->_data['site_id'] . '_' . $this->_data['name'];

		// verify that there are columns
		foreach ($this->_data['column_name'] as $key => $value) {
			if ( empty($this->_data['column_name'][$key]) || empty($this->_data['column_type'][$key]) ) {
				unset( $this->_data['column_name'][$key], $this->_data['column_type'][$key] );
			}
		}

		try {
			if (empty($this->_data['column_name'])) throw new KMS_Exception('No columns were specified');
			$site_list->save();
			$list = ORM::factory('list')->create($table_name, $this->_data['column_name'], $this->_data['column_type']);
			$this->_message("The site list <strong>{$site_list->name}</strong> was successfully created");
			$this->_details = "The site list `{$site_list->id}` was created";
			$this->_identifier = "site_lists.{$site_list->id}";
		} catch (Exception $e) {
			$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'add'));
			$this->_message('There were errors that occurred when attempting to create the list.<br /><br />' . $e->getMessage(), 'error');
			$this->_store = FALSE;
		}
	}

	/**
	 * Deletes a list from a site
	 */
	public function action_list_delete() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'overview'));

		$site_list = KMS::instance('site')->lists->find(arr::get($this->_data, 'site_list'));
		$list = ORM::factory('list')->load($site_list->name);

		try {
			$list->drop();
			$site_list->delete();
			$this->_message("The site list <strong>{$site_list->name}</strong> was successfully deleted");
			$this->_details = "The site list `{$site_list->id}` was deleted";
			$this->_identifier = "site_lists.{$site_list->id}";
		} catch (Exception $e) {
			$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'view', 'id' => $site_list->id));
			$this->_message('There were errors that occurred when attempting to delete the list.<br /><br />' . $e->getMessage(), 'error');
			$this->_store = FALSE;
		}
	}

	/**
	 * Deletes an item in a site list
	 */
	public function action_list_view_delete() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'view', 'id' => arr::get($this->_data, 'site_list')));

		$site_list = KMS::instance('site')->lists->find(arr::get($this->_data, 'site_list'));
		$list = ORM::factory('list', $this->_data['id'])->load($site_list->name);
		if (!$list->loaded()) KMS::stop('Unable to load list entry');

		try {
			$site_list->records = $site_list->records - 1;
			$site_list->save();
			$list->delete();
			$this->_message("The list entry <strong>{$list->id}</strong> was successfully removed");
			$this->_details = "The list entry `{$list->id}` was deleted";
			$this->_identifier = "{$list->table_name()}.{$list->id}";
		} catch (Exception $e) {
			$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'list-edit', 'id' => $site_list->id, 'subid' => $list->id));
			$this->_message('There were errors that occurred when attempting to delete the list entry.<br /><br />' . $e->getMessage(), 'error');
			$this->_store = FALSE;
		}
	}

	/**
	 * Adds an item in a site list
	 */
	public function action_list_view_add() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'view', 'id' => arr::get($this->_data, 'site_list')));

		$site_list = KMS::instance('site')->lists->find(arr::get($this->_data, 'site_list'));
		$list = ORM::factory('list')->load($site_list->name);
		unset($this->_data['site_list'], $this->_data['site_id']);
		$list->values($this->_data);

		try {
			$site_list->records = $site_list->records + 1;
			$site_list->save();
			$list->save();
			$this->_message("The list entry <strong>{$list->id}</strong> was successfully created");
			$this->_details = "The list entry `{$list->id}` was created";
			$this->_identifier = "{$list->table_name()}.{$list->id}";
		} catch (Exception $e) {
			$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'list-edit', 'id' => $site_list->id, 'subid' => $list->id));
			$this->_message('There were errors that occurred when attempting to create the list entry.<br /><br />' . $e->getMessage(), 'error');
			$this->_store = FALSE;
		}
	}

	/**
	 * Edits an item in a site list
	 */
	public function action_list_view_edit() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'view', 'id' => arr::get($this->_data, 'site_list')));

		$site_list = KMS::instance('site')->lists->find(arr::get($this->_data, 'site_list'));
		$list = ORM::factory('list', $this->_data['id'])->load($site_list->name);
		if (!$list->loaded()) KMS::stop('Unable to load list entry');
		unset($this->_data['id'], $this->_data['site_list'], $this->_data['site_id']);
		$list->values($this->_data);

		try {
			$list->save();
			$this->_message("The list entry <strong>{$list->id}</strong> was successfully updated");
			$this->_details = "The list entry `{$list->id}` was updated";
			$this->_identifier = "{$list->table_name()}.{$list->id}";
		} catch (Exception $e) {
			$this->_redirect = Route::url('kms-admin', array('action' => 'lists', 'section' => 'list-edit', 'id' => $site_list->id, 'subid' => $list->id));
			$this->_message('There were errors that occurred when attempting to save the list entry.<br /><br />' . $e->getMessage(), 'error');
			$this->_store = FALSE;
		}
	}

	/**
	 * Edits a user profile
	 */
	public function action_profile_edit() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'profile', 'section' => 'edit'));
		$profile = KMS::instance('site')->users->where('user_id', '=', $this->_user->id)->find();
		if (!$profile->loaded()) KMS::stop ( 'Unable to load profile id ' . $this->_user->id, $profile );
		if (empty($this->_data['password'])) {
			$this->_data['password_confirm'] = $profile->password;
			unset($this->_data['password']);
		}
		if ($this->_data['username'] == $profile->username) unset($this->_data['username']);
		$profile->values($this->_data);

		//die(kohana::debug( $this->_data, $profile ));
		$this->_store = $profile->check();
		if ($this->_store) {
			try {
				if (isset($this->_data['password'])) $profile->password = sha1($this->_data['password']);
				$profile->save();
				$this->_user = $profile;
				$this->_message("Your profile was successfully updated");
				$this->_details = "The profile `{$profile->id}` was updated";
				$this->_identifier = "users.{$profile->id}";
				$this->_redirect = Route::url('kms-admin', array('action' => 'profile'));
			} catch (Exception $e) {
				$this->_message('There were errors that occurred when attempting to save your profile.<br /><br />' . $e->getMessage(), 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($profile->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
		if (!isset($this->_data['password'])) unset($this->_data['password_confirm']);
	}

	/**
	 * Enables a site resource
	 * @param  int  id of resource to enable
	 */
	public function action_resource_enable( $id ) {
		$resource = KMS::instance('site')->site_snippets->where('snippet_id', '=', $id)->find();
		if (!$resource->loaded()) KMS::stop ( 'Unable to load resource id ' . $id, $resource );
		$resource->enabled = TRUE;
		$resource->save();

		$snippet = ORM::factory('snippet', $id);
		$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => ( $snippet->eval ? 'snippets' : 'chunks' )));
		$this->_message("The resource <strong>{$snippet->code}</strong> was successfully enabled");
		$this->_details = "The " . ($snippet->eval ? 'snippet' : 'chunk') . " `{$snippet->code}` was enabled";
		$this->_identifier = "snippets.{$snippet->id}";
	}

	/**
	 * Disables a site resource
	 * @param  int  id of resource to disable
	 */
	public function action_resource_disable( $id ) {
		$resource = KMS::instance('site')->site_snippets->where('snippet_id', '=', $id)->find();
		if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );
		$resource->enabled = FALSE;
		$resource->save();

		$snippet = ORM::factory('snippet', $id);
		$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => ( $snippet->eval ? 'snippets' : 'chunks' )));
		$this->_message("The resource <strong>{$snippet->code}</strong> was successfully disabled");
		$this->_details = "The " . ($snippet->eval ? 'snippet' : 'chunk') . " `{$snippet->code}` was disabled";
		$this->_identifier = "snippets.{$snippet->id}";
	}

	/**
	 * Activates a site template
	 * @param  int  id of the template to activate
	 */
	public function action_template_activate( $id ) {
		$template = KMS::instance('site')->site_templates;
		$template->enabled = 0;
		$template->save_all();

		$template = $template->find($id);
		$template->enabled = 1;
		$template->save();

		$template = KMS::instance('site')->templates->find($id);
		$this->_redirect = Route::url('kms-admin', array('action' => 'templates'));
		$this->_message("The template <strong>{$template->name}</strong> was successfully activated");
		$this->_details = "The template `{$template->name}` was activated";
		$this->_identifier = "templates.{$template->id}";
	}

	/**
	 * Removes a template from a site
	 */
	public function action_template_delete() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'templates'));
		$template = KMS::instance('site')->site_templates->delete(arr::get($this->_data, 'id'));

		$template = KMS::instance('site')->templates->find(arr::get($this->_data, 'id'));
		$this->_message("The template <strong>{$template->name}</strong> was successfully deleted");
		$this->_details = "The template `{$template->name}` was deleted";
		$this->_identifier = "templates.{$template->id}";
	}

	/**
	 * Creates a new template and assigns it to site
	 */
	public function action_template_add() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'templates', 'section' => 'add'));

		$template = KMS::instance('site')->templates;
		$template->values($this->_data);
		$this->_store = $template->check();
		if ($this->_store) {
			try {
				$template->save();
				$this->_message("The template <strong>{$template->name}</strong> was successfully created");
				$this->_details = "The template `{$template->name}` was created";
				$this->_identifier = "templates.{$template->id}";
				$site_tempaltes = ORM::factory('site_template');
				$site_tempaltes->site_id = KMS::instance('site')->id;
				$site_tempaltes->template_id = $template->id;
				$site_tempaltes->save();
				$this->_redirect = Route::url('kms-admin', array('action' => 'templates', 'section' => 'overview'));
			} catch (Exception $e) {
				$this->_message('The template name (' . arr::get($this->_data, 'name') . ') is already in use. Please enter a unique template name.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($template->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Edits a template
	 */
	public function action_template_edit() {
		$id = arr::get($this->_data, 'id');
		unset($this->_data['id']); // do not update primary key
		$this->_redirect = Route::url('kms-admin', array('action' => 'templates', 'section' => 'edit', 'id' => $id));

		$template = KMS::instance('site')->templates->find($id);
		if (!$template->loaded()) KMS::stop('Unable to load template for editing');
		$template->values($this->_data);
		$this->_store = $template->check();
		if ($this->_store) {
			try {
				$template->save();
				$this->_message("The template <strong>{$template->name}</strong> was successfully updated");
				$this->_details = "The template `{$template->name}` was updated";
				$this->_identifier = "templates.{$template->id}";
			} catch (Exception $e) {
				$this->_message('The template name (' . arr::get($this->_data, 'name') . ') is already in use. Please enter a unique template name.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($template->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Edits a user account
	 */
	public function action_user_edit() {
		//die(kohana::debug($this->_data));
		$id = arr::get($this->_data, 'id');
		unset($this->_data['id']); // do not update primary key
		$this->_redirect = Route::url('kms-admin', array('action' => 'admin', 'section' => 'users'));

		$user = KMS::instance('site')->users->find($id);
		if (!$user->loaded()) KMS::stop('Unable to load user for editing');
		if (empty($this->_data['password'])) {
			$this->_data['password_confirm'] = $profile->password;
			unset($this->_data['password']);
		}
		if ($this->_data['username'] == $user->username) unset($this->_data['username']);
		$user->values($this->_data);

		$role = DB::update('site_users')
			->set(array('role_id' => $this->_data['role']))
			->where('site_id', '=', $this->_data['site_id'])
			->where('user_id', '=', $id);

		$this->_store = $user->check();
		if ($this->_store) {
			try {
				$role->execute(KMS_DATABASE);
				if (isset($this->_data['password'])) $user->password = sha1($this->_data['password']);
				$user->save();
				$this->_message("The user <strong>{$user->username}</strong> was successfully updated");
				$this->_details = "The user `{$user->username}` was updated";
				$this->_identifier = "users.{$user->id}";
			} catch (Exception $e) {
				$this->_message('An error occured when trying to update the database.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$this->_redirect = Route::url('kms-admin', array('action' => 'admin', 'section' => 'user-edit', 'id' => $id));
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($user->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Deletes a user account
	 */
	public function action_user_delete() {
		$this->_store = TRUE;
		$this->_redirect = Route::url('kms-admin', array('action' => 'admin', 'section' => 'users'));

		$user = KMS::instance('site')->users->find($this->_data['id']);
		if (!$user->loaded()) KMS::stop('Unable to load user to delete');
		$sites = ORM::factory('site_user')->where('user_id', '=', $this->_data['id'])->find_all();

		if ($sites->count() > 1) {
			try {
				DB::delete('site_users')
					->where('user_id', '=', $this->_data['id'])
					->where('site_id', '=', $this->_data['site_id'])
					->execute(KMS_DATABASE);
				$this->_message("The user <strong>{$user->username}</strong> was successfully removed from the site");
				$this->_details = "The user `{$user->username}` was removed from the site";
				$this->_identifier = "users.{$user->id}";
			} catch (Exception $e) {
				die(kohana::debug($e));
				$this->_message('An error occured when trying to update the database. [1]', 'error');
				$this->_store = FALSE;
			}
		} else {
			try {
				DB::delete('site_users')
					->where('user_id', '=', $this->_data['id'])
					->execute(KMS_DATABASE);
				$user->active = FALSE;
				$user->save();
				$this->_message("The user <strong>{$user->username}</strong> was successfully deleted");
				$this->_details = "The user `{$user->username}` was deleted";
				$this->_identifier = "users.{$user->id}";
			} catch (Exception $e) {
				$this->_message('An error occured when trying to update the database. [2]', 'error');
				$this->_store = FALSE;
			}
		}
	}

	/**
	 * Creates a new site variable
	 */
	public function action_variable_create() {
		$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => 'variables', 'id' => 'new'));
		$resource = KMS::instance('site')->variables;
		$resource->values($this->_data);
		$this->_store = $resource->check();
		if ($this->_store) {
			try {
				$resource->save();
				$this->_message("The site variable <strong>{$resource->name}</strong> was successfully created");
				$this->_details = "The variable `{$resource->name}` was created";
				$this->_identifier = "site_variables.{$resource->id}";
				$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => 'variables'));
			} catch (Exception $e) {
				$this->_message('The variable name (' . arr::get($this->_data, 'name') . ') is already in use. Please enter a unique variable name.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($resource->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

	/**
	 * Deletes a site variable
	 */
	public function action_variable_delete() {
		$resource = KMS::instance('site')->variables->find($this->_data['id']);
		if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );
		$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => 'variables'));
		$this->_message("The site variable <strong>{$resource->name}</strong> was successfully deleted");
		$this->_details = "The variable `{$resource->name}` was deleted";
		$this->_identifier = "site_variables.{$resource->id}";
		$resource->delete();
	}

	/**
	 * Edits a site variable
	 */
	public function action_variable_edit () {
		$id = arr::get($this->_data, 'id');
		unset($this->_data['id']);
		$this->_redirect = Route::url('kms-admin', array('action' => 'resources', 'section' => 'variables', 'id' => $id));
		$resource = KMS::instance('site')->variables->find($id);
		if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );
		$resource->values($this->_data);
		$this->_store = $resource->check();
		if ($this->_store) {
			try {
				$resource->save();
				$this->_message("The site variable <strong>{$resource->name}</strong> was successfully updated");
				$this->_details = "The variable `{$resource->name}` was edited";
				$this->_identifier = "site_variables.{$resource->id}";
			} catch (Exception $e) {
				$this->_message('The variable name (' . arr::get($this->_data, 'name') . ') is already in use. Please enter a unique variable name.', 'error');
				$this->_store = FALSE;
			}
		} else {
			$msg = 'ERROR: The following errors occurred:<br />';
			foreach ($resource->validate()->errors('validate') as $error)
				$msg .= ' - ' . ucfirst( $error ) . '<br />';
			$this->_message($msg, 'error');
		}
	}

}