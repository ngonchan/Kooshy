<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Default public viewing controller
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Controller_KMS extends Controller {

	/**
	 * Loads KMS_Content
	 */
	public function action_index() {
		$this->request->response = KMS::factory('content');
	}

}
