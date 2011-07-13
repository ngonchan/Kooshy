<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Super Administration controller for KMS system
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_Controller_KMS_SuperAdmin extends Controller_Template {

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
		if ( $this->_user === NULL || !KMS::instance('privilege')->is_super() ) {
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
	 * Super administration overview
	 */
	public function action_index() {
		KMS::stop('Should not be here!');
	}

	/**
	 * Displays overview for Kooshy environment
	 */
	public function action_overview() {
		$this->template->title = 'Kooshy Environment Overview';
		$installed = file_get_contents(arr::get(kohana::modules(), 'kms') . 'config/.kms');
		$installed = strtotime( trim(preg_replace('/^.+?\:\:(.+?)$/', '$1', $installed)) );
		$supers = ORM::factory('user')->where('super', '=', TRUE)->find_all();
		$sites = ORM::factory('site')->find_all();
		$counts = array(
			'content'   => ORM::factory('site_content')->count_all(),
			'chunks'    => ORM::factory('snippet')->where('eval', '=', FALSE)->count_all(),
			'snippets'  => ORM::factory('snippet')->where('eval', '=', TRUE)->count_all(),
			'variables' => ORM::factory('site_variable')->count_all(),
		);
		$activity = ORM::factory('user_action')->order_by('created', 'desc')->limit(10)->find_all();
		$this->template->content = View::factory('kms/super-overview', compact(
			'sites', 'activity', 'installed', 'supers', 'counts'
		));
	}

	/**
	 * Displays overview for Kooshy activity
	 */
	public function action_activity() {
		$this->template->title = 'Kooshy Activity';
		$activity = ORM::factory('user_action')->order_by('created', 'desc')->find_all();
		$this->template->content = View::factory('kms/super-activity', compact('activity'));
	}

	/**
	 * Loads site pages
	 */
	public function action_sites() {
		switch (Request::$current->param('section')) {
			case 'overview':
				$site = ORM::factory('site')->find(Request::$current->param('id'));
				if (!$site->loaded()) KMS::stop('The requested site was not found');
				$counts = (object) array(
					'content'   => $site->content->count_all(),
					'variables' => $site->variables->count_all(),
					'chunks'    => $site->snippets->where('eval', '=', FALSE)->count_all(),
					'snippets'  => $site->snippets->where('eval', '=', TRUE)->count_all(),
				);
				$template = $site->templates->find( $site->site_templates->where('enabled', '=', TRUE)->find()->template_id )->name;
				$activity = $site->user_actions->order_by('created', 'desc')->limit(25)->find_all();
				$chunks = ORM::factory('snippet')->where('eval', '=', FALSE)->find_all();
				$snippets = ORM::factory('snippet')->where('eval', '=', TRUE)->find_all();
				$this->template->title = $site->description;
				$this->template->content = View::factory('kms/super-site-overview', compact('site', 'counts', 'template', 'activity', 'chunks', 'snippets'));
				break;
			case 'add':
				$site = array();
				if (KMS::Session()->path('ua.status') === 'failed') {
					$site = KMS::Session()->path('ua.fields');
				}
				$this->template->title = 'New Site';
				$this->template->content = View::factory('kms/super-site-add', compact('site'));
				break;
			case 'edit':
				$this->template->title = 'Editing Site';
				$site = ORM::factory('site')->find(Request::$current->param('id'));
				if (!$site->loaded()) KMS::stop('The requested site was not found');
				if (KMS::Session()->path('ua.status') === 'failed') {
					$site->values(KMS::Session()->path('ua.fields'));
				}
				$site = $site->as_array();
				$this->template->content = View::factory('kms/super-site-edit', compact('site'));
				break;
			case 'delete':
				$site = ORM::factory('site')->find(Request::$current->param('id'));
				if (!$site->loaded()) KMS::stop('The requested site was not found');
				$this->template->title = 'Delete Site';
				$this->template->content = View::factory('kms/super-site-delete', compact('site'));
				break;
			default:
				Request::$current->redirect( Route::url('kms-superadmin', array('action' => 'overview')) );
		}
	}


	/**
	 * Loads template pages
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
	 */

}
