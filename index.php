<?php
require_once 'secret.php';
require_once 'init.php';

use System\Application;

Application::set('development', '/development', 'ip', ['127.0.0.1', '::1']);
Application::end();
