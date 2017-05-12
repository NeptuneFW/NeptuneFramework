<?php
    namespace development;
    class App
    {
        protected $controller   = 'home';
        protected $method       = 'index';
        const THIS_APP = "development";
    
        public function __construct()
        {
    
            $databases = glob("database/databases/*");
    
            foreach ($databases as $database) {
    
                $connectionSettings = file_get_contents($database . "/connection.ntconfig");
                eval("\$connectionSettings = " . $connectionSettings );
    
    
                $databaseName = explode("/", $database);
                $databaseName = end($databaseName);
    
                $GLOBALS['Databases'][$databaseName] = new \PDO("mysql:host=". $connectionSettings['host'] .";dbname=" . $databaseName . ";charset=utf8", $connectionSettings['user'], $connectionSettings['pass']);
    
            }
    
    
            $url = isset($_GET['url']) ? $_GET['url'] : null;
            $url = rtrim($url, '/');
            $url = explode('/', $url);
            if (empty($url[0]))
            {
                require __DIR__ .'/../http/controller/' . $this->controller . '.php';
                $method = $this->method;
                $ctrl = new $this->controller();
                $ctrl->$method();
                return false;
            }
            $this->controller = $url[0];
            $file = 'app/http/controller/' . $this->controller . '.php';
            if (file_exists($file))
            {
                require $file;
            }
            else
            {
                ErrorHandler::page404();
                return false;
            }
            $this->controller = new $url[0];
            if (isset($url[2]))
            {
                if (method_exists($this->controller ,$url[1]))
                {
                    $this->controller->{$url[1]}($url[2]);
                }
                else
                {
                    ErrorHandler::page404();
                    return false;
                }
            }
            else
            {
                if (isset($url[1]))
                {
                    if (!method_exists($this->controller ,$url[1]))
                    {
                        ErrorHandler::page404();
                    }
                    else
                    {
                        $this->controller->{$url[1]}();
                    }
                }
                else if (empty($url[1]))
                {
                    $method = $this->method;
                    $this->controller->$method();
                }
            }
        }
    }