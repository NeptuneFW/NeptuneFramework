<?php
namespace App\Production\Http\Controller;

use Libs\Connect\Connect;
use duncan3dc\Laravel\Blade;

class Home
{
  public function index()
  {
    echo Blade::render('home');
  }
}
