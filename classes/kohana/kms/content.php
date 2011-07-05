<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Content parser for KMS system.
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS_Content {

	/**
	 * @var Model_Site_Content holds the page content
	 */
	protected $_content;

	/**
	 * @var string holds the raw content which echos to the screen
	 */
	protected $_response;

	/**
	 * @var array contains the site chunks
	 */
	protected $_chunks;

	/**
	 * @var array contains the site snippets
	 */
	protected $_snippets;

	/**
	 * @var array contains the site variables
	 */
	protected $_variables;

	/**
	 * @var array holds compiled variables for snippets
	 */
	protected $_render_vars = array();

	/**
	 * Initializes the object
	 */
	public function __construct() {
		$this->_content = KMS::instance('site')->content->where('uri', '=', Request::$current->param('path'))->find();
		if (!$this->_content->loaded()) throw new KMS_Exception ('404 Error [Page not found]');

		$this->_chunks = $this->_flatten( KMS::instance('site')->snippets->where('eval', '=', FALSE)->find_all(), 'code', 'body' );
		$this->_snippets = $this->_flatten( KMS::instance('site')->snippets->where('eval', '=', TRUE)->where('site_snippets.enabled', '=', TRUE)->find_all(), 'code', 'body' );
		$this->_variables = $this->_flatten( KMS::instance('site')->variables->find_all(), 'name', 'value' );
		$this->_load();
	}

	/**
	 * Helper method to flatten the ORM results of the chunks, snippets, and variables
	 * @param   ORM     model to flatten
	 * @param   string  field to be the flattened array's keys
	 * @param   string  field to be the flattened array's values
	 * @return  array
	 */
	protected function _flatten( $data, $keyfield, $valuefield ) {
		$out = array();
		foreach ($data as $item) {
			$out[ $item->$keyfield ] = $item->$valuefield;
		}
		return $out;
	}

	/**
	 * Loads the site content
	 */
	protected function _load() {
		$this->_response = $this->_content->body;
		$active_template = KMS::instance('site')->site_templates->where('enabled', '=', TRUE)->find()->template_id;
		$template = KMS::instance('site')->templates->find($active_template);
		$this->_variables['title'] = $this->_content->title;
		if ($template->loaded() && $this->_content->mime_type == 'text/html') {
			$this->_variables['content'] = $this->_content->body;
			$this->_variables['meta_keywords'] = $this->_content->meta_keywords;
			$this->_variables['meta_description'] = $this->_content->meta_description;
			$this->_response = $template->body;
		}
	}

	/**
	 * Helper method for evaluating snippets
	 * @param   string  snippet code
	 * @param   string  raw data to evaluate
	 * @return  string
	 */
	protected function _eval($find, $eval_data_in) {
		ob_start();
		try {
			extract($this->_render_vars);
			eval('?>' . $eval_data_in);
		} catch (Exception $e) {
			echo 'An error occurred in snippet `' . $find . '`: ' . $e->getMessage() . ' on line ' . $e->getLine();
		}
		$eval_data_out = ob_get_clean();

		return $eval_data_out;
	}

	/**
	 * Helper function for rendering site variables
	 * @param   string   data to parse (chunk, snippet, variable)
	 * @param   string   specifies what type of data is being passed in ([c]hunk, [s]nippet, [v]ariable, [list])
	 * @return  boolean
	 */
	protected function _parse( $data, $type ) {
		$found = FALSE;
		foreach ($data as $key => $value) {
			$find = $key;
			if ($type != 'list')
				$key = preg_quote($key, '/');
			$key = "/\[\[{$type}\*{$key}\]\]/";
			if (!preg_match($key, $this->_response, $matches)) continue;
			if ($type == 's') {
				$value = $this->_eval($find, $value);
			} else if ($type == 'list') {
				$data = KMS::factory('list', $matches)->load();
				$this->_render_vars += $data;
			}
			$this->_response = preg_replace($key, $value, $this->_response);
			$found = TRUE;
		}
		return $found;
	}

	/**
	 * Renders the KMS content to string for output to the browser
	 * @return  string
	 */
	public function __toString() {
		while (
			$this->_parse($this->_variables, 'v') ||
			$this->_parse($this->_chunks, 'c') ||
			$this->_parse(array('(.+?)'=>''), 'list')
		) {}

		while (
			$this->_parse($this->_variables, 'v') ||
			$this->_parse($this->_chunks, 'c') ||
			$this->_parse($this->_snippets, 's')
		) {}
		return (string) Request::$current->response = $this->_response;
	}

	/**
	 * Creates and returns a new KMS_Content object
	 * @return  KMS_Content
	 */
	public static function factory() {
		return new KMS_Content();
	}

}
