<?php
namespace System;

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 11.04.2017
 * Time: 12:11
 */

use duncan3dc\Laravel\Blade;

class Application
{

    private static $applications;

    public static  function set($application_name, $application_url, $application_security, $allowed)
    {
        self::$applications[] = ['application_name' => $application_name, 'application_url' => $application_url, 'application_security' => $application_security, 'allowed' => $allowed];

    }

    public static function end()
    {
        $pathApp = PATH;
        $appNotFound = true;
        foreach (self::$applications as $application)
        {
            extract($application);
            $application_url = str_replace("/", "\\/", $application_url);
            if(preg_match( '/' . $application_url . '/', $pathApp))  {

                global $callRoute, $middlewares, $routed, $route;
                $route = new \System\Route(false, $application_name);
                $appNotFound = false;
                if($application_security == 'public') {
                    foreach (glob('app/' . $application_name . '/http/router/*') as $item) {
                        require $item;
                    }
                }
                else {
                    if ($application_security == 'password') {
                        if(isset($_SESSION['application'][$application_name]['allowed']) AND $_SESSION['application'][$application_name]['allowed']  == true){
                            global $callRoute, $middlewares, $routed, $route;
                            $route = new \System\Route(false, $application_name);
                            foreach (glob('app/' . $application_name . '/http/router/*') as $item) {
                                require $item;
                            }
                        }
                        else {

                            global $route, $routed;
                            if(isset($_POST['password'])) {
                                if($_POST['password'] == $allowed) {
                                    $routed = false;
                                    $_SESSION['application'][$application_name]['allowed'] = true;
                                } else {
                                    $routed = false;
                                    echo "

                    <form method='post'>

                        Password:
                        <input type='password' name='password'/>
                        <input type='submit'/>

                    </form>
                    ";

                                }

                            } else {

                                $route = false;

                                echo "

                    <form method='post'>

                        Password:
                        <input type='password' name='password'/>
                        <input type='submit'/>

                    </form>
                    ";

                            }


                        }

                    }
                    if ($application_security == 'ip'){
                        if(isset($_SESSION['application'][$application_name]['allowed']) AND $_SESSION['application'][$application_name]['allowed']  == true){
                            global $callRoute, $middlewares, $routed, $route;
                            $route = new \System\Route(false, $application_name);
                            foreach (glob('app/' . $application_name . '/http/router/*') as $item) {
                                require $item;
                            }
                        } else {
                            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                                $ip = $_SERVER['HTTP_CLIENT_IP'];
                            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                            } else {
                                $ip = $_SERVER['REMOTE_ADDR'];
                            }

                            foreach ($allowed as $value) {

                                if ($value == $ip) {

                                    $_SESSION['application'][$application_name]['allowed'] = true;

                                } else {
                                    echo "You don't have permission to access";
                                    global $route;
                                    $routed = false;
                                }

                            }
                        }

                    }

                }
                if(count($callRoute) > 0)
                {
                    if(count($middlewares) > 0)
                    {
                        foreach ($middlewares as $middleware)
                        {
                            $return = call_user_func([$middleware[0], $middleware[1]]);
                            if($return != true)
                            {
                                die();
                            }
                        }
                    }

                    $class = new $callRoute[0];
                    $callFunc = $callRoute[1];
                    $params = "";
                    foreach ($callRoute[2] as $param)
                    {
                        $params .= "'" . $param . "',";
                    }
                    Blade::share('route', $route);

                    $params = rtrim($params, ",");
                    eval("\$class->" . $callFunc . "(". $params .");");
                }

                if($routed == true) {

                    \Libs\Errors\ErrorHandler::page404();

                }

                exit;
            }
        }
        if($appNotFound)
        {
            global $callRoute, $middlewares, $routed, $route;
            $route = new \System\Route(false, DEFAULT_APP);
            $appNotFound = false;
            foreach (glob('app/' . DEFAULT_APP . '/http/router/*') as $item) {
                require $item;
            }
            if(count($callRoute) > 0)
            {
                if(count($middlewares) > 0)
                {
                    foreach ($middlewares as $middleware)
                    {
                        $return = call_user_func([$middleware[0], $middleware[1]]);
                        if($return != true)
                        {
                            die();
                        }
                    }
                }

                $class = new $callRoute[0];
                $callFunc = $callRoute[1];
                $params = "";
                foreach ($callRoute[2] as $param)
                {
                    $params .= "'" . $param . "',";
                }
                $params = rtrim($params, ",");
                Blade::share('route', $route);
                eval("\$class->" . $callFunc . "(". $params .");");
            }

            if($routed == true) {

                \Libs\Errors\ErrorHandler::page404();

            }

        }
        $GLOBALS['Databases'] = null;
    }

}
