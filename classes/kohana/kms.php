<?php defined('SYSPATH') or die('No direct script access.');
/**
 * [Kooshy] (KMS) is a content management module for the
 * Kohana Framework. The base class is the starting point for all KMS calls.
 *
 * @package    KMS
 * @category   Base
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_KMS {

	/**
	 * @var Kohana_Config holds the configuraton data for the KMS system
	 */
	protected $_config;

	/**
	 * @var KMS_Session holds the session data for the KMS system
	 */
	protected $_session;

	/**
	 * @var KMS this is the singleton instance for the KMS system
	 */
	protected static $_instance;

	/**
	 * @var mixed singleton instances for various called KMS classes
	 */
	protected static $_instances = array();

	/**
	 * Prevents direct instantiation
	 */
	protected function __construct() {}

	/**
	 * Allows static calls to get KMS configuration data
	 * @return  KMS_Config
	 */
	public static function Config() {
		if (empty(self::$_instance)) self::$_instance = new KMS();
		if (empty(self::$_instance->_config)) self::$_instance->_config = Kohana::config('kms');

		return self::$_instance->_config;
	}

	/**
	 * Allows static calls to get KMS session data
	 * @return  KMS_Session
	 */
	public static function Session() {
		if (empty(self::$_instance)) self::$_instance = new KMS();
		if (empty(self::$_instance->_session)) self::$_instance->_session = KMS_Session::instance();;

		return self::$_instance->_session;
	}

	/**
	 * Creates and returns a new KMS object
	 * @param   string  KMS class to load
	 * @param   mixed   optional parameters
	 * @return  mixed
	 */
	public static function factory($class, $params = NULL) {
		if (empty(self::$_instance)) self::$_instance = new KMS();

		$class = 'kms_' . strtolower($class);
		Request::factory('kms-action/cleanup')->execute();
		return call_user_func_array(array($class, 'factory'), array($params));
	}

	/**
	 * Gets a singleton KMS object instance
	 * @param   type   KMS class to get
	 * @param   mixed  optional parameters
	 * @return  mixed
	 */
	public static function instance($class, $params = NULL) {
		$install_file = arr::get(kohana::modules(), 'kms') . 'config/.kms';
		if ( !file_exists($install_file) && !empty($_SERVER['REQUEST_URI']) && !preg_match('/kms-asset/', $_SERVER['REQUEST_URI']) ) {
			require arr::get(kohana::modules(), 'kms') . 'install.php';
		}

		if (empty(self::$_instance)) {
			self::$_instance = new KMS();
			KMS::Session(); // start session
		}

		$kmsclass = 'kms_' . strtolower($class);
		if (empty(self::$_instances[$kmsclass])) {
			self::$_instances[$kmsclass] = self::factory($class, $params);
		}
		return self::$_instances[$kmsclass];
	}

	/**
	 * Helper function to provide mime_types for the KMS system
	 * @param   boolean  flattens the results to eliminate duplicates
	 * @return  array
	 */
	public static function mime_types($flattened = TRUE) {
		$full_mime_types = array(
			'web' => array(
				'txt'  => 'text/plain',
				'htm'  => 'text/html',
				'html' => 'text/html',
				'php'  => 'text/html',
				'css'  => 'text/css',
				'js'   => 'application/javascript',
				'json' => 'application/json',
				'xml'  => 'application/xml',
			),
			'images' => array(
				'png'  => 'image/png',
				'jpe'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg'  => 'image/jpeg',
				'gif'  => 'image/gif',
				'bmp'  => 'image/bmp',
				'ico'  => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif'  => 'image/tiff',
				'svg'  => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
			),
			'archives' => array(
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
			),
			'audio/video' => array(
				'mp3' => 'audio/mpeg',
				'qt'  => 'video/quicktime',
				'mov' => 'video/quicktime',
				'swf'  => 'application/x-shockwave-flash',
				'flv'  => 'video/x-flv',
			),
			'adobe' => array(
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai'  => 'application/postscript',
				'eps' => 'application/postscript',
				'ps'  => 'application/postscript',
			),
			'ms office' => array(
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
			),
			'open office' => array(
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			),
		);

		$mime_types = array();
		if ($flattened === TRUE) {
			foreach ($full_mime_types as $types) {
				foreach ($types as $ext => $type) {
					$mime_types[$ext] = $type;
				}
			}
		} else {
			foreach ($full_mime_types as $key => $types) {
				$mime_types[$key] = array();
				foreach ($types as $type) {
					if (in_array($type, $mime_types[$key])) continue;
					$mime_types[$key][$type] = $type;
				}
			}
		}

		return $mime_types;
	}

	/**
	 * KMS method to output hard error messages to users
	 * @param  string  error message
	 * @param  mixed   optional arguments for debugging
	 * @param  string  page title
	 */
	public static function stop($message, $args = 'Passed in mixed arguments', $title = 'KMS Error', $debug = FALSE) {
		if ($args !== 'Passed in mixed arguments') {
			$message .= "\n<p>Arguments:</p>" . kohana::debug($args);
		}
		$message .= "\n<p><a href=\"javascript:history.back()\">&laquo; Back</a></p>";

		// header status
		if (isset($_SERVER['SERVER_PROTOCOL']))
			$protocol = $_SERVER['SERVER_PROTOCOL'];
		else $protocol = 'HTTP/1.1';
		header($protocol.' 500 Internal Server Error');

		ob_clean();
		ob_start();
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" <?php if ( function_exists( 'language_attributes' ) ) language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $title ?></title>
		<link rel="stylesheet" href="css/install.css" type="text/css" />
		<?php echo html::style('kms-asset/css/error.css') ?>
	</head>
	<body>
		<p><?php echo $message; ?></p>
	</body>
	</html>
	<?php
		$response = ob_get_clean();
		if ($debug === FALSE) die($response);
		else return $response;
	}

	/**
	 * Helper method for displaying form data for lists
	 * @param   array   database table information
	 * @param   string  default/given form field value
	 * @param   array   specifies fields to be hidden
	 * @return  string
	 */
	public static function input( array $column_data, $value = '', array $readonly_columns = array('id') ) {
		if (  in_array($column_data['column_name'], $readonly_columns) ) return $value;

		$data = NULL;
		switch (TRUE) {
			case ('longtext' == $column_data['data_type']):
				$data = form::textarea($column_data['column_name'], $value, array('class'=>'text-input textarea tinymce', 'cols'=>'79', 'rows'=>'15'));
				break;
			case (preg_match('/text$/', $column_data['data_type'])):
				$data = form::textarea($column_data['column_name'], $value, array('class'=>'text-input textarea', 'cols'=>'79', 'rows'=>'15'));
				break;
			default:
				$data = form::input($column_data['column_name'], $value, array('class'=>'text-input large-input'));
				break;
		}

		return $data;
	}

}
