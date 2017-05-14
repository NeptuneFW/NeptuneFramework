<?php
namespace Applications\Production\Request\Controller;

class HomeController
{
  public function index($param)
  {
    echo 'asd ali ' . $param;
  }
  public function profile($id)
  {
    echo 'Profile! ' . $id;
  }
  public function test()
  {
    echo 'test';
  }
}
