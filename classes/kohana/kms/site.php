<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Site loader for KMS system.
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS_Site {

	/**
	 * @var  int  database id of the site
	 */
	protected $_id;

	/**
	 * @var  Model_Site  database model for the site
	 */
	protected $_site;

	/**
	 * @var  Model_Route  database model for site routes
	 */
	protected $_routes;

	/**
	 * Creates a new instance of the class
	 * @param  int  optional specific site load id
	 */
	public function __construct($site_id = NULL) {
		$this->_id = $site_id === NULL ? $this->_lookup() : $site_id;

		$this->_site = ORM::factory('site', $this->_id);
		if ( ! $this->_site->loaded() ) {
			throw new Kohana_Exception('The KMS Site ID :site: could not be found/loaded from the database', array(':site:' => $this->_id));
		}

		foreach ($this->_site->routes->find_all() as $route) {
			$regexps = array();
			$defaults = array();
			foreach ($route->defaults->find_all() as $default) {
				$defaults[$default->key] =  $default->value;
			}
			foreach ($route->regexps->find_all() as $regexp) {
				$regexps[$regexp->key] = $regexp->regexp;
			}

			$new_route = Route::set('kms_' . $route->name, $route->route, $regexps);
			if (!empty($defaults)) {
				$new_route->defaults($defaults);
			}
		}
	}

	/**
	 * Creates and return a new instance of the class
	 * @param   KMS_Site  optional specific site load id
	 * @return  KMS_Site
	 */
	public static function factory($site_id = NULL) {
		return new KMS_Site($site_id);
	}

	/**
	 * Magic method to return specific items from the site model
	 * @param   string  name of column/relationship to retrieve
	 * @return  mixed   returns column or orm relationship model
	 */
	public function __get($name) {
		return $this->_site->{$name};
	}

	/**
	 * Checks for the site domain in the database. This prevents unauthorized
	 * sites from displaying the content.
	 * @throw   Kohana_Exception  throws exception if domain is not found in the site database
	 * @return  int               returns the found site id
	 */
	protected function _lookup() {
		$domain = arr::get($_SERVER, 'HTTP_HOST');
		$site = ORM::factory('site', array('domain' => $domain));
		if ( ! $site->loaded() ) {
			throw new Kohana_Exception('The KMS Site :domain could not be found/loaded from the database', array(':domain' => $domain));
		} else {
			return $site->id;
		}
	}

}
