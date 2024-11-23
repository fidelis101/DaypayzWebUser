<?php
class Logger {

    private
        $file,
        $timestamp;

    public function __construct($filename) {
        $this->file = $filename;
    }

    public function setTimestamp($format) {
        date_default_timezone_set("Africa/Lagos");
        $this->timestamp = date($format)." &raquo; ";
    }

    public function putLog($insert) {
        if (isset($this->timestamp)) {
            file_put_contents($this->file, $this->timestamp.$insert."<br>", FILE_APPEND);
        } else {
            trigger_error("Timestamp not set", E_USER_ERROR);
        }
    }

    public function getLog() {
        $content = @file_get_contents($this->file);
        return $content;
    }

    function logErr($data){
        $logPath = __DIR__. "/../logs/logs.txt";
        $mode = (!file_exists($logPath)) ? 'w':'a';
        $logfile = fopen($logPath, $mode);
        fwrite($logfile, "\r\n". $data);
        fclose($logfile);
      }

}