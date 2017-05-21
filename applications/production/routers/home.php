<?php
$app->make('/', function($request, $response, $service, $app){
  return $app->annen->name;
});
