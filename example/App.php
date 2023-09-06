<?php defined('BASEPATH') or exit('No direct script access allowed');

use Virdiggg\LogViewerCI3\Viewer;

class App extends CI_Controller
{
    public $logs;
    public function __construct()
    {
        parent::__construct();
        $this->logs = new Viewer();
    }

	public function logs()
	{
		// Log path
		$this->logs->setPath(APPPATH . 'logs' . DIRECTORY_SEPARATOR);
		// Log extension, you can modify this to match your log file's extension.
		$this->logs->setExt('php');

        // Date is YYYY-MM-DD format
		$filterDate = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');

		$this->logs->setName('log-' . $filterDate);

		$result = $this->logs->getLogs();

		echo json_encode($result);
		return;
	}
}