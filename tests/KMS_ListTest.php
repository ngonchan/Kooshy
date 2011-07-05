<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Unit test for KMS_List
 *
 * @package    KMS
 * @category   Unittest
 * @group      kms
 * @group      kms.core
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */
class KMS_ListTest extends PHPUnit_Framework_TestCase {

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

	public function provider() {
		return array(
			array( array('[[list*contacts]') ),
		);
	}

	/**
	 * @dataProvider provider
	 */
	public function testFactory( $params ) {
		$this->markTestIncomplete('not implemented');
	}

}