<?php
namespace Triton;

use PDO;
use PDOException;
use Triton\TritonTable;

class Triton extends PDO
{
  public static $db,
                  $dbDIR,
                  $settings;

  public function __construct($db, $host = null, $user = null, $pass = null)
  {
    require realpath(__DIR__) . '/database.config.php';
    self::$settings = $settings;

    self::$settings['host'] = empty($host) ? self::$settings['host'] : $host;
    self::$settings['user'] = empty($user) ? self::$settings['user'] : $user;
    self::$settings['pass'] = empty($pass) ? self::$settings['pass'] : $pass;

    self::$dbDIR = realpath('.') . DIRECTORY_SEPARATOR . 'database/databases' . DIRECTORY_SEPARATOR . ucwords($db);

    try
    {
        parent::__construct('mysql:host=' . self::$settings['host'] . ';dbname=' . $db, self::$settings['user'], self::$settings['pass']);
    }
    catch (PDOException $e)
    {
      die('No connection to the database! We think the problem is yours. If it\'s us, please contact us! More information: ' . $e->getMessage());
    }
    if (is_dir(self::$dbDIR))
    {
      if (!file_exists(self::$dbDIR . '/connection.ntconfig'))
      {
        file_put_contents(self::$dbDIR . '/connection.ntconfig', 'array(\'host\' => \'' . self::$settings['host'] . '\', \'user\' => \'' . self::$settings['user'] . '\', \'pass\' => \'' . self::$settings['pass'] . '\');');
      }
      if (!file_exists(self::$dbDIR . "/lock.nt"))
      {
        foreach (glob(self::$dbDIR . '/*') as $value)
        {
          if (pathinfo($value, PATHINFO_BASENAME) == 'connection.ntconfig' || preg_match("/Row\.php/", pathinfo($value, PATHINFO_BASENAME)))
          {
            continue;
          }
          $fileName = pathinfo($value, PATHINFO_FILENAME);
          $class = '\\Database\\Databases\\' . $db . '\\' . $fileName . '';
          require $value;
          $class = new $class;
          $table = call_user_func_array(array($class, "table"), array(new TritonTable()));
          $fileString = "array( 'table' => '" . $fileName . "', \r\n";

          $sqlString = 'CREATE TABLE IF NOT EXISTS ' . rtrim($fileName, 'Table') . ' ( ' . "\r\n";

          $sqlStringEnd = '';
          foreach ($table->variables as $typeKey => $typeValue)
          {
            foreach ($typeValue as $columnKey => $columnValue)
            {
              if (!empty($columnValue['length']))
              {
                $sqlString .= $columnValue['name'] . ' ' . $typeKey . '(' . $columnValue['length'] . ') ';
                $fileString .= 'array(\'name\' => \'' . $columnValue['name'] . '\', \'type\' => \'' . $typeKey . '\', \'length\' => \'' . $columnValue['length'] . '\'';
              }
              else
              {
                $sqlString .= $columnValue['name'] . ' ' . $typeKey . ' ';
                $fileString .= 'array(\'name\' => \'' . $columnValue['name'] . '\',\'type\' => \'' . $typeKey . '\', \'length\' => null';
              }
              $fileString .= ',';
              if (isset($columnValue['class']) && is_object($columnValue['class']))
              {
                $fileString .= 'array(\'properties\' =>';

                foreach ($columnValue['class']->properties as $propkey => $propValue)
                {
                  $sqlString .= ' ' . $propValue . ' ';
                  $fileString .= ' \'' . $propValue . '\',';
                }
                $fileString .= '),';

                foreach ($columnValue['class']->sqlEnd as $sqlEndValue)
                {
                    $sqlStringEnd .= ', ' . $sqlEndValue . ' ';
                }
              }
              $sqlString .= ', ';
              $fileString .= '),';
            }
          }
          $sqlString = rtrim($sqlString, ', ');
          $sqlStringEnd .= ')';
          foreach ($table->sqlEnd as $sqlEndValue)
          {
            $sqlStringEnd .= ' ' . $sqlEndValue . ' ';
          }
          $sqlString .= $sqlStringEnd;
          $fileString .= ');';
          fopen(self::$dbDIR . '/lock.nt', 'w+');
          $this->exec($sqlString);
        }
      }
    }
    else
    {
      mkdir(realpath(__DIR__) . '../../database/databases/' . ucwords($db));

      if (!file_exists(realpath(__DIR__) . '../../database/databases/' . ucwords($db) . '/connection.ntconfig'))
      {
        file_put_contents(realpath(__DIR__) . '../../database/databases/' . ucwords($db) . '/connection.ntconfig', 'array(\'host\' => \'' . self::$settings['host'] . '\', \'user\' => \'' . self::$settings['user'] . '\', \'pass\' => \'' . self::$settings['pass'] . '\');');
      }
      if (!file_exists(realpath(__DIR__) . '../../database/databases/' . ucwords($db) . '/lock.nt'))
      {
        file_put_contents(realpath(__DIR__) . '../../database/databases/' . ucwords($db) . '/lock.nt', '');
      }
    }
  }
  public static function TableColumn($name, $length = null)
  {
    return new TritonTableColumn($name, $length);
  }
}
