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

    /**
     * Default regex for log error codeigniter 3
     * 
     * @return string
     */
    const REGEX = '/^([A-Z]+)\s*\-\s*([\-\d]+\s+[\:\d]+)\s*\-\->\s*(.+)$/';

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
    public function parseLogs($file)
    {
        $arrLogString = $this->prepareLogs($file);

        $no = 1;
        $logs = [];
        foreach ($arrLogString as $logString) {
            preg_match(LogViewer::REGEX, $logString, $matches);

            $level = $matches[1];
            $date = $matches[2];
            $message = $matches[3];
            $logs[] = [
                'no' => $no++,
                'date' => $date,
                'level' => $level,
                'data' => $message,
            ];
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
        return count((array) $glob) > 0 ? $this->parseLogs($glob[0]) : [];
    }

    /**
     * Create array log string
     * 
     * @param string $path
     * 
     * @return array
     */
    public function prepareLogs($path) {
        $arrLogString = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($this->getExt() === 'php') {
            unset($arrLogString[0]);
            $arrLogString = array_values($arrLogString);
        }

        return $arrLogString;
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
}