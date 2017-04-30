<?php

namespace Triton;

class Triton extends \PDO
{
    public static $db;
    public static $dbDIR;
    public static $settings;

    public function __construct($db, $host = null, $user = null, $pass = null)
    {

        require realpath(__DIR__) . "/database.config.php";
        self::$settings = $settings;

        self::$settings['host'] = empty($host) ? self::$settings['host'] : $host;
        self::$settings['user'] = empty($user) ? self::$settings['user'] : $user;
        self::$settings['pass'] = empty($pass) ? self::$settings['pass'] : $pass;

        self::$dbDIR = ROOT . DIRECTORY_SEPARATOR . "database/databases" . DIRECTORY_SEPARATOR . ucwords($db);

        try {
            parent::__construct("mysql:host=" . self::$settings["host"] . ";dbname=" . $db, self::$settings['user'], self::$settings['pass']);
        } catch (PDOException $e) {

            echo "Üzgünüz... Veritabanı bağlantısı sırasında hata oluştu. Lütfen bağlantı bilgilerinizin doğruluğunu kontrol ediniz <br/> \r\n";
            echo "Başarılı bir veritabanı bağlantısı olmadığı için Triton devam edemiyor <br/> \r\n";
            echo "Detaylı Bilgi : <br/> " . $e->getMessage() . " \r\n";
            die();

        }


        if (is_dir(self::$dbDIR)) {

            if (!file_exists(self::$dbDIR . "/connection.ntconfig")) {

                file_put_contents(self::$dbDIR . "/connection.ntconfig", "array('host' => '" . self::$settings['host'] . "', 'user' => '" . self::$settings['user'] . "','pass' => '" . self::$settings['pass'] . "');");

            }

            if (!file_exists(self::$dbDIR . "/lock.nt")) {
                foreach (glob(self::$dbDIR . "/*") as $value) {
                    if (pathinfo($value, PATHINFO_BASENAME) == 'connection.ntconfig' || preg_match("/Row\.php/", pathinfo($value, PATHINFO_BASENAME))) {
                        continue;
                    }

                    $fileName = pathinfo($value, PATHINFO_FILENAME);

                    $class = '\Database\Databases\\' . $db . '\\' . $fileName . '';

                    $class = new $class;

                    $table = call_user_func_array(array($class, "table"), array(new \Triton\TritonTable()));

                    //var_dump($table);

                    $fileString = "array( 'table' => '" . $fileName . "', \r\n";

                    $sqlString = 'CREATE TABLE IF NOT EXISTS ' . $fileName . ' ( ' . "\r\n";

                    $sqlStringEnd = "";

                    foreach ($table->variables as $typeKey => $typeValue) {

                        foreach ($typeValue as $columnKey => $columnValue) {

                            if (!empty($columnValue['length'])) {

                                $sqlString .= $columnValue['name'] . " " . $typeKey . "(" . $columnValue['length'] . ") ";
                                $fileString .= "array('name' => '" . $columnValue['name'] . "','type' => '" . $typeKey . "', 'length' => '" . $columnValue['length'] . "'";

                            } else {

                                $sqlString .= $columnValue['name'] . " " . $typeKey . " ";
                                $fileString .= "array('name' => '" . $columnValue['name'] . "','type' => '" . $typeKey . "', 'length' => null";


                            }
                            $fileString .= ",";
                            if (isset($columnValue['class']) && is_object($columnValue['class'])) {

                                $fileString .= "array('properties' =>";

                                foreach ($columnValue['class']->properties as $propkey => $propValue) {

                                    $sqlString .= " " . $propValue . " ";
                                    $fileString .= " '" . $propValue . "',";

                                }

                                $fileString .= "),";

                                foreach ($columnValue['class']->sqlEnd as $sqlEndValue) {

                                    $sqlStringEnd .= ", " . $sqlEndValue . " ";

                                }


                            }

                            $sqlString .= ", ";
                            $fileString .= "),";

                        }

                    }

                    $sqlString = rtrim($sqlString, ", ");


                    $sqlStringEnd .= ")";

                    foreach ($table->sqlEnd as $sqlEndValue) {

                        $sqlStringEnd .= " " . $sqlEndValue . " ";

                    }

                    $sqlString .= $sqlStringEnd;
                    $fileString .= ");";

                    if (!is_dir("Temp/Tables/" . ucfirst($db))) {

                        mkdir("Temp/Tables/" . ucfirst($db));

                    }

                    fopen(self::$dbDIR . "/lock.nt", "w+");
                    $this->exec($sqlString);

                    var_dump($sqlString);
                }
            }


        } else {

            echo realpath(__DIR__);

            mkdir(realpath(__DIR__) . "../../database/databases/" . ucwords($db));

            if (!file_exists(realpath(__DIR__) . "../../database/databases/" . ucwords($db) . "/connection.ntconfig")) {

                file_put_contents(realpath(__DIR__) . "../../database/databases/" . ucwords($db) . "/connection.ntconfig", "array('host' => '" . self::$settings['host'] . "', 'user' => '" . self::$settings['user'] . "','pass' => '" . self::$settings['pass'] . "');");

            }

            if (!file_exists(realpath(__DIR__) . "../../database/databases/" . ucwords($db) . "/lock.nt")) {

                file_put_contents(realpath(__DIR__) . "../../database/databases/" . ucwords($db) . "/lock.nt", "");

            }

        }

    }

    public static function TableColumn($name, $length = null)
    {
        return new TritonTableColumn($name, $length);
    }
}