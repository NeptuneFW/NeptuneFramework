<?php
/**
 *  Route
 *
 * @author Mehmet Ali Peker <maps6134@gmail.com>
 */
namespace System;

class Route
{
    /**
     * Your routes nickname
     *
     * @var array
     */
    public $nicknames = array();

    /**
     * @var string
     */
    private $app = '';

    /**
     * @var string
     */
    public $url = '';

    /**
     * @var bool Default False
     */
    private $sitemap = false;

    /**
     * @var array
     */
    public $sitemaps = array();

    /**
     * @var string
     */
    private $path = '';

    /**
     * Route constructor.
     * @param bool $sitemap
     */
    public function __construct($sitemap = false, $app = null)
    {
        $this->sitemap = $sitemap;
        if($sitemap !== true)
        {
            global $base;
            $path = substr(PATH, strlen($base));
            if (is_array($path) AND isset($path[1]) AND (strlen($path[1]) > 0)) {
                $this->path = $path[1];
            }
            if (preg_match('/' . $app . '/', PATH)) {
                $path = substr($path, strlen($app) + 2);
                $path = '/' . $path;
            }
            if(empty($path))
            {
                $this->path = '/';
            }
            else
            {
                $this->path = $path;
            }
            $this->app = $app;

        }
    }

    /**
     * This function is used to direct get requests.
     * @param string $url Request URL Example : /users/{user_name}
     * @param array $options Your route options.
     * @return void
     */
	public function get($url,$options = array())
    {
        global $routed;
        $method = 'GET';
        if (!empty($options)) {
            if (is_array($options)) {
                if ($this->sitemap != true) {
                    if($routed == true) {
                        $call = $options['call'];
                    }
                    if (isset($options['nickname'])) {
                        $this->nicknames[$options['nickname']] = $url;
                    }
                }
                if ($this->sitemap == true) {
                    if (isset($options['sitemap']) AND $this->sitemap == true) {
                        if ($options['sitemap'] != false) {
                            if (is_array($options['sitemap'])) {
                                if (!isset($options['sitemap']['if'])) $options['sitemap']['if'] = null;
                                if (!isset($options['sitemap']['lastmod'])) $options['sitemap']['lastmod'] = null;
                                if (!isset($options['sitemap']['changefreg'])) $options['sitemap']['changefreg'] = null;
                                if (!isset($options['sitemap']['priority'])) $options['sitemap']['priority'] = null;
                                preg_match_all('/{(\w+)}/', $url, $columns);
                                array_shift($columns);
                                if ($this->sitemap == true) {
                                    $columnsNew = array();
                                    foreach ($columns[0] as $columnKey => $columnValue) {
                                        $columnsNew[$columnValue] = $options['sitemap']['columns'][$columnValue];
                                    }
                                    $this->sitemaps[] = [
                                        'url' => $url,
                                        'if' => $options['sitemap']['if'],
                                        'columns' => $columnsNew,
                                        'lastmod' => $options['sitemap']['lastmod'],
                                        'changefreg' => $options['sitemap']['changefreg'],
                                        'priority' => $options['sitemap']['priority']
                                    ];
                                }
                            } else if ($options['sitemap'] == true) {
                                $this->sitemaps[] = [
                                    'url' => $url,
                                ];
                            }
                        }
                    }
                }
            } else {
                $call = $options;
            }
        }
        if($routed == true) {
            if ($this->sitemap != true) {
                global $routed;
                $url = preg_replace('/{[^0-9_-](\\w+)}/', '(.*?)', $url);
                if (preg_match("~^$url$~ms", $this->path, $param) && $routed && METHOD == $method) {
                    if (isset($options['middleware'])) {
                        global $middlewares;
                        if (is_array($options['middleware'])) {
                            $check = true;
                            foreach ($options['middleware'] as $middleware) {
                                $callMiddleWare = explode("@", $middleware);
                                $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                                require_once ltrim($callMiddleWare[0] . ".php", "\\");
                                $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                            }
                        } else if (is_string($options['middleware'])) {
                            $check = true;
                            $callMiddleWare = explode("@", $options['middleware']);
                            $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                            require_once ltrim($callMiddleWare[0] . ".php", "\\");
                            $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                        }
                    }
                    $call = explode('@', $call);
                    $call[0] = "\\App\\" . $this->app . "\\Http\\Controller\\" . $call[0];
                    require ltrim($call[0], '\\') . ".php";
                    global $callRoute;
                    array_shift($param);
                    $callRoute = array($call[0], $call[1], $param);
                    $routed = false;
                }
            }
        }
    }
    public function post($url,$options = array())
    {
        global $routed;
        $method = 'POST';
        if (!empty($options)) {
            if (is_array($options)) {
                if ($this->sitemap != true) {
                    if($routed == true) {
                        $call = $options['call'];
                    }
                    if (isset($options['nickname'])) {
                        $this->nicknames[$options['nickname']] = $url;
                    }
                }
                if ($this->sitemap == true) {
                    if (isset($options['sitemap']) AND $this->sitemap == true) {
                        if ($options['sitemap'] != false) {
                            if (is_array($options['sitemap'])) {
                                if (!isset($options['sitemap']['if'])) $options['sitemap']['if'] = null;
                                if (!isset($options['sitemap']['lastmod'])) $options['sitemap']['lastmod'] = null;
                                if (!isset($options['sitemap']['changefreg'])) $options['sitemap']['changefreg'] = null;
                                if (!isset($options['sitemap']['priority'])) $options['sitemap']['priority'] = null;
                                preg_match_all('/{(\w+)}/', $url, $columns);
                                array_shift($columns);
                                if ($this->sitemap == true) {
                                    $columnsNew = array();
                                    foreach ($columns[0] as $columnKey => $columnValue) {
                                        $columnsNew[$columnValue] = $options['sitemap']['columns'][$columnValue];
                                    }
                                    $this->sitemaps[] = [
                                        'url' => $url,
                                        'if' => $options['sitemap']['if'],
                                        'columns' => $columnsNew,
                                        'lastmod' => $options['sitemap']['lastmod'],
                                        'changefreg' => $options['sitemap']['changefreg'],
                                        'priority' => $options['sitemap']['priority']
                                    ];
                                }
                            } else if ($options['sitemap'] == true) {
                                $this->sitemaps[] = [
                                    'url' => $url,
                                ];
                            }
                        }
                    }
                }
            } else {
                $call = $options;
            }
        }
        if($routed == true) {
            if ($this->sitemap != true) {
                global $routed;
                $url = preg_replace('/{[^0-9_-](\\w+)}/', '(.*?)', $url);
                if (preg_match("~^$url$~ms", $this->path, $param) && $routed && METHOD == $method) {
                    if (isset($options['middleware'])) {
                        global $middlewares;
                        if (is_array($options['middleware'])) {
                            $check = true;
                            foreach ($options['middleware'] as $middleware) {
                                $callMiddleWare = explode("@", $middleware);
                                $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                                require_once ltrim($callMiddleWare[0] . ".php", "\\");
                                $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                            }
                        } else if (is_string($options['middleware'])) {
                            $check = true;
                            $callMiddleWare = explode("@", $options['middleware']);
                            $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                            require_once ltrim($callMiddleWare[0] . ".php", "\\");
                            $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                        }
                    }
                    $call = explode('@', $call);
                    $call[0] = "\\App\\" . $this->app . "\\Http\\Controller\\" . $call[0];
                    require ltrim($call[0], '\\') . ".php";
                    global $callRoute;
                    array_shift($param);
                    $callRoute = array($call[0], $call[1], $param);
                    $routed = false;
                }
            }
        }
    }
    public function put($url,$options = array())
    {
        global $routed;
        $method = 'PUT';
        if (!empty($options)) {
            if (is_array($options)) {
                if ($this->sitemap != true) {
                    if($routed == true) {
                        $call = $options['call'];
                    }
                    if (isset($options['nickname'])) {
                        $this->nicknames[$options['nickname']] = $url;
                    }
                }
                if ($this->sitemap == true) {
                    if (isset($options['sitemap']) AND $this->sitemap == true) {
                        if ($options['sitemap'] != false) {
                            if (is_array($options['sitemap'])) {
                                if (!isset($options['sitemap']['if'])) $options['sitemap']['if'] = null;
                                if (!isset($options['sitemap']['lastmod'])) $options['sitemap']['lastmod'] = null;
                                if (!isset($options['sitemap']['changefreg'])) $options['sitemap']['changefreg'] = null;
                                if (!isset($options['sitemap']['priority'])) $options['sitemap']['priority'] = null;
                                preg_match_all('/{(\w+)}/', $url, $columns);
                                array_shift($columns);
                                if ($this->sitemap == true) {
                                    $columnsNew = array();
                                    foreach ($columns[0] as $columnKey => $columnValue) {
                                        $columnsNew[$columnValue] = $options['sitemap']['columns'][$columnValue];
                                    }
                                    $this->sitemaps[] = [
                                        'url' => $url,
                                        'if' => $options['sitemap']['if'],
                                        'columns' => $columnsNew,
                                        'lastmod' => $options['sitemap']['lastmod'],
                                        'changefreg' => $options['sitemap']['changefreg'],
                                        'priority' => $options['sitemap']['priority']
                                    ];
                                }
                            } else if ($options['sitemap'] == true) {
                                $this->sitemaps[] = [
                                    'url' => $url,
                                ];
                            }
                        }
                    }
                }
            } else {
                $call = $options;
            }
        }
        if($routed == true) {
            if ($this->sitemap != true) {
                global $routed;
                $url = preg_replace('/{[^0-9_-](\\w+)}/', '(.*?)', $url);
                if (preg_match("~^$url$~ms", $this->path, $param) && $routed && METHOD == $method) {
                    if (isset($options['middleware'])) {
                        global $middlewares;
                        if (is_array($options['middleware'])) {
                            $check = true;
                            foreach ($options['middleware'] as $middleware) {
                                $callMiddleWare = explode("@", $middleware);
                                $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                                require_once ltrim($callMiddleWare[0] . ".php", "\\");
                                $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                            }
                        } else if (is_string($options['middleware'])) {
                            $check = true;
                            $callMiddleWare = explode("@", $options['middleware']);
                            $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                            require_once ltrim($callMiddleWare[0] . ".php", "\\");
                            $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                        }
                    }
                    $call = explode('@', $call);
                    $call[0] = "\\App\\" . $this->app . "\\Http\\Controller\\" . $call[0];
                    require ltrim($call[0], '\\') . ".php";
                    global $callRoute;
                    array_shift($param);
                    $callRoute = array($call[0], $call[1], $param);
                    $routed = false;
                }
            }
        }
    }
    public function delete($url,$options = array())
    {
        global $routed;
        $method = 'DELETE';
        if (!empty($options)) {
            if (is_array($options)) {
                if ($this->sitemap != true) {
                    if($routed == true) {
                        $call = $options['call'];
                    }
                    if (isset($options['nickname'])) {
                        $this->nicknames[$options['nickname']] = $url;
                    }
                }
                if ($this->sitemap == true) {
                    if (isset($options['sitemap']) AND $this->sitemap == true) {
                        if ($options['sitemap'] != false) {
                            if (is_array($options['sitemap'])) {
                                if (!isset($options['sitemap']['if'])) $options['sitemap']['if'] = null;
                                if (!isset($options['sitemap']['lastmod'])) $options['sitemap']['lastmod'] = null;
                                if (!isset($options['sitemap']['changefreg'])) $options['sitemap']['changefreg'] = null;
                                if (!isset($options['sitemap']['priority'])) $options['sitemap']['priority'] = null;
                                preg_match_all('/{(\w+)}/', $url, $columns);
                                array_shift($columns);
                                if ($this->sitemap == true) {
                                    $columnsNew = array();
                                    foreach ($columns[0] as $columnKey => $columnValue) {
                                        $columnsNew[$columnValue] = $options['sitemap']['columns'][$columnValue];
                                    }
                                    $this->sitemaps[] = [
                                        'url' => $url,
                                        'if' => $options['sitemap']['if'],
                                        'columns' => $columnsNew,
                                        'lastmod' => $options['sitemap']['lastmod'],
                                        'changefreg' => $options['sitemap']['changefreg'],
                                        'priority' => $options['sitemap']['priority']
                                    ];
                                }
                            } else if ($options['sitemap'] == true) {
                                $this->sitemaps[] = [
                                    'url' => $url,
                                ];
                            }
                        }
                    }
                }
            } else {
                $call = $options;
            }
        }
        if($routed == true) {
            if ($this->sitemap != true) {
                global $routed;
                $url = preg_replace('/{[^0-9_-](\\w+)}/', '(.*?)', $url);
                if (preg_match("~^$url$~ms", $this->path, $param) && $routed && METHOD == $method) {
                    if (isset($options['middleware'])) {
                        global $middlewares;
                        if (is_array($options['middleware'])) {
                            $check = true;
                            foreach ($options['middleware'] as $middleware) {
                                $callMiddleWare = explode("@", $middleware);
                                $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                                require_once ltrim($callMiddleWare[0] . ".php", "\\");
                                $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                            }
                        } else if (is_string($options['middleware'])) {
                            $check = true;
                            $callMiddleWare = explode("@", $options['middleware']);
                            $callMiddleWare[0] = "\\App\\" . $this->app . "\\http\\middleware\\" . $callMiddleWare[0];
                            require_once ltrim($callMiddleWare[0] . ".php", "\\");
                            $middlewares[] = [$callMiddleWare[0], $callMiddleWare[1]];
                        }
                    }
                    $call = explode('@', $call);
                    $call[0] = "\\App\\" . $this->app . "\\Http\\Controller\\" . $call[0];
                    require ltrim($call[0], '\\') . ".php";
                    global $callRoute;
                    array_shift($param);
                    $callRoute = array($call[0], $call[1], $param);
                    $routed = false;
                }
            }
        }
    }

    public function route($nickname)
    {
        if(!preg_match('/app\\\\(\\w+)\\\\/',debug_backtrace()[2]['file'], $app))
        {
            preg_match('/app\\\\(\\w+)\\\\/',debug_backtrace()[4]['file'], $app);
        }
        $app = $app[1];
        $this->app = $app;
        $this->url = $this->nicknames[$nickname];
        return $this;
    }

    public function param()
    {
        $params = func_get_args();
        foreach($params as $param)
        {
            $this->url = preg_replace('/{(.*?)}/', $param, $this->url, 1);
        }
        return $this;
    }

    public function getRoute()
    {
        if(ucfirst(DEFAULT_APP) != ucfirst($this->app))
        {
            $this->url = '/' . $this->app . $this->url;
        }

        return BASE_URL . $this->url;

    }

}
