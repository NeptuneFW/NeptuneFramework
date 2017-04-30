<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 16.04.2017
 * Time: 18:02
 */

namespace System;

class Response
{

    public static function route($nickname)
    {
        global $route;
        return new ResponseRoute($route->nicknames[$nickname]);
    }

    public static function back($time = 0)
    {
        http_response_code(200);

        $url = $_SERVER['HTTP_REFERER'];

        if ($time > 0) {
            header('Refresh: ' . $time . ';url=' .  $url);
        } else if ($time == 0) {
            //var_dump($this->url);
            header('Location: ' .  $url);
        }
    }

}

class ResponseRoute
{
    private $url, $app, $status_code = 200;

    public function __construct($url)
    {
        preg_match('/App\\\\(\\w+)\\\\/', debug_backtrace()[2]['class'], $app);
        $app = $app[1];
        $this->app = $app;
        $this->url = $url;
    }

    public function param()
    {
        $params = func_get_args();
        foreach ($params as $param) {
            $this->url = preg_replace('/{(.*?)}/', $param, $this->url, 1);
        }
        return $this;
    }

    public function withStatus($status_code)
    {
        $this->status_code = $status_code;
        return $this;
    }

    public function go($time = 0)
    {
        http_response_code($this->status_code);
        if ($this->url != '/') {
            $this->url = rtrim($this->url, "/");
        }
        if (ucfirst(DEFAULT_APP) != ucfirst($this->app)) {
            if ($this->url == '/') {
                $this->url = '';
            }
            $this->url = strtolower('/' . $this->app . $this->url);
        }

        if ($time > 0) {
            header('Refresh: ' . $time . ';url=' . BASE_URL . $this->url);
        } else if ($time == 0) {
            //var_dump($this->url);
            header('Location: ' . BASE_URL . $this->url);
        }

    }


}