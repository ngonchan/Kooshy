<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site database model
 *
 * @package    KMS
 * @category   Modules
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Model_Site extends ORM {

	/**
	 * @var  string  database configuration key
	 */
	protected $_db = KMS_DATABASE;

	/**
	 * @var  array  auto-update columns for creation and updates
	 */
	protected $_created_column = array('column' => 'created', 'format' => TRUE);

	/**
	 * @var  array  ORM has_many relationships
	 */
	protected $_has_many = array(
		'content'        => array( 'model' => 'site_content' ),
		'lists'          => array( 'model' => 'site_list' ),
		'routes'         => array( 'model' => 'site_route' ),
		'site_snippets'  => array( 'model' => 'site_snippet' ),
		'snippets'       => array( 'model' => 'snippet', 'through' => 'site_snippets' ),
		'site_templates' => array( 'model' => 'site_template' ),
		'templates'      => array( 'model' => 'template', 'through' => 'site_templates' ),
		'variables'      => array( 'model' => 'site_variable' ),
		'users'          => array( 'model' => 'user', 'through' => 'site_users' ),
		'user_actions'   => array( 'model' => 'user_action' ),
	);

	/**
	 * @var  array  validation rules
	 */
	protected $_rules = array(
		'domain' => array(
			'not_empty'  => NULL,
			'max_length' => array(60),
		),
		'description' => array(
			'not_empty'  => NULL,
		),
		'template_id' => array(
			'not_empty'  => NULL,
		),
	);

}
