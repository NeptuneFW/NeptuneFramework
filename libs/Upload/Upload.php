<?php
class Upload
{
    public static function getUpload($name, $new_name = true, $size = 2097152){
        if (isset($_FILES[$name])){
            $name = $_FILES[$name];
            $file_name = $name['name'];
            $file_tmp = $name['tmp_name'];
            $file_size = $name['size'];
            $file_error = $name['error'];
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));
            $allowed = array('txt', 'zip', 'rar', 'png', 'jpg', 'jpeg', 'gif', 'html', 'php');
            if (in_array($file_ext, $allowed)){
                if ($file_error === 0){
                    if ($file_size <= $size){
                        if ($new_name == false){
                            $file_name = $name['name'];
                            $file_destination = ROOT_DIR . '/../Resources/Uploads/' . $file_name;
                            if (move_uploaded_file($file_tmp, $file_destination)){
                                return $file_destination;
                            }
                        }else {
                            $file_name_new = uniqid('', true) . '.' . $file_ext;
                            $file_destination = ROOT_DIR . '/../Resources/Uploads/' . $file_name_new;
                            if (move_uploaded_file($file_tmp, $file_destination)){
                                return $file_destination;
                            }
                        }
                    }
                }
            }
        }
    }
}