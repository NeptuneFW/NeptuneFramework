<?php
$SCRIPT_NAME = explode('index.php', $_SERVER['SCRIPT_NAME']);
$SCRIPT_NAME = explode('/', $_SERVER['SCRIPT_NAME']);
define('DS', '/');
define('SD', '\\');
define('OS', ':');
define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . OS . DS . DS . $_SERVER['SERVER_NAME'] . OS . $_SERVER['SERVER_PORT'] . DS . $SCRIPT_NAME[1]);
define('ROOT_DIR', realpath(__DIR__));
define('ROOT', realpath(__DIR__ . "/../"));
if(preg_match('/\/public/', BASE_URL))
{
    define('IMAGES_URL', BASE_URL . '/images');
}
else
{
    define('IMAGES_URL', BASE_URL . '/public/images');
}
define('IMAGES_DIR', ROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images');
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('STYLE_DIR', ROOT . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "style");
define('SCRIPT_DIR', ROOT . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "script");


require "../libs/Assets/Assets.php";
require "../libs/Upload/Upload.php";
require "../libs/errors/ErrorHandler.php";
require "../libs/Validator/Validator.php";
require "../libs/CSS/CSS.php";
require "../System/Cookie.php";
require "../System/core.php";

class AssetsManager
{
	use \System\Core;

	public function index()
	{
		if (isset($_GET['id'])) {

		    $found = 0;
            foreach ($this->assets->assets['group'] as $group)
            {
                if($found == 1) exit;

                foreach ($group as $asset)
                {
                    if($found == 1) exit;
                    if(isset($asset['id']))
                    {
                        if($asset['id'] == $_GET['id'])
                        {
                            if($asset['type'] == 'css')
                            {
                                header("Content-type: text/css");
                            }
                            else if($asset['type'] == 'js')
                            {
                                header("Content-type: text/css");
                            }
                            require $asset['url'];
                            $found = 1;
                        }
                    }
                }
            }
        }
	}
}
$new = new AssetsManager();
$new->index();
