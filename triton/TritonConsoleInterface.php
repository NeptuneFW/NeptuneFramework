<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 27.03.2017
 * Time: 19:34
 */

require __DIR__ . "/TritonTable.php";
require __DIR__ . "/TritonTableColumn.php";

use Triton\TritonTableColumn;
use Triton\TritonTable;

class TritonConsoleInterface extends \PDO
{

    public static $settings, $dbDIR;

    public function __construct($db, $host = null, $user = null, $pass = null)
    {

        require realpath(__DIR__) . "/database.config.php";
        self::$settings = $settings;

        self::$settings['host'] = empty($host) ? self::$settings['host'] : $host;
        self::$settings['user'] = empty($user) ? self::$settings['user'] : $user;
        self::$settings['pass'] = empty($pass) ? self::$settings['pass'] : $pass;

        self::$dbDIR = realpath(__DIR__) . "/../database/databases/" . ucwords($db);

        try {
            parent::__construct("mysql:host=" . self::$settings["host"] . ";dbname=" . $db, self::$settings['user'], self::$settings['pass']);
        } catch (PDOException $e) {

            echo "Üzgünüz... Veritabanı bağlantısı sırasında hata oluştu. Lütfen bağlantı bilgilerinizin doğruluğunu kontrol ediniz <br/> \r\n";
            echo "Başarılı bir veritabanı bağlantısı olmadığı için Triton devam edemiyor <br/> \r\n";
            echo "Detaylı Bilgi : <br/> " . $e->getMessage() . " \r\n";
            die();

        }
    }

    public function createTable($value, $db) {

        $fileName = pathinfo($value, PATHINFO_FILENAME);
        $dataBase = ucfirst($db);

        $class = '\database\databases\\' . $dataBase . '\\' . $fileName;

        require $value;

        $class = new $class;
        $table = call_user_func_array(array($class, "table"), array(new TritonTable()));
        $sqlString = 'CREATE TABLE IF NOT EXISTS ' . explode("Table", $fileName)[0] . ' ( ' . "\r\n";
        $sqlStringEnd = '';
        foreach ($table->variables as $typeKey => $typeValue)
        {
            foreach ($typeValue as $columnKey => $columnValue)
            {
                if (!empty($columnValue['length'])) {
                    $sqlString .= $columnValue['name'] . ' ' . $typeKey . '(' . $columnValue['length'] . ') ';
                }
                else
                {
                    $sqlString .= $columnValue['name'] . " " . $typeKey . " ";
                }
                if (isset($columnValue['class']) && is_object($columnValue['class']))
                {
                    foreach ($columnValue['class']->properties as $propkey => $propValue)
                    {
                        $sqlString .= " " . $propValue . " ";
                    }
                    foreach ($columnValue['class']->sqlEnd as $sqlEndValue)
                    {
                        $sqlStringEnd .= ", " . $sqlEndValue . " ";
                    }
                }
                $sqlString .= ", ";
            }
        }
        $sqlString = rtrim($sqlString, ", ");
        $sqlStringEnd .= ")";
        foreach ($table->sqlEnd as $sqlEndValue)
        {
            $sqlStringEnd .= " " . $sqlEndValue . " ";
        }
        $sqlString .= $sqlStringEnd;
        fopen(self::$dbDIR . "/lock.nt", "w+");
        $this->exec($sqlString);
    }

