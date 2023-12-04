<?php

namespace Virdiggg\LogViewerCI3;

defined('APPPATH') or define('APPPATH', '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR);
defined('LOG_PATH') or define('LOG_PATH', APPPATH.'logs');

class LogViewer
{
    /**
     * Path file logs
     * 
     * @return string
     */
    private $path = LOG_PATH;

    /**
     * Log file name
     * 
     * @return string
     */
    private $name;

    /**
     * Log file extension
     * 
     * @return string
     */
    private $ext = 'log';

    public function __construct()
    {
    }

    /**
     * Get log files as glob.
     *
     * @param array $array
     * 
     * @return array
     */
    public function parseLogs($array)
    {
        $no = 1;
        $logs = [];
        foreach ($array as $file) {
            $logsTemp = file_get_contents($file);
            $pieces = explode('ERROR - ', $logsTemp);

            foreach ($pieces as $piece) {
                if (!empty($piece) && strpos($piece, "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>") === false) {
                    $date = $this->before($piece, ' --> ');

                    $logs[] = [
                        'no' => $no++,
                        'date' => $date,
                        'data' => strip_tags($this->after($piece, ' --> ')),
                    ];
                }
            }
        }

        return $logs;
    }

    /**
     * Get log contents.
     *
     * @return array
     */
    public function getLogs()
    {
        $glob = array_filter(glob($this->getName()), 'is_file');
        return count((array) $glob) > 0 ? $this->parseLogs($glob) : [];
    }

    /**
     * Set name to log file.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $this->getPath() . str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $name) . '.' . $this->getExt();
    }

    /**
     * Get name to log file.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path to log file.
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = rtrim($path, " \r\n\\/") . DIRECTORY_SEPARATOR;
    }

    /**
     * Get path to log file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set log file's extention.
     *
     * @param string $ext
     *
     * @return void
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * Get log file's extention.
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    private function before($subject, $search)
    {
        if ($search === '') {
            return $subject;
        }

        $result = strstr($subject, (string) $search, true);

        return $result === false ? $subject : $result;
    }

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    private function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }
}