<?php

namespace Libs\Errors;

class ErrorHandler
{
    protected $errors = [];
    public function addError($error, $key = null)
    {
        if ($key)
        {
            $this->errors[$key][] = $error;
        }
        else
        {
            $this->errors[] = $error;
        }
    }
    public function all($key = null)
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : $this->errors;
    }
    public function hasErrors()
    {
        return count($this->all()) ? true : false;
    }
    public function first($key)
    {
        echo '
            <style>
                .msg {
                    padding: 15px 25px;
                    margin: 25px;
                    font-family: Verdana;
                    font-size: 14px;
                }
                
                .error {
                    color: hsla(0, 95%, 35%, 1);
                    border: 1px solid hsla(0, 95%, 35%, 1);
                    background-color: hsla(0, 15%, 97%, 1);
                    color: hsla(9, 87%, 46%, 1);
                    border: 1px solid hsla(9, 87%, 46%, 1);
                    background-color: hsla(9, 15%, 97%, 1);
                }
            </style>
        ';
        echo isset($this->all()[$key][0]) ? '<div class="msg error">' . ucfirst($this->all()[$key][0]) . '</div>' : false;
    }
    public static function show($errorText)
    {
        echo '<style>.msg {padding: 15px 25px;margin: 25px;font-family: Verdana;font-size: 14px;}.error {color: hsla(0, 95%, 35%, 1);border: 1px solid hsla(0, 95%, 35%, 1);background-color: hsla(0, 15%, 97%, 1);color: hsla(9, 87%, 46%, 1);border: 1px solid hsla(9, 87%, 46%, 1);background-color: hsla(9, 15%, 97%, 1);}</style>';
        return '<div class="msg error">'.$errorText.'</div>';
    }
    public static function page404()
    {
        echo '<style>html,body{height:100%;overflow:hidden;}.error-page{display:flex;align-items:center;justify-content:center;text-align: center;height: 100%;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;}.error-page h1 {font-size: 30vh;font-weight: bold;position: relative;margin: -8vh 0 0;padding: 0;color: transparent;background-image: url(\'http://payload385.cargocollective.com/1/12/409842/10017846/Gradient_00020.gif\');background-size: 4em auto;}.error-page h1:before {content: \'404\';background: #fff;color: #333;display: block;mix-blend-mode: lighten;}.error-page h1:after {content: \'404\';position: absolute;top: 0;left: 0;right: 0;color: transparent;text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.25);-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}.error-page h1 + p {color: #D53939;font-size: 8vh;font-weight: bold;line-height: 8vh;position: relative;}.error-page h1 + p:after {content: \'404 Error!\';position: absolute;top: 0;left: 0;right: 0;color: transparent;text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}#particles-js {position: fixed;top: 0;right: 0;bottom: 0;left: 0;}</style><div class="error-page"><div><h1></h1><p>404 Error!</p></div></div><div id="particles-js"></div><script src="https://code.jquery.com/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script><script>particlesJS("particles-js", {"particles": {"number": {"value": 5,"density": {"enable": true,"value_area": 800}},"color": {"value": "#f8f8f8"},"shape": {"type": "circle",},"opacity": {"value": 0.5,"random": true,"anim": {"enable": false,"speed": 1,"opacity_min": 0.2,"sync": false}},"size": {"value": 140,"random": false,"anim": {"enable": true,"speed": 10,"size_min": 40,"sync": false}},"line_linked": {"enable": false,},"move": {"enable": true,"speed": 8,"direction": "none","random": false,"straight": false,"out_mode": "out","bounce": false,"attract": {"enable": false,"rotateX": 600,"rotateY": 1200}}},"interactivity": {"detect_on": "canvas","events": {"onhover": {"enable": false},"onclick": {"enable": false},"resize": true}},"retina_detect": true});</script>';
    }
}