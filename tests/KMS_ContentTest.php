<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Unit test for KMS_Content
 *
 * @package    KMS
 * @category   Unittest
 * @group      kms
 * @group      kms.core
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */
class KMS_ContentTest extends PHPUnit_Framework_TestCase {

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
	 * @todo  Need to create test for __toString. Will have to wait for __contruct refactor
	 */
	public function test__toString() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Need to test object fully rather than stopping at the exception
	 */
	public function testFactory() {
		try {
			KMS::instance('content');
		} catch (Exception $e) {
			$this->assertInstanceOf('KMS_Exception', $e);
			return;
		}
		$this->fail();
	}

}