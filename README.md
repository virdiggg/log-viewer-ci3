# A Simple Log Viewer for CodeIgniter 3

<img src="https://img.shields.io/packagist/php-v/virdiggg/log-viewer-ci3" /> <img src="https://img.shields.io/badge/codeigniter--version-3-green" /> <img src="https://img.shields.io/github/license/virdiggg/log-viewer-ci3" />

## Inspired from [opcodesio/log-viewer](https://github.com/opcodesio/log-viewer) for laravel.

### HOW TO USE
- Install this library with composer
```
composer require virdiggg/log-viewer-ci3
```
- Optional, update your `composer.json` and add this line
```
"scripts": {
    "post-install-cmd": [
        "@php -r \"mkdir('controllers/api'); copy('vendor/virdiggg/log-viewer-ci3/example/Logs.php', 'controllers/api/Logs.php');\""
    ]
}
```
- Open your `config/config.php`, then edit this line.
```diff
-$config['log_threshold'] = 0;
+$config['log_threshold'] = 1;
```
- Optional, you can use `.log` extension, not the default `.php`. Edit this line.
```diff
-$config['log_file_extension'] = '';
+$config['log_file_extension'] = 'log';
```
- Create a controller to use this library. Example is `application/controller/App.php`. This example is in JSON format; you can modify it accordingly.
```
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
        $this->logs->setPath(APPPATH . 'logs');
        // Log extension
        $this->logs->setExt('php');

        $filterDate = $this->input->post('date') ? $this->input->post('date') : '2023-01-01';

        $this->logs->setName('log-' . $filterDate);

        $result = $this->logs->getLogs();

        echo json_encode($result);
        return;
    }
}
```
- Open your website.
```
http://localhost/codeigniter/logs
```
