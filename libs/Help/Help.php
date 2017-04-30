<?php
class Help
{
    public static $helps = [
        'Input' => [
            'name' => 'Input',
            'description' => 'Input sınıfı, gelen post, get isteklerinizi güvenli bir şekilde almanızı sağlar. Ya da bir post, get isteği var mı şeklinde bir olanak sunar. Input::get("test") dediğiniz zaman gelen isteğe göre veriyi alır. Input::exists("POST"){}, Input::exists("GET"){} ise gelecek isteğe göre işlem yapıcağımızı belirtir. Standart değer olarak POST gelir ama GET olarak da istek alabilirsiniz.',
            'link' => 'link.com'
        ],
        'Validator' => [
            'name' => 'Validator',
            'description' => 'Validator, güvenlik önlemleri içindir. Post olarak gelen değer boş mu, en az/fazla kaç karakter gibi kurallar ile kullanıcıya zorunluluklar belirtiyoruz. Alacağı değerler; required, minlength, maxlength, match, regex\'dir. Required; true olarak belirlendiği zaman kullanıcı boş veri gönderemez. Max/Min length; karakter sayısını kontrol etmemizi sağlar. Match; Girilen değer hangi alan ile eşleşmesini istiyorsak onun adını yazarak buna olanak sağlamış oluruz. "match" => "password" gibi. Regex; Karşılaştırma yapmamızı sağlar.',
            'link' => 'link.com'
        ]
    ];
    public static function getAllHelp(){
        $helps = self::$helps;
        echo '<pre>', print_r($helps), '</pre>';
    }
    public static function getHelp($helpName){
        $helps = self::$helps;
        echo '<pre>', print_r($helps[$helpName]), '</pre>';
    }
}