<?php

namespace Tarvos;
use Libs\Languages as Languages;

class Tarvos
{
    public $tarvosPath = ROOT . DS . 'resources\view' . DS;
    public function render($tarvosPage, $vars = array(), $cache = false, $cache_time = 3)
    {
        $path = $this->tarvosPath . $tarvosPage . '.tarvos.php';
        if (file_exists($path))
        {

            $contents = file_get_contents($path);
            if($cache === true) {
                $cache = new TarvosCache($path, $contents, $cache_time, $vars);
            }
            else {
                ob_start();
                global $route;
                extract($vars, EXTR_SKIP);
                $content = $contents;
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
                    '/\@if (.*?);/' => '<?php if ($1) : ?>',
                    '/\@else/' => '<?php else : ?>',
                    '/\@endif/' => '<?php endif; ?>',


                ];

                $pattern = array_keys($template_engine);

                $replacement = array_values($template_engine);

                $content = preg_replace($pattern, $replacement, $content);


                eval("?>" . $content);

            }

        }
        else
        {
            echo ErrorHandler::show('Belirtilen görüntü dosyası yüklenemedi.');
        }
    }
}