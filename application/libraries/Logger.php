<?php
class Logger
{
    private $CI;

    public function __construct()
    {        
        $this->CI =& get_instance();
    }

    public function logAction($action, $data = null)
    {
        $user_id        = $this->CI->session->userdata('adm_id');
        $today          = date('Y-m-d H:i:s');
        $separator      = ' --> ';
        $messages       = '';

        $messages       .= $today . $separator;
        $messages       .= 'IP: ' . $this->CI->input->ip_address() . $separator;
        $messages       .= 'Action: ' . $action . $separator;

        if ($data) {
            $messages   .= 'Data: ' . str_replace(array("\n", "\r", "    "), '', print_r($data, true)) . $separator;
        }

        $messages       .= 'Current URL: ' . uri_string();

        $path_file      = 'tmp'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'users';
        if (!is_dir($path_file)) {
            if (!mkdir($path_file, 0777, true) && !is_dir($path_file)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path_file));
            }
            @chmod($path_file, 0777);
        } else {
            @chmod($path_file, 0777);
        }

        file_put_contents(FCPATH . $path_file . DIRECTORY_SEPARATOR . 'log-user-' . $user_id . '.php', $messages . PHP_EOL, FILE_APPEND | LOCK_EX);

        return true;
    }

}
