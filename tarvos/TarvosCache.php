<?php

namespace Tarvos;

use Libs\Languages as Languages;

class TarvosCache {

    private  $cache = null;
    private  $time = 3;
    private  $status = 0;
    private  $dir = "cache";
    private  $buffer=false;
    private  $vars = null;
    private  $start=null;
    private  $load=false;
    private  $contents= "";
    private  $type=true;
    private  $extension=".ntcache";
    private  $active=true;
    public function __construct($path, $contents,$cache_time = 3,$vars, $active=true){
        $this->active=$active;
        $this->contents = $contents;
        $this->cache_time = $cache_time;
        $this->vars = $vars;
        if ($active) {

            if ($this->type) {

                if(!file_exists(dirname(__FILE__)."/".$this->dir)){
                    mkdir(dirname(__FILE__)."/".$this->dir, 0777);
                }
                if ($this->load) {
                    list($time[1], $time[0]) = explode(' ', microtime());
                    $this->start = $time[1] + $time[0];
                }


                $this->cache  =  dirname(__FILE__)."/".$this->dir."/".md5($path).$this->extension;
                if(time() - $this->time < @filemtime($this->cache)) {
                    readfile($this->cache);
                    $this->status=1;
                    die();
                }else {

                    @unlink($this->cache);
                    ob_start();
                }
            }
        }
    }
    private function writeCache($content){
        global $route;
        ob_start();
        extract($this->vars, EXTR_OVERWRITE);
        $file = fopen($this->cache, 'w');
        $extend_isset = preg_match('/\@extend (.*)\;/', $content, $extend);
        if ($extend_isset === 1) {
            $extend_file = file_get_contents(__DIR__ . '/../resources/view/layouts/' . $extend[1] . ".tarvos.php");
            $content = preg_replace('/\@extend (.*)\;/', $extend_file, $content);
        }
        $extend = array();
        $partial_isset = preg_match_all('/\@partial (.*)\;/', $content, $extend);
        if ($partial_isset >= 1) {
            $i = 0;
            while ($partial_isset > $i) {
                $partial = file_get_contents(__DIR__ . '/../resources/view/partials/' . $extend[1][$i] . ".tarvos.php");
                $extend[1][$i] = preg_replace('/\//', '\\/', $extend[1][$i]);
                $content = preg_replace('/\@partial ' . $extend[1][$i] . '\;/', $partial, $content);
                $extend2 = array();
                $partial_isset2 = preg_match_all('/\@partial (.*)\;/', $content, $extend2);
                if ($partial_isset2 >= 1) {
                    $j = 0;
                    while ($partial_isset2 > $j) {
                        $partial = file_get_contents(__DIR__ . '/../resources/view/partials/' . $extend2[1][$j] . ".tarvos.php");
                        $extend2[1][$j] = preg_replace('/\//', '\\/', $extend2[1][$j]);
                        $content = preg_replace('/\@partial ' . $extend2[1][$j] . '\;/', $partial, $content);
                        $j++;
                    }

                }
                $i++;
            }

        }
        echo $content;

        $content = ob_get_clean();
        if (ob_get_level() > 0) ob_flush();
        preg_match_all('/@section \((.*?)\)(.*?)@endsection/ms', $content, $match);
        array_shift($match);
        $say = count($match);
        for ($i = 0; $i < $say - 1; $i++) {
            $c = array_combine($match[$i], $match[$i + 1]);
        }
        foreach ($c as $k => $v) {
            $content = preg_replace("/@yield\($k\)/ms", $v, $content);
        }
        $content = preg_replace('/[\r\n]*@section .*?@endsection[\r\n]*/ms', '', $content);
        $content = preg_replace('/[\r\n]*@yield.*?\)[\r\n]*/ms', '', $content);
        $template_engine = [

            "/(\w+)\:(\w+)/" => '$$1["$2"]',
            "/[^\\\\]{{ (.*?) }}/" => "<?php echo \"\$1\"; ?>",
            "/[^\\\\]{ (.*?) }/" => "<?php echo \$1; ?>",
            "/[^\\\\]{! (.*?) !}/" => "<?php print_r(\$1); ?>",
            "/[^\\\\]{# (.*?) #}/" => "<!-- $1 -->",
            '/\{\? (.*) \?}/' => "<?php var_dump(\$1); ?>",
            '/\@for (.*) as (.*);/' => '<?php foreach($$1 as $$2) : ?>',
            '/\@for (.*),(.*),(.*);/' => '<?php for($1; $2; $3) : ?>',
            '/\@endfor;/' => '<?php endfor; ?>',
            '/\@endforeach;/' => '<?php endforeach; ?>',
            '/\@elseif (.*);/' => '<?php else if ($1) : ?>',
            '/\@if (.*);/' => '<?php if ($1) : ?>',
            '/\@else/' => '<?php else : ?>',
            '/\@endif/' => '<?php endif; ?>',


        ];

        $pattern = array_keys($template_engine);

        $replacement = array_values($template_engine);

        $content = preg_replace($pattern, $replacement, $content);

        eval("?>" . $content);

        $content= ob_get_flush();
        @fwrite($file, $content);
        fclose($file);
    }
    public function clearCache(){
        $dir = opendir($this->dir);
        while (($file = readdir($dir)) !== false)
        {
            if(! is_dir($file)){
                unlink($this->dir."/".$file);
            }}
        closedir($dir);

    }
    public function __destruct(){
        if ($this->active) {

            if ($this->type) {
                if ($this->status==0) {
                    if ($this->buffer) {
                        $this->writeCache($this->buffer(ob_get_contents()));
                    }else{
                        $this->writeCache($this->contents);
                    }

                }
                if ($this->load) {
                    list($time[1], $time[0]) = explode(' ', microtime());
                    $finish = $time[1] + $time[0];
                    $total_time = number_format(($finish - $this->start), 6);
                    echo "Load Time (S) :  {$total_time} ";
                }

                ob_end_flush();
            }
        }
    }
}