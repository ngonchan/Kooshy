<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Unit test for KMS
 *
 * @package    KMS
 * @category   Unittest
 * @group      kms
 * @group      kms.core
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */
class KMSTest extends PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {}

	/**
	 * Tests that required keys exist in the configuration file
	 */
	public function testConfig() {
		$this->assertArrayHasKey('session_key', KMS::Config()->as_array());
	}

	/**
	 * Tests that a KMS Session object can be created
	 */
	public function testSession() {
		$this->assertInstanceOf('KMS_Session', KMS::Session());
	}

	/**
	 * Tests that the KMS class can create an instance of a KMS classes
	 */
	public function testFactory() {
		$this->assertInstanceOf('KMS_Site', KMS::factory('site', 1));

		try {
			KMS::factory('content');
			$this->fail('KMS_Content did not product exception');
		} catch (Exception $e) {
			$this->assertInstanceOf('Kohana_Exception', $e);
		}

	}

	/**
	 * Tests that the KMS class can create a singleton instance of a KMS classes
	 */
	public function testInstance() {
		$this->assertInstanceOf('KMS_Site', KMS::instance('site', 1));
	}

	/**
	 * Tests that all available mime_types are accounted for
	 */
	public function testMime_types() {
		$types = array(
			'web' => array(
				'text/plain' => 'text/plain',
				'text/html' => 'text/html',
				'text/css' => 'text/css',
				'application/javascript' => 'application/javascript',
				'application/json' => 'application/json',
				'application/xml' => 'application/xml',
			),
			'images' => array(
				'image/png' => 'image/png',
				'image/jpeg' => 'image/jpeg',
				'image/gif' => 'image/gif',
				'image/bmp' => 'image/bmp',
				'image/vnd.microsoft.icon' => 'image/vnd.microsoft.icon',
				'image/tiff' => 'image/tiff',
				'image/svg+xml' => 'image/svg+xml',
			),
			'archives' => array(
				'application/zip' => 'application/zip',
				'application/x-rar-compressed' => 'application/x-rar-compressed',
				'application/x-msdownload' => 'application/x-msdownload',
				'application/vnd.ms-cab-compressed' => 'application/vnd.ms-cab-compressed',
			),
			'audio/video' => array(
				'audio/mpeg' => 'audio/mpeg',
				'video/quicktime' => 'video/quicktime',
				'application/x-shockwave-flash' => 'application/x-shockwave-flash',
				'video/x-flv' => 'video/x-flv',
			),
			'adobe' => array(
				'application/pdf' => 'application/pdf',
				'image/vnd.adobe.photoshop' => 'image/vnd.adobe.photoshop',
				'application/postscript' => 'application/postscript',
			),
			'ms office' => array(
				'application/msword' => 'application/msword',
				'application/rtf' => 'application/rtf',
				'application/vnd.ms-excel' => 'application/vnd.ms-excel',
				'application/vnd.ms-powerpoint' => 'application/vnd.ms-powerpoint',
			),
			'open office' => array(
				'application/vnd.oasis.opendocument.text' => 'application/vnd.oasis.opendocument.text',
				'application/vnd.oasis.opendocument.spreadsheet' => 'application/vnd.oasis.opendocument.spreadsheet',
			)
		);
		$this->assertSame($types, KMS::mime_types(FALSE));

		$types = array(
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
		$this->assertSame($types, KMS::mime_types());
	}

	/**
	 * No testing needed at this time
	 */
	public function testStop() {
		//die( (string) KMS::stop('Unittesting 123', array('args'), 'KMS Error', TRUE) );
		$stopped = (string) KMS::stop('Unittesting 123', array('args'), 'KMS Error', TRUE);
		$this->assertContains('Unittesting', $stopped);
	}

	/**
	 * Data provider for testInput
	 */
	public function inputData() {
		$columns = array(
			'id' => array(
				'type' => 'int',
				'min' => '-2147483648',
				'max' => '2147483647',
				'column_name' => 'id',
				'column_default' => '',
				'data_type' => 'int',
				'is_nullable' => '',
				'ordinal_position' => '1',
				'display' => '11',
				'comment' => '',
				'extra' => 'auto_increment',
				'key' => 'PRI',
				'privileges' => 'select,insert,update,references',
			),
			'first_name' => array(
				'type' => 'string',
				'column_name' => 'first_name',
				'column_default' => '',
				'data_type' => 'varchar',
				'is_nullable' => '',
				'ordinal_position' => '2',
				'character_maximum_length' => '255',
				'collation_name' => 'latin1_swedish_ci',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			),
			'last_name' => array(
				'type' => 'string',
				'column_name' => 'last_name',
				'column_default' => '',
				'data_type' => 'varchar',
				'is_nullable' => '',
				'ordinal_position' => '3',
				'character_maximum_length' => '255',
				'collation_name' => 'latin1_swedish_ci',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			),
			'details' => array(
				'type' => 'string',
				'character_maximum_length' => '16777215',
				'column_name' => 'details',
				'column_default' => '',
				'data_type' => 'mediumtext',
				'is_nullable' => '',
				'ordinal_position' => '6',
				'collation_name' => 'latin1_swedish_ci',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			),
			'detailslong' => array(
				'type' => 'string',
				'character_maximum_length' => '16777215',
				'column_name' => 'detailslong',
				'column_default' => '',
				'data_type' => 'longtext',
				'is_nullable' => '',
				'ordinal_position' => '6',
				'collation_name' => 'latin1_swedish_ci',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			)
		);

		return array(
			array($columns['id'], '', array('id'), ''),
			array($columns['first_name'], 'Bob', array('id'), form::input('first_name', 'Bob', array('class'=>'text-input large-input'))),
			array($columns['last_name'], '', array('id'), form::input('last_name', '', array('class'=>'text-input large-input'))),
			array($columns['details'], 'No WYSIWYG', array('id'), form::textarea('details', 'No WYSIWYG', array('class'=>'text-input textarea', 'cols'=>'79', 'rows'=>'15')) ),
			array($columns['detailslong'], 'WYSIWYG', array('id'), form::textarea('detailslong', 'WYSIWYG', array('class'=>'text-input textarea tinymce', 'cols'=>'79', 'rows'=>'15')) ),
		);
	}

	/**
	 * @dataProvider inputData
	 */
	public function testInput( array $column_data, $value = '', array $readonly_columns = array('id'), $result ) {
		$this->assertEquals($result, KMS::input($column_data, $value, $readonly_columns));
	}

}