    public function createTableFile($tableName, $database)
    {
    $table[0] = $tableName;
    $connectionSettings = file_get_contents("database/databases/". $database . "/connection.ntconfig");
    eval("\$connectionSettings = " .$connectionSettings);
    try
    {
        $pdoConnection = new PDO("mysql:host=" . $connectionSettings["host"] . ";dbname=" . $database, $connectionSettings['user'], $connectionSettings['pass']);
    }
    catch (PDOException $e)
    {
        echo "Üzgünüz... Veritabanı bağlantısı sırasında hata oluştu. Lütfen bağlantı bilgilerinizin doğruluğunu kontrol ediniz <br/> \r\n";
        echo "Başarılı bir veritabanı bağlantısı olmadığı için Triton devam edemiyor <br/> \r\n";
        echo "Detaylı Bilgi : <br/> " .  $e->getMessage() . " \r\n";
        die();
    }
        $queryTable = $pdoConnection->prepare('SHOW COLUMNS FROM ' . $table[0]);
        $queryTable->execute();
        $columns = $queryTable->fetchAll();
        $tableFileContent = "<?php

namespace Database\\Databases\\" . ucfirst($database) . ";
use \\Triton\\Triton as Triton;

/**
* Created by Neptune Framework.
* User: Triton
*/ 

class ". ucfirst($table[0]) . "Table
{

    protected static \$table_id_column = 'id'; // The name of the column that has the table ID number
    private \$where = [];
    
    public function table(\$table) 
    {";
        $funcArgs = array('str' => '');

        foreach($columns as $column)
        {
            preg_match('/[0-9]+/', $column['Type'], $length);
            $tableFileContent .= '
            
        $column'. ucfirst($column['Field']) .' = \Triton::TableColumn("'.$column['Field'].'");';

            if($column['Key'] == 'PRI') {

                $tableFileContent .= '
            $column'. ucfirst($column['Field']) .'->primary();';

            }

            if($column['Null'] == 'NO') {

                $tableFileContent .= '
            $column'. ucfirst($column['Field']) .'->null("NOT NULL");';

            }
            else if ( $column['Null'] == 'YES' )
            {

                $tableFileContent .= '
            $column'. ucfirst($column['Field']) . '->null("NULL");';

            }

            if( !empty($column['Default'])) {

                $tableFileContent .= ' 
            $column' . ucfirst($column['Field']) . '->default("'. $column['Default'] .'");';

            }

            if( !empty($column['Extra'])) {

                $tableFileContent .= '
            $column'. ucfirst($column['Field']) . '->extra("' . $column['Extra'] . '");';

            }

            if(!isset($length[0])) {

                $tableFileContent .= '
            $table->'.$column['Type']. '($column' . ucfirst($column['Field']) . ');';

            } else {

                preg_match('/(\w)+/', $column['Type'], $type);

                $tableFileContent .= '
            $table->'.$type[0]. '($column' . ucfirst($column['Field']) . ','.$length[0].');';

            }
            //var_dump($column);


            $funcArgs['str'] .= "$" . $column['Field'] . " = null,";
            $funcArgs[] .=$column['Field'];


        }

        $funcArgs['str'] = rtrim($funcArgs['str'], ",");
        $tableFileContent .= "
    
        return \$table;
    } 
    public static function all(\$columns = null, \$type = 'object') 
    {
        \$pdoConnection = \$GLOBALS['Databases']['" . ucfirst($database) . "'];
        if(!empty(\$columns)) {
            if(is_array(\$columns)) 
            {
                \$columnString = \"\";
                foreach (\$columns as \$column) 
                {
                    \$columnString .= \$column . \", \";
                }
                \$columnString = rtrim(\$columnString, \", \");
            }
            else 
            {
                \$columnString = \$columns;
            }
        }
        else 
        {
            \$columnString = '*';
        }
        \$selectWhere = \$pdoConnection->query('SELECT ' . \$columnString . ' FROM " . $table[0] . " ')->fetchAll();
        if(\$type == 'array') 
        {
            return \$selectWhere;
        }
        if(count(\$selectWhere) > 1) 
        {
            \$classRow = new ". ucfirst($table[0]) ."Row();
            foreach (\$selectWhere as \$itKey => \$itValue) 
            {
                foreach (\$itValue as \$itemKey => \$itemValue) 
                {
                    if (!is_int(\$itemKey)) 
                    {
                        \$classRow->\$itemKey[] = \$itemValue;
                    }
                }
            }
            return \$classRow;
        } 
        else if (count(\$selectWhere) == 1) 
        {
            \$classRow = new ". ucfirst($table[0]) ."Row();
            foreach (\$selectWhere[0] as \$itemKey => \$itemValue) 
            {
                if (!is_int(\$itemKey)) 
                {
                    \$classRow->\$itemKey[] = \$itemValue;
                }
            }
            return \$classRow;
        } 
        else 
        {
            return false;
        }
    }
    public function where(\$column, \$operator, \$value) 
    {
        \$this->where[\"first\"] = array(\$column, \$operator, \$value);
        return \$this;
    }

    public function orWhere(\$column, \$operator, \$value) 
    {
        \$this->where[\"orWhere\"][]= array(\$column, \$operator, \$value);
        return \$this;
    }

    public function andWhere(\$column, \$operator, \$value) {
        \$this->where[\"andWhere\"][]= array(\$column, \$operator, \$value);
        return \$this;
    }

    public function execute() {
        \$pdoConnection = \$GLOBALS['Databases']['" . ucfirst($database) . "'];
        \$bind = [];
        \$selectQuery = \"SELECT * FROM ". $table[0] ." WHERE \" . \$this->where['first'][0] . \" \" . \$this->where['first'][1] . \":first \";
        \$bind['first'] = \$this->where['first'][2];
        \$i = 0;
        if(isset(\$this->where['orWhere'])) 
        {      
            foreach(\$this->where['orWhere'] as \$itemValue)
            {
                \$selectQuery .= \"OR \" . \$itemValue[0] . \" \" . \$itemValue[1] . \" :bind_\" . \$i . \" \";
                \$bind['bind_' . \$i] = \$itemValue[2]; 
                \$i++;
            }
        }
        if(isset(\$this->where['andWhere'])) 
        {
            foreach (\$this->where['andWhere'] as \$itemValue) 
            {
                \$selectQuery .= \"AND \" . \$itemValue[0] . \" \" . \$itemValue[1] . \" :bind_\" . \$i . \" \";
                \$bind['bind_' . \$i] = \$itemValue[2];
                \$i++;
            }
        }      
        \$selectWhere = \$pdoConnection->prepare(\$selectQuery);
        \$selectWhere->execute(\$bind);
        
        if(\$selectWhere != false )
        {
            \$selectWhere = \$selectWhere->fetchAll();
        }
        else
        {
            return false;
        }
        if(count(\$selectWhere) > 1) 
        {
            \$classRow = new ". $table[0] ."Row();
            foreach (\$selectWhere as \$itKey => \$itValue) 
            {
                foreach (\$itValue as \$itemKey => \$itemValue) 
                {
                    if(\$itemKey == self::\$table_id_column)
                    {
                        \$classRow->triton_" . strtolower($table[0]) . "_id = [\$itemValue, self::\$table_id_column];
                    }
                    if (!is_int(\$itemKey)) {
                        \$classRow->\$itemKey[] = \$itemValue;
                    }
                }
            }          
            return \$classRow;
        } 
        else if (count(\$selectWhere) == 1) 
        {
            \$classRow = new ". $table[0] ."Row();
            foreach (\$selectWhere[0] as \$itemKey => \$itemValue) 
            {
                if(\$itemKey == self::\$table_id_column)
                {
                    \$classRow->triton_" . strtolower($table[0]) . "_id = [\$itemValue, self::\$table_id_column];
                }
                if (!is_int(\$itemKey)) {
                    \$classRow->\$itemKey[] = \$itemValue;
                }
            }
            return \$classRow;
        } 
        else 
        {
            return false;
        }
    }

    ";
        $funcArgs = array('str' => '');

        foreach($columns as $column){

            $funcArgs['str'] .= "$" . $column['Field'] . " = null,";
            $funcArgs[] = $column['Field'];

        }

        $funcArgs['str'] = rtrim($funcArgs['str'], ",");

        //var_dump($funcArgs , "\r\n");

        $tableFileContent .= '
        
    public static function count($where = null)
    {   
        $pdoConnection = $GLOBALS[\'Databases\'][\'' . ucfirst($database) . '\'];
        if(empty($where))
        {
            $count = $pdoConnection->query("SELECT COUNT('. $funcArgs[0] .') FROM ' . $table[0] . '")->fetchAll();
            return $count[0][0];
        }
        else 
        {
            $count = $pdoConnection->query("SELECT COUNT('. $funcArgs[0] .') FROM ' . $table[0] . ' WHERE " . $where .  " ")->fetchAll();
            return $count[0][0];
        }           
    }
    public static function orderBy($type, $limit = null, $where = null, $return = "object")
    {    
        $database = explode(DIRECTORY_SEPARATOR , __DIR__);
        $database = end($database);
        $table = explode(DIRECTORY_SEPARATOR, explode("Table" , __CLASS__)[0]);
        $table = end($table);    
        $pdoConnection = $GLOBALS[\'Databases\'][ucfirst($database)];        
        if(!empty($where)) 
        {            
            $where = "WHERE " . $where;         
        }       
        $orderBy = $pdoConnection->prepare("SELECT * FROM " . $table . " " . $where . " ORDER BY " . " " . self::$table_id_column . " "  . $type . (empty($limit) ? "" : " LIMIT " . $limit));
        $orderBy->execute();
        $selectOrderBy = $orderBy->fetchAll();  
        if($return == "array")
        {
            return $selectOrderBy;
        }
        if(count($selectOrderBy) > 1) 
        {    
            $classRow = new '. $table[0] .'Row();
            foreach ($selectOrderBy as $itKey => $itValue) 
            {    
                foreach ($itValue as $itemKey => $itemValue) 
                {
                    if (!is_int($itemKey)) 
                    {
                        $classRow->$itemKey[] = $itemValue;
                    }
                }
            }
            return $classRow;
        } 
        else if (count($selectOrderBy) == 1) 
        {
            $classRow = new '. $table[0] .'Row();
            foreach ($selectOrderBy[0] as $itemKey => $itemValue) 
            {
                if (!is_int($itemKey)) {
                    $classRow->$itemKey[] = $itemValue;
                }
            }
            return $classRow;
        } 
        else 
        {
            return false;
        }
    }    
        
    public static function add(' . $funcArgs['str'] . ')
    {
        $database = explode(DIRECTORY_SEPARATOR , __DIR__);
        $database = end($database);
        $table = explode(DIRECTORY_SEPARATOR, explode("Table" , __CLASS__)[0]);
        $table = end($table);
        $pdoConnection = $GLOBALS[\'Databases\'][ucfirst($database)];
        if(!is_array(func_get_arg(0))) 
        {
            $insertQueryString = "INSERT INTO " . $table  . " SET";
            $insertValueArray = [];
            $args = self::funcGetNamedParams();
            foreach($args as $funcArgKey => $funcArgValue) 
            {
                if(!empty($funcArgValue) || $funcArgValue === "0" || $funcArgValue === 0) 
                {
                    $insertQueryString .= " " . $funcArgKey . "= ?,";
                    $insertValueArray[] = $funcArgValue;
                }
            }
            $insertQueryString = rtrim($insertQueryString, ",");
            $insertQuery = $pdoConnection->prepare($insertQueryString);
            $insert = $insertQuery->execute($insertValueArray);
            if($insert === true) 
            {
                return array(true, $pdoConnection->lastInsertId());
            } else 
            {
                return array(false);
            }
        } 
        else 
        {
            $insertQueryString = "INSERT INTO " . $table  . " SET";
            $insertValueArray = [];
            $args = func_get_arg(0);
            foreach($args as $funcArgKey => $funcArgValue) 
            {
                if(!empty($funcArgValue)) {
                    $insertQueryString .= " " . $funcArgKey . "= ?,";
                    $insertValueArray[] = $funcArgValue;
                }
            }
            $insertQueryString = rtrim($insertQueryString, ",");
            $insertQuery = $pdoConnection->prepare($insertQueryString);
            $insert = $insertQuery->execute($insertValueArray);
            if($insert === true) 
            {
                return array(true, $pdoConnection->lastInsertId());
            } 
            else 
            {
                return array(false);
            }
        }
    }
    
    public static function find($id, $columns = null) 
    {
        $tableID = self::$table_id_column;
        $database = explode(DIRECTORY_SEPARATOR , __DIR__);
        $database = end($database);
        $table = explode(DIRECTORY_SEPARATOR, explode("Table" , __CLASS__)[0]);
        $table = end($table);
        $pdoConnection = $GLOBALS[\'Databases\'][ucfirst($database)];
        $columnString = "*";
        if(!empty($columns)) 
        {
            if(is_array($columns)) 
            {
                $columnString = "";
                foreach ($columns as $column) 
                {
                    $columnString .= $column . ", ";
                }
                $columnString = rtrim($columnString, ", ");
            }
            else 
            {
                $columnString = $columns;
            }
        }
        $selectRow = $pdoConnection->prepare("SELECT " . $columnString . " FROM " . $table . " WHERE " . $tableID . "=:id");
        $selectRow->execute([\'id\' => $id]);
        $'.$table[0].'Row = new '.$table[0].'Row();
        $i = 0;       
        $'.$table[0].'Row->triton_'.$table[0].'_id = array($id, $tableID);        
        $selectedRows = $selectRow->fetch(\PDO::FETCH_ASSOC);
        if($selectedRows != false) 
        {
            foreach ($selectedRows as $columnKey => $columnValue) 
            {
                $'.$table[0].'Row->$columnKey = $columnValue;
                $i++;
            }
            return $'.$table[0].'Row;
        }
        else 
        {
            return false;
        }
    }

    private static function funcGetNamedParams() 
    {
        $func = debug_backtrace()[1][\'function\'];
        $args = debug_backtrace()[1][\'args\'];
        $reflector = new \ReflectionClass(__CLASS__);
        $params = [];
        foreach($reflector->getMethod($func)->getParameters() as $k => $parameter)
        {
            $params[$parameter->name] = isset($args[$k]) ? $args[$k] : $parameter->getDefaultValue();
        }
        return $params;
    }
}        
';

        $rowFileContent = "<?php
namespace Database\\Databases\\" . ucfirst($database) . ";

class ". ucfirst($table[0]) . "Row
{
  public \$triton_".$table[0]."_id = null,  ". $funcArgs['str'] .";
  public function all()
  {
    \$array = [];";
        foreach($funcArgs as $funcArgKey => $funcArgValue)
        {
            if($funcArgKey != 'str' || $funcArgKey == '0')
            {
                $rowFileContent .= "if(is_array(\$this->". $funcArgValue . ")) foreach (\$this->". $funcArgValue . " as \$key => \$value) { \$array[\$key]['".$funcArgValue."'] = \$value; }; \r\n        " ;
            }
        }
        $rowFileContent .= "
    return \$array;
  }
    
    
    public function select(\$index) 
    {      
        \$classRow = new ". $table[0] ."Row();
        \$classRow->triton_" . strtolower($table[0]) . "_id = \$this->triton_" . strtolower($table[0]) . "_id;
";
        foreach($funcArgs as $funcArgKey => $funcArgValue) {
            if($funcArgKey != 'str' || $funcArgKey == "0") {
                $rowFileContent .= "\$classRow->". $funcArgValue . " = \$this->". $funcArgValue . "[\$index]; \r\n        " ;
            }
        }
        $rowFileContent .= "return \$classRow;      
    }        
            
    public function first() 
    {   
        if(is_array(\$this->".$funcArgs[0].")) 
        {        
            \$rowClass = new " . ucfirst($table[0]) . "Row();       
            \$rowClass->triton_" . strtolower($table[0]) . "_id = \$this->triton_" . strtolower($table[0]) . "_id;
            ";

        foreach($funcArgs as $funcArgKey => $funcArgValue) {

            if($funcArgKey != 'str' || $funcArgKey == "0") {

                $rowFileContent .= "\$rowClass->". $funcArgValue ." =  \$this->" . $funcArgValue . "[0]; 
            ";

            }

        }

        $rowFileContent .= "return \$rowClass;   
        } 
        else 
        {
            return \$this;
        }    
    }
            
    public function save() 
    {        
        \$database = explode(DIRECTORY_SEPARATOR , __DIR__);
        \$database = end(\$database);
        \$table = explode(DIRECTORY_SEPARATOR, explode(\"Row\" , __CLASS__)[0]);
        \$table = end(\$table);
        \$pdoConnection = \$GLOBALS['Databases'][ucfirst(\$database)];        
        if(debug_backtrace()[0]['type'] == '->') 
        {             
            if(empty(\$this->triton_".$table[0]."_id)) 
            {                
                \$insertQueryString = \"INSERT INTO \" . \$table . \" SET \";    
                \$insertValueArray = [];                 
                \$args = [];
                ";

                foreach($funcArgs as $funcArgKey => $funcArgValue) {

                    if($funcArgKey != 'str' || $funcArgKey == "0") {

                        $rowFileContent .= "\$args['". $funcArgValue ."'] =  \$this->" . $funcArgValue . ";
                " ;

                    }

                }
                $rowFileContent .= "
                foreach(\$args as \$funcArgKey => \$funcArgValue) 
                {
                    if(!empty(\$funcArgValue)) 
                    {
                        \$insertQueryString .= \" \" . \$funcArgKey . \"= ?,\";
                        \$insertValueArray[] = \$funcArgValue;
                    }
                }
                \$insertQueryString = rtrim(\$insertQueryString, \",\");                
                \$insertQuery = \$pdoConnection->prepare(\$insertQueryString);
                \$insert = \$insertQuery->execute(\$insertValueArray);
                if(\$insert === true) 
                {
                    return array(true, \$pdoConnection->lastInsertId());
                } 
                else 
                {
                    return array(false);
                }            
            } 
            else 
            {                          
                \$insertQueryString = \"UPDATE \" . \$table  . \" SET\";
                \$insertValueArray = [];             
                \$args = [];
            ";
        foreach($funcArgs as $funcArgKey => $funcArgValue) {
            if($funcArgKey != 'str' || $funcArgKey == "0") {
                $rowFileContent .= "    \$args['". $funcArgValue ."'] =  \$this->" . $funcArgValue . "; 
            " ;
            }
        }
        $rowFileContent .= "            
                foreach(\$args as \$funcArgKey => \$funcArgValue) 
                {
                    if(!empty(\$funcArgValue)) 
                    {
                        \$insertQueryString .= \" \" . \$funcArgKey . \"= ?,\";
                        \$insertValueArray[] = \$funcArgValue;
                    }
                }
    
                \$insertQueryString = rtrim(\$insertQueryString, \",\");
                \$insertQueryString .= ' WHERE ' . \$this->triton_".$table[0]."_id[1] . ' = ' . \$this->triton_".$table[0]."_id[0];                
                \$insertQuery = \$pdoConnection->prepare(\$insertQueryString);
                \$insert = \$insertQuery->execute(\$insertValueArray);
                if(\$insert === true) 
                {
                    return array(true, \$pdoConnection->lastInsertId());
                } 
                else 
                {
                    return array(false);
                }
            }
        }
    }
    
    public function delete() 
    {
        \$database = explode(DIRECTORY_SEPARATOR , __DIR__);
        \$database = end(\$database);
        \$table = explode(DIRECTORY_SEPARATOR, explode(\"Row\" , __CLASS__)[0]);
        \$table = end(\$table);
        \$pdoConnection = \$GLOBALS['Databases'][ucfirst(\$database)];        
        \$deleteQueryString = 'DELETE FROM  " . $table[0] . " ';
        \$deleteQueryString .= ' WHERE ' . \$this->triton_".$table[0]."_id[1] . ' = ' . \$this->triton_".$table[0]."_id[0];                
        \$deleteQuery = \$pdoConnection->prepare(\$deleteQueryString);
        \$delete = \$deleteQuery->execute();
        if(\$delete === true) {
            return array(true);
        } 
        else 
        {
            return array(false);
        }   
    }    
}";

        if(file_exists("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Table.php')) {

            $time = time();
            if(!is_dir("database/databases/" . $database . "/" . ucfirst($table[0]) . "Table")) {
                mkdir("database/databases/" . $database . "/" . ucfirst($table[0]) . "Table");
            }
            mkdir("database/databases/" . $database . "/" . ucfirst($table[0]) . "Table/" . ucfirst($table[0]) . 'Table_' . $time);

            rename("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Table.php', "database/databases/" . $database . "/" . ucfirst($table[0]) . "Table/" . ucfirst($table[0]) . 'Table_' . $time . '/' . ucfirst($table[0]) . 'Table');

        }

        if(file_exists("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Row.php')) {

            $time = time();
            if(!is_dir("database/databases/" . $database . "/" . ucfirst($table[0]) . "Table")) {
                mkdir("database/databases/" . $database . "/" . ucfirst($table[0]) . "Table");
            }

            rename("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Row.php', "database/databases/" . $database . "/" . ucfirst($table[0]) . "Table/" . ucfirst($table[0]) . 'Table_' . $time . '/' . ucfirst($table[0]) . 'Row');

        }

        file_put_contents("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Table.php', $tableFileContent);

        file_put_contents("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Row.php', $rowFileContent);

        if(file_exists("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Table.php')) {

            if(file_get_contents("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Table.php') == $tableFileContent) {

                echo "database/databases/" . $database . "/" . ucfirst($table[0]) . "Table.php oluşturuldu. \r\n";

            }

        }

        if(file_exists("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Row.php')) {

            if(file_get_contents("database/databases/" . $database . "/" . ucfirst($table[0]) . 'Row.php') == $rowFileContent) {

                echo "database/databases/" . $database . "/" . ucfirst($table[0]) . "Row.php oluşturuldu. \r\n";

            }

        }

    }


    public function createTableFiles($database) {

        $query = $this->prepare('show tables');
        $query->execute();
        $tables = $query->fetchAll();

        foreach($tables as $table){

            $this->createTableFile($table[0], $database);

        }

    }


}