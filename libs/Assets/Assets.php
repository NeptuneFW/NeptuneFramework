<?php

namespace Libs\Assets;

//----------------------------------------------------------------------------------------------------
// NEPTUNE FRAMEWORK BETA V1.0
//----------------------------------------------------------------------------------------------------
//
// Author     : Emirhan ENGIN <whitekod.com2001@gmail.com>
//              Mehmet Ali PEKER <thecoder@outlook.com.tr>
// Copyright  : Copyright (c) 2016-2017, NEPTUNE FRAMEWORK BETA V1.0
//
//----------------------------------------------------------------------------------------------------
class Assets
{
    public $assets = array(), $group, $returnedData = ' ',$assetsData = array();

    public function createAssetsGroup($group){
        $assets = $this->assets;
        if(array_search($group, $assets) == FALSE){
            $this->group = $group;
            $this->assets['group'][$group] = array( 'js' => array( 'preference' => 0, 'name' => null), 'css' => array('preference' => 0, 'name' => null), 'font' => array('preference' => 0, 'name' => null));
            return $this;
        }else if (array_search($group, $assets) === TRUE){
            $this->group = $group;
            echo $this->group;
            return $this;
        }
    }
    public function getAssetsGroup($group){
        $assets = $this->assets;
        $this->group = $group;
        return $this;
    }
    public function createAsset($name, $type, $url, $id, $preference = null){
        $assets = $this->assets;
        if(array_search($this->group,$assets) == FALSE) {
            $this->assets['group'][$this->group][] = array(
                'name' => $name,
                'type' => $type,
                'url' => $url,
                'preference' => $preference,
                'id' =>  $id
            );
            if($type == 'css'){
                $this->assets['group'][$this->group]['css'][] = $name;
            }else if($type == 'css-inline'){
                $this->assets['group'][$this->group]['css'][] = $name;
            }else if($type == 'js'){
                $this->assets['group'][$this->group]['js'][] = $name;
            }else if($type == 'js-inline'){
                $this->assets['group'][$this->group]['js'][] = $name;
            }else if($type == 'font'){
                $this->assets['group'][$this->group]['font'][] = $name;
            } else {
                $this->returnedData .= Error::show('Belirtilen tip bulunamadı.');
                return $this;
            }
        }else {
            return $this;
        }

        return $this;
    }
    public function useAsset($name){
        $useAsset = array();
        foreach($this->assets['group'][$this->group] as $asset){
            if($asset['name'] != null && $asset['name'] == $name ){
                $useAsset = $asset;
            }
        }
        $type = @$useAsset['type'];
        $id = @$useAsset['id'];
        $url = @$useAsset['url'];
        if($type != null) {
            if ($type == 'css') {
                //$url = RESOURCES_DIR . '/AssetsManager.php?id=' . $id;
                $exp = @explode(ROOT, $url);
                if (!isset($exp[1])) {
                    $this->returnedData .= '<link rel="stylesheet" href="' . $url . '"/>';
                }else {
                    $url = BASE_URL . "/public/AssetsManager.php?id=" . $id;
                    $this->returnedData .= '<link rel="stylesheet" href="' . $url . '"></script>';
                }
                return $this;
            } else if ($type == 'css-inline') {
                \Libs\CSS\CSS::$variables = 	extract($this->assetsData);

                ob_start();
                echo "<style>";
                require "$url";
                echo "</style>";
                $this->returnedData .=  ob_get_clean();
                return $this;
            } else if ($type == 'js') {
                $exp = @explode(ROOT, $url);
                //print_r($exp);
                if (!isset($exp[1])) {
                    $this->returnedData .= '<script src="' . $url . '"></script>';
                }else {
                    $url = BASE_URL . "/public/AssetsManager.php?id=" . $id;
                    $this->returnedData .= '<script src="' . $url . '"></script>';
                }
                return $this;
            } else if ($type == 'font') {
                $this->returnedData .= '<link rel="stylesheet" href="' . $url . '" />';
                return $this;
            } else if ($type == 'js-inline') {
                JS::$variables = extract($this->assetsData);
                ob_start();
                echo "<script>";
                require "$url";
                echo "</script>";
                $this->returnedData .= ob_get_clean();
                return $this;
            } else {
                $this->returnedData .= Error::show('Belirtilen tip bulunamadı.');
                return $this;
            }
        }

    }

    /**
     *
     * @param $type
     * @return $this
     */
    public function useAllAssets($type){
        $this->returnedData = '';
        $AssetsGroup = HelperFunctions::array_natcase('preference', $this->assets['group'][$this->group]);
        $names = array();
        unset($AssetsGroup[$type]['preference']);
        foreach($AssetsGroup[$type] as $key => $value) {
            array_push($names, $value);
        }
        foreach($names as $value){
            $this->useAsset($value);
        }
        return $this;
    }
}
$assets = new Assets();
