<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Administration controller for KMS system
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_Controller_KMS_Admin extends Controller_Template {

	/**
	 * @var  string  template view to load
	 */
	public $template = 'kms/admin';

	/**
	 * @var  KMS_Site  site object
	 */
	protected $_site;

	/**
	 * @var  array  user data for logged in user
	 */
	protected $_user;

	/**
	 * Sets up the template and loads the site
	 */
	public function before() {
		parent::before();
		$this->_site = KMS::instance('site');
		$this->_user = KMS::Session()->get('user');
		if ($this->_user === NULL && Request::$current->action != 'login') {
			Request::$current->redirect( Route::url('kms-admin', array('action' => 'login')) );
		}

		$this->template->site = KMS::instance('site');
		$this->template->title = ucwords(strtolower(Request::instance()->action));
		$this->template->sidebar = Request::factory('kms-admin/sidebar')->execute()->response;
	}

	/**
	 * Updates page title and finializes the site template
	 */
	public function after() {
		parent::after();
		$this->template->title = (empty($this->template->title)?'': $this->template->title . ' : ') . 'Kooshy (KMS)';
	}

	/**
	 * Default action which should not be called
	 */
	public function action_index() {
		// catch all for actions
		throw new KMS_Exception('Should not be here!');
	}

	/**
	 * Loads the login page
	 */
	public function action_login() {
		if (KMS::Session()->get('user') !== NULL) {
			Request::$current->redirect( Route::url('kms-admin', array('action' => 'dashboard')) );
		}
		$this->template = View::factory('kms/login', array(
			'ua' => KMS::Session()->path('ua.login', array())
		));
	}

	public function action_admin() {
		switch (Request::$current->param('section')) {
			case 'users':
				$users = KMS::instance('site')->users->find_all();
				$this->template->content = View::factory('kms/admin-users', compact('users'));
				break;
			case 'user-edit':
				$this->template->title = 'Editing User';
				$user = KMS::instance('site')->users->find(Request::$current->param('id'));
				$user->password = '';
				if (!$user->loaded()) KMS::stop( 'Unable to load user' );
				if (KMS::Session()->path('ua.status') === 'failed') {
					$user->values(KMS::Session()->path('ua.fields'));
				}
				$roles = array();
				$role_orm = ORM::factory('role')
					->where('site_id', '=', $this->template->site->id)
					->or_where('site_id', 'IS', NULL)
					->order_by('name')->find_all();
				foreach ($role_orm as $role) {
					$roles[$role->id] = $role->name;
				}
				$role = $user->role->find()->as_array();
				$user = $user->as_array();
				$user['role'] = $role;
				$this->template->content = View::factory('kms/admin-users-edit', compact('user', 'roles'));
				break;
			case 'user-delete':
				die('@TODO');
				break;
			default:
				Request::$current->redirect( Route::url('kms-admin', array('action'=>'admin', 'section'=>'users')) );
		}
	}

	/**
	 * Loads the content pages
	 */
	public function action_content() {
		switch (Request::$current->param('section')) {
			case 'overview':
				$content = KMS::instance('site')->content->find_all();
				$this->template->content = View::factory('kms/content-overview', compact('content'));
				break;
			case 'add':
				$this->template->title = 'Adding Content';
				$content = array();
				if (KMS::Session()->path('ua.status') === 'failed') {
					$content = KMS::Session()->path('ua.fields');
				}
				$this->template->content = View::factory('kms/content-add', compact('content'));
				break;
			case 'edit':
				$this->template->title = 'Editing Content';
				$content = KMS::instance('site')->content->where('id', '=', Request::$current->param('id'))->find();
				if (!$content->loaded()) KMS::stop('The requested content was not found');
				if (KMS::Session()->path('ua.status') === 'failed') {
					$content->values(KMS::Session()->path('ua.fields'));
				}
				$content = $content->as_array();
				$this->template->content = View::factory('kms/content-edit', compact('content'));
				break;
			case 'delete':
				$content = KMS::instance('site')->content->where('id', '=', Request::$current->param('id'))->find();
				if (!$content->loaded()) KMS::stop('Unable to load content!');
				$content = $content->as_array();
				$this->template->title = 'Delete Content';
				$this->template->content = View::factory('kms/content-delete', compact('content'));
				break;
			default:
				Request::$current->redirect( Route::url('kms-admin', array('action'=>'content', 'section'=>'overview')) );
		}
	}

	/**
	 * Loads the dashboard
	 */
	public function action_dashboard() {
		$site = KMS::instance('site');
		$counts = (object) array(
			'content'   => $site->content->count_all(),
			'variables' => $site->variables->count_all(),
			'chunks'    => $site->snippets->where('eval', '=', FALSE)->where('site_snippets.enabled', '=', TRUE)->count_all(),
			'snippets'  => $site->snippets->where('eval', '=', TRUE)->where('site_snippets.enabled', '=', TRUE)->count_all(),
		);
		$template = $site->templates->find( $site->site_templates->where('enabled', '=', TRUE)->find()->template_id )->name;
		$activity = $site->user_actions->order_by('created', 'desc')->limit(10);

		$view = 'kms/dashboard';
		if ( KMS::instance('privilege')->in_group('user') ) {
			$view .= '-user';
			$activity->where('user_id', '=', $this->_user->id);
		}

		$activity = $activity->find_all();
		$this->template->content = View::factory($view, compact('site', 'counts', 'template', 'activity'));
	}

	/**
	 * Loads the list pages
	 */
	public function action_lists() {
		$site_list = KMS::instance('site')->lists;
		if (Request::$current->param('id') !== NULL) {
			$site_list = $site_list->find(Request::$current->param('id'));
			if (!$site_list->loaded()) KMS::stop ('Unable to load site list');
			$list = ORM::factory('list')->load($site_list->name);
			$columns = $list->columns();
		}
		if (Request::$current->param('subid') !== NULL && $site_list->loaded()) {
			$list = $list->find(Request::$current->param('subid'));
			if (!$list->loaded()) KMS::stop ('Unable to load list entry');
			if (KMS::Session()->path('ua.status') === 'failed') {
				$list->values(KMS::Session()->path('ua.fields'));
			}
		}

		switch (Request::$current->param('section')) {
			case 'overview':
				$this->template->title = 'Site Lists';
				$lists = KMS::instance('site')->lists->find_all();
				$this->template->content = View::factory('kms/lists', compact('lists'));
				break;
			case 'add':
				if (KMS::Session()->path('ua.status') === 'failed') {
					$site_list->values(KMS::Session()->path('ua.fields'));
				}
				$column_types = array(
					'Number' => array(
						'integer' => 'Integer < 11 digits',
						'decimal' => 'Decimal 13 digits . 2 digits'
					),
					'Text' => array(
						'text' => 'for less than 255 chars',
						'long' => 'long without WYSIWYG',
						'long-wysiwyg' => 'long with WYSIWYG'
					)
				);
				$this->template->title = 'Site List Create';
				$this->template->content = View::factory('kms/lists-add', compact('site_list', 'column_types'));
				break;
			case 'delete':
				$this->template->title = 'Site List Delete';
				$this->template->title = $site_list->name . ' List Delete';
				$this->template->content = View::factory('kms/lists-delete', compact('site_list'));
				break;
			case 'view':
				$list = $list->find_all();
				$this->template->title = $site_list->name . ' List';
				$this->template->content = View::factory('kms/lists-view', compact('list', 'site_list', 'columns'));
				break;
			case 'list-edit':
				$this->template->title = $site_list->name . ' List - Editing Item ' . $list->id;
				$this->template->content = View::factory('kms/lists-view-edit', compact('list', 'site_list', 'columns'));
				break;
			case 'list-insert':
				if (KMS::Session()->path('ua.status') === 'failed') {
					$list->values(KMS::Session()->path('ua.fields'));
				}
				$this->template->title = $site_list->name . ' List - Creating Item';
				$this->template->content = View::factory('kms/lists-view-add', compact('list', 'site_list', 'columns'));
				break;
			case 'list-remove':
				$this->template->title = $site_list->name . ' List - Removing Item ' . $list->id;
				$this->template->content = View::factory('kms/lists-view-delete', compact('list', 'site_list'));
				break;
			default:
				Request::$current->redirect( Route::url('kms-admin', array('action'=>'lists', 'section'=>'overview')) );
		}
	}

	/**
	 * Loads the profile pages
	 */
	public function action_profile() {
		switch (Request::$current->param('section')) {
			case 'overview':
				$this->template->title = 'User Profile';
				$profile = (object) $this->_user;
				$roles = ORM::factory('site_user')
					->where('user_id', '=', $profile->id)
					->order_by( DB::expr("find_in_set(site_id, '" . KMS::instance('site')->id . "') DESC") )
					->find_all();
				$site_access = KMS::instance('privilege')->get_details();
				$this->template->content = View::factory('kms/profile-overview', compact('profile', 'roles', 'site_access'));
				break;
			case 'edit':
				$this->template->title = 'Editing Profile';
				$profile = KMS::instance('site')->users->where('user_id', '=', $this->_user->id)->find();
				if (!$profile->loaded()) KMS::stop('The requested profile was not found');
				$profile->password = '';
				if (KMS::Session()->path('ua.status') === 'failed') {
					$profile->values(KMS::Session()->path('ua.fields'));
				}
				$profile = $profile->as_array();
				$this->template->content = View::factory('kms/profile-edit', compact('profile'));
				break;
			default:
				Request::$current->redirect( Route::url('kms-admin', array('action'=>'profile', 'section'=>'overview')) );
		}
	}

	/**
	 * Loads the resource pages
	 */
	public function action_resources() {
		switch (Request::$current->param('section')) {
			case 'chunks':
				$this->template->title = 'Site Chunks';
				$resources = KMS::instance('site')->snippets->where('eval', '=', FALSE)->find_all();
				$this->template->content = View::factory('kms/resources-chunks', compact('resources'));
				break;
			case 'snippets':
				$this->template->title = 'Site Snippets';
				$resources = KMS::instance('site')->snippets->where('eval', '=', TRUE)->find_all();
				$this->template->content = View::factory('kms/resources-snippets', compact('resources'));
				break;
			case 'view': //read-only view for snippets and chunks
				$this->template->title = 'Site Resource';
				$resource = KMS::instance('site')->snippets->where('id', '=', Request::$current->param('id'))->find();
				if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );
				$resource = $resource->as_array();
				$this->template->content = View::factory('kms/resources-view', compact('resource'));
				break;
			case 'delete-variable':
				$this->template->title = 'Delete Variable';
				$resource = KMS::instance('site')->variables->find(Request::$current->param('id'));
				if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );
				$resource = $resource->as_array();
				$this->template->content = View::factory('kms/resources-variable-delete', compact('resource'));
				break;
			case 'variables':
				$id = Request::$current->param('id');
				if ($id == 'new') {
					$this->template->title = 'New Site Variable';
					$resource = array();
					if (KMS::Session()->path('ua.status') === 'failed') {
						$resource = KMS::Session()->path('ua.fields');
					}
					$this->template->content = View::factory('kms/resources-variable-new', compact('resource'));
				} else if ($id === NULL) {
					$this->template->title = 'Editing Site Variable';
					$resources = KMS::instance('site')->variables->find_all();
					$this->template->content = View::factory('kms/resources-variables', compact('resources'));
				} else {
					$this->template->title = 'Site Variables';
					$resource = KMS::instance('site')->variables->find($id);
					if (!$resource->loaded()) KMS::stop ( 'Unable to load resource' );

					if (KMS::Session()->path('ua.status') === 'failed') {
						$resource->values(KMS::Session()->path('ua.fields'));
					}
					$resource = $resource->as_array();
					$this->template->content = View::factory('kms/resources-variable-edit', compact('resource'));
				}
				break;
			default:
				throw new KMS_Exception('Unknown section specified: :section:', array(':section:' => Request::$current->param('section')));
		}
	}

	/**
	 * Loads template pages
	 */
	public function action_templates() {
		switch (Request::$current->param('section')) {
			case 'overview':
				$this->template->title = 'Site Templates';
				$template = KMS::instance('site')->templates->find_all();
				$this->template->content = View::factory('kms/template-overview', compact('template'));
				break;
			case 'add':
				$this->template->title = 'Adding Template';
				$template = array();
				if (KMS::Session()->path('ua.status') === 'failed') {
					$template = KMS::Session()->path('ua.fields');
				}
				$this->template->content = View::factory('kms/template-add', compact('template'));
				break;
			case 'edit':
				$this->template->title = 'Editing Template';
				$template = KMS::instance('site')->templates->find(Request::$current->param('id'));
				if (!$template->loaded()) KMS::stop('The requested template was not found');
				if (KMS::Session()->path('ua.status') === 'failed') {
					$template->values(KMS::Session()->path('ua.fields'));
				}
				$template = $template->as_array();
				$this->template->content = View::factory('kms/template-edit', compact('template'));
				break;
			case 'delete':
				$template = KMS::instance('site')->templates->find(Request::$current->param('id'));
				if (!$template->loaded()) KMS::stop('The requested template was not found');
				$template = $template->as_array();
				$this->template->title = 'Delete Template';
				$this->template->content = View::factory('kms/template-delete', compact('template'));
				break;
			default:
				Request::$current->redirect( Route::url('kms-admin', array('action'=>'templates', 'section'=>'overview')) );
		}
	}

}
