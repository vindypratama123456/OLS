<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha_resource extends CI_Controller {

	public function get($p_FileName = null)
    {
		if (!preg_match('/^[a-z_]+\.(css|gif|js)$/', $p_FileName)) {
            exit('Invalid file name.');
        }

		$path = FCPATH . 'application' . DIRECTORY_SEPARATOR. 'libraries' . DIRECTORY_SEPARATOR. 'botdetect' . DIRECTORY_SEPARATOR. 'lib' . DIRECTORY_SEPARATOR . 'botdetect' . DIRECTORY_SEPARATOR .'public' . DIRECTORY_SEPARATOR . $p_FileName;
		if (is_readable($path)) {
			$fileInfo = pathinfo($path);
			$this->output
                 ->set_content_type($fileInfo['extension'])
                 ->set_output(file_get_contents($path));
		}
	}
}
