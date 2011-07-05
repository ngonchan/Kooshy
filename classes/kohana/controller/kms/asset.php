<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Asset controller for KMS system
 *
 * @package    KMS
 * @category   Controller
 * @author     Alan Roemen <aroemen@cognitived.com>
 * @copyright  (c) 2011 Cognitived
 * @license    http://cognitived.com/kms/license
 */

class Kohana_Controller_KMS_Asset extends Controller {

	/**
	 * Loads the asset specified
	 */
	public function action_load() {
		$content = '';
		$type = Request::$current->param('type');
		$asset = Request::$current->param('file');
		preg_match('/^(.+?)\.(.{2,3}$)/', $asset, $asset);
		if (empty($asset))
			$content .= '<!-- Unable to parse asset -->';
		else {
			$ext = $asset[2];
			$asset = $asset[1];
			$file = Kohana::find_file('assets/kms', "{$type}/{$asset}", $ext);
			if ($file === FALSE)
				$content .= "<!-- Missing: {$type}/{$asset} Ext: {$ext} -->\n";
			else {
				if ($ext == 'js' || $ext == 'css') {
					$content .= '/*** Resource: ' . Request::$current->uri . " ***/\n";
				}
				switch ($ext) {
					case '7z':
					case 'flv':
					case 'pdf':
					case 'mp3':
					case 'mov':
					case 'rar':
					case 'swf':
					case 'xml':
					case 'zip':
						Request::$current->headers['Content-type'] = $this->_get_mime_type($file, $ext);
						Request::$current->send_headers();
						die(file_get_contents($file));
						break;
					default:
						Request::$current->headers['Content-type'] = $this->_get_mime_type($file, $ext);
						$content .= file_get_contents($file);
						break;
				}
			}
		}

		$this->request->response = $content;
	}

	/**
	 * Helper method to find mime_type
	 * @param   string  name of the file
	 * @param   string  extension of the file
	 * @return  string
	 */
	private function _get_mime_type($filename, $ext) {
		$mime_types = KMS::mime_types();

		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		} elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}
}
