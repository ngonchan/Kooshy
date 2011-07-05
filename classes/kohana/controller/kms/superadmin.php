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
		if ($this->_user === NULL) {
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
		$this->template->title = 'Super Admin Dashboard';
		$this->template->content = '@TODO';
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
