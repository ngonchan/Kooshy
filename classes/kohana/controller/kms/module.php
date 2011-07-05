<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Module controller for KMS system. Loaded via HMVC request.
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_Controller_KMS_Module extends Controller {

	/**
	 * @var  string  action of the host call
	 */
	public $action;

	/**
	 * @var  array  sidebar menu data
	 */
	protected $_menu;

	/**
	 * Sets up module actions
	 * @param  Request  Request that created the controller
	 */
	public function __construct( $request ) {

		// default menu items
		$this->_menu[10] = array('title' => 'Dashboard', 'params' => array('action' => 'dashboard'));
		$this->_menu[15] = array('title' => 'Profile', 'params' => array('action' => 'profile'));
		$this->_menu[999] = array('route' => 'kms-action', 'title' => 'Logout', 'params' => array('action' => 'logout'));

		// TEMPLATES
		if ( KMS::instance('privilege')->has_any( array('template_activate', 'template_add', 'template_delete', 'template_edit') ) ) {
			$this->_menu[20] = array('title' => 'Templates', 'params' => array('action' => 'templates'),
				'submenu' => array(
					10 => array('title' => 'Overview', 'params' => array('section' => 'overview')),
				));
			if ( KMS::instance('privilege')->has('template_add') )
				$this->_menu[20]['submenu'][20] = array('title' => 'Add New', 'params' => array('section' => 'add'));
		}

		// CONTENT
		if ( KMS::instance('privilege')->has_any( array('content_add', 'content_delete', 'content_edit') ) ) {
			$this->_menu[30] = array('title' => 'Content', 'params' => array('action' => 'content'),
				'submenu' => array(
					10 => array('title' => 'Overview', 'params' => array('section' => 'overview')),
				));
			if ( KMS::instance('privilege')->has('content_add'))
				$this->_menu[30]['submenu'][20] = array('title' => 'Add New', 'params' => array('section' => 'add'));
		}

		// LISTS
		if ( KMS::instance('privilege')->has_any( array('list_add', 'list_delete', 'list_view_add', 'list_view_delete', 'list_view_edit') ) ) {
			$this->_menu[40] = array('title' => 'Lists', 'params' => array('action' => 'lists', 'section' => 'overview'), 'submenu' => array());
			$submenu = array(20 => array('title' => 'Show All Lists', 'params' => array('section' => 'overview')));
			if ( KMS::instance('privilege')->has('list_add'))
				$submenu[10] = array('title' => 'Add New List', 'params' => array('section' => 'add'));
			$this->_menu[40]['submenu'] = KMS::instance('site')->lists->menu($submenu);
		}

		// RESOURCES
		if ( KMS::instance('privilege')->has_any( array('resource_enable', 'resource_disable') )) {
			$this->_menu[50] = array('title' => 'Resources', 'params' => array('action' => 'resources'),
				'submenu' => array(
					10 => array('title' => 'Snippets', 'params' => array('section' => 'snippets')),
					20 => array('title' => 'Chunks', 'params' => array('section' => 'chunks')),
					30 => array('title' => 'Variables', 'params' => array('section' => 'variables')),
				));
		}

		// ADMINISTRATION
		if ( KMS::instance('privilege')->has_any( array('user_add', 'user_edit') ) ) {
			$this->_menu[80] = array('title' => 'Administration', 'params' => array('action' => 'admin'), 'submenu' => array());
			if ( KMS::instance('privilege')->has_any( array('user_add', 'user_edit') ) )
				$this->_menu[80]['submenu'][10] = array('title' => 'Users', 'params' => array('section' => 'users'));
		}

		parent::__construct($request);
	}

	/**
	 * Loads the sidebar
	 */
	public function action_sidebar() {
		$this->action = Request::instance()->action;
		$user = (object) KMS::Session()->get('user');
		$sidebar = $this;

		$this->request->response = View::factory('kms/admin-sidebar', compact('user', 'sidebar'));
	}

	/**
	 * Parses the sidebar menu data to html
	 * @staticvar  int     level of nesting
	 * @param      array   menu data
	 * @param      array   menu parameters
	 * @return     string  html data
	 */
	public function menu($menu = NULL, $params = array()) {
		if ($menu === NULL) $menu = $this->_menu;
		static $level = -1;
		$level++;
		$html = '';
		ksort($menu);

		foreach ($menu as $key => $item) {
			$params = array_merge($params, $item['params']);
			$class = array( ($level==0?'nav-top-item':'') );
			if (empty($item['submenu'])) $class[] = 'no-submenu';
			if (Request::instance()->action == $params['action']) {
				if ($level == 0 || Request::instance()->param('section') == $params['section'])
					$class[] = 'current';
			}


			$attributes = arr::get($item, 'attributes', array());
			$attributes['class'] = (empty($attributes['class']) ? '' : $attributes['class'] . ' ') . implode(' ', $class);
			if (empty($attributes['title'])) $attributes['title'] = $item['title'];

			$html .= '<li>'
				. html::anchor(Route::url(arr::get($item, 'route', 'kms-admin'), $params), $item['title'], $attributes)
				. (!empty($item['submenu']) ? '<ul>' . $this->menu($item['submenu'], $params) . '</ul>' : '')
				. '</li>';
		}

		$level--;
		return $html;
	}

}