<?php
namespace Applications\Production\Request\Controller;

class HomeController
{
  public function index()
  {
    // echo 'asd';
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
