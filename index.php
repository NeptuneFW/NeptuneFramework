<?php
require_once realpath('.') . '\system\init.php';

use Libs\Validator\Validator;

if ($_POST)
{
  $v = new Validator;

  $validation = $v->make($_POST, [
    'name' => 'required'
  ]);
  $validation->validate();

  if ($validation->passed())
  {
    echo 'Geçer';
  }
  else
  {
    $errors = $validation->errors();
    echo $errors->first('name');
  }
}

?>
<form action="" method="post">
  <input type="text" name="name" value=""> <input type="submit" name="" value="SUBMIT!">
</form>
