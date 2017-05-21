<?php
namespace Libs\Router;

use Libs\Router\DataCollection\DataCollection;

class ServiceProvider
{
  protected $request,
            $response,
            $session_id,
            $layout,
            $view,
            $shared_data;
  public $view_dir = 'resources/views/';

  public function __construct(Request $request = null, AbstractResponse $response = null)
  {
    $this->bind($request, $response);
    $this->shared_data = new DataCollection();
  }
  public function bind(Request $request = null, AbstractResponse $response = null)
  {
    $this->request  = $request  ?: $this->request;
    $this->response = $response ?: $this->response;
    return $this;
  }
  public function sharedData()
  {
    return $this->shared_data;
  }
  public function startSession()
  {
    if (session_id() === '')
    {
      session_start();
      $this->session_id = session_id() ?: false;
    }
    return $this->session_id;
  }
  public function flash($msg, $type = 'info', $params = null)
  {
    $this->startSession();
    if (is_array($type))
    {
      $params = $type;
      $type = 'info';
    }
    if (!isset($_SESSION['__flashes']))
    {
      $_SESSION['__flashes'] = [$type => []];
    }
    else if (!isset($_SESSION['__flashes'][$type]))
    {
      $_SESSION['__flashes'][$type] = [];
    }
    $_SESSION['__flashes'][$type][] = $this->markdown($msg, $params);
  }
  public function flashes($type = null)
  {
    $this->startSession();
    if (!isset($_SESSION['__flashes']))
    {
      return [];
    }
    if (null === $type)
    {
      $flashes = $_SESSION['__flashes'];
      unset($_SESSION['__flashes']);
    }
    else
    {
      $flashes = [];
      if (isset($_SESSION['__flashes'][$type]))
      {
        $flashes = $_SESSION['__flashes'][$type];
        unset($_SESSION['__flashes'][$type]);
      }
    }
    return $flashes;
  }
  public static function escape($str, $flags = ENT_QUOTES)
  {
    return htmlentities($str, $flags, 'UTF-8');
  }
  public function refresh()
  {
    $this->response->redirect($this->request->uri());
    return $this;
  }
  public function back()
  {
    $referer = $this->request->server()->get('HTTP_REFERER');
    if (null !== $referer)
    {
      $this->response->redirect($referer);
    }
    else
    {
      $this->refresh();
    }
    return $this;
  }
  public function layout($layout = null)
  {
    if (null !== $layout)
    {
      $this->layout = $layout;
      return $this;
    }
    return $this->layout;
  }
  public function yieldView()
  {
    require $this->view;
  }
  public function render($view, array $data = [])
  {
    $original_view = $this->view;
    if (!empty($data))
    {
      $this->shared_data->merge($data);
    }
    $this->view = $this->view_dir . $view . '.php';
    if (null === $this->layout)
    {
      $this->yieldView();
    }
    else
    {
      require $this->layout;
    }
    if (false !== $this->response->chunked)
    {
      $this->response->chunk();
    }
    $this->view = $original_view;
  }
  public function partial($view, array $data = [])
  {
    $layout = $this->layout;
    $this->layout = null;
    $this->render($view, $data);
    $this->layout = $layout;
  }
  public function __isset($key)
  {
    return $this->shared_data->exists($key);
  }
  public function __get($key)
  {
    return $this->shared_data->get($key);
  }
  public function __set($key, $value)
  {
    $this->shared_data->set($key, $value);
  }
  public function __unset($key)
  {
    $this->shared_data->remove($key);
  }
}
