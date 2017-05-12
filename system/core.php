<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 11.04.2017
 * Time: 22:51
 */

namespace System;

trait Core {

    public $assets;

    public function __construct()
    {

        $this->assets = new \Libs\Assets\Assets();

        $this->assets->createAssetsGroup("main")
            ->createAsset("roboto", "font", "https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en", "roboto")
            ->createAsset("materialIcons", "css", "https://fonts.googleapis.com/icon?family=Material+Icons", "materialIcons")
            ->createAsset("materialColorPalette", "css", "https://code.getmdl.io/1.3.0/material.grey-orange.min.css", "materialColorPalette")
            ->createAsset('jquery', 'js', SCRIPT_DIR . '/admin/jquery-1.10.2.js', 'jquery')
            ->createAsset('homeJS', 'js', SCRIPT_DIR . '/home.js', 'homeJS')
            ->createAsset("materialJS", "js", "https://code.getmdl.io/1.3.0/material.min.js", "materialJS")
        ->createAssetsGroup('blog')
            ->createAsset("blogStyle", "css", STYLE_DIR . "/styles.css", "blogStyle")
        ->createAssetsGroup("dashboard")
            ->createAsset("flexible", "css", STYLE_DIR . '/flexible.css', "flexible")
            ->createAsset("animate", "css", STYLE_DIR . '/admin/animate.min.css', "animate")
            ->createAsset("bootstrap", "css", STYLE_DIR . '/admin/bootstrap.min.css', "bootstrap")
            ->createAsset("bootstrapdashboard", "css", STYLE_DIR . '/admin/light-bootstrap-dashboard.css', "bootstrapdashboard")
            ->createAsset("demo", "css", STYLE_DIR . '/admin/demo.css', "demo")
            ->createAsset("pe-icon-7-stroke", "css", STYLE_DIR . '/admin/pe-icon-7-stroke.css', "pe-icon-7-stroke")
            ->createAsset("pe-icon-7-strokeFontEot", "css", STYLE_DIR . '/admin/pe-icon-7-stroke.eot', "pe-icon-7-strokeFontEot")
            ->createAsset("pe-icon-7-strokeSVG", "css", STYLE_DIR . '/admin/pe-icon-7-stroke.svg', "pe-icon-7-strokeSVG")
            ->createAsset("pe-icon-7-strokeTTF", "css", STYLE_DIR . '/admin/pe-icon-7-stroke.ttf', "pe-icon-7-strokeTTF")
            ->createAsset("pe-icon-7-strokeWOFF", "css", STYLE_DIR . '/admin/pe-icon-7-stroke.woff', "pe-icon-7-strokeWOFF")
            ->createAsset('jquery', 'js', SCRIPT_DIR . '/admin/jquery-1.10.2.js', 'jquery')
            ->createAsset('bootstrapJS', 'js', SCRIPT_DIR . '/admin/bootstrap.min.js', 'bootstrapJS')
            ->createAsset('bootstrapCRS', 'js', SCRIPT_DIR . '/admin/bootstrap-checkbox-radio-switch.js', 'bootstrapCRS')
            ->createAsset('bootstrapNotify', 'js', SCRIPT_DIR . '/admin/bootstrap-notify.js', 'bootstrapNotify')
            ->createAsset('bootstrapSelect', 'js', SCRIPT_DIR . '/admin/bootstrap-select.js', 'bootstrapSelect')
            ->createAsset('charlist', 'js', SCRIPT_DIR . '/admin/chartist.min.js', 'charlist')
            ->createAsset('demoJS', 'js', SCRIPT_DIR . '/admin/demo.js', 'demoJS')
            ->createAsset('lightBootstrap', 'js', SCRIPT_DIR . '/admin/light-bootstrap-dashboard.js', 'lightBootstrap')
        ->createAssetsGroup('login')
            ->createAsset('jquery', 'js', SCRIPT_DIR . '/admin/jquery-1.10.2.js', 'jquery')
            ->createAsset("loginCSS", "css", STYLE_DIR . "/admin/login.css", "loginCSS")
            ->createAsset("loginJS", "js", SCRIPT_DIR . "/admin/login.js", "loginJS")
        ->createAssetsGroup('tinyMCE')
            ->createAsset('tinyMCEJS', 'js', '//cdn.tinymce.com/4/tinymce.min.js', 'tinyMCEJS')
            ->createAsset('tinyMCEJSR', 'js', SCRIPT_DIR . '/admin/tiny.js', 'tinyMCEJSR');




    }

}
