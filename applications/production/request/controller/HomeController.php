<?php
namespace Applications\Production\Request\Controller;

class HomeController
{
  public function index($request)
  {
    echo $request->headers;
  }
}
