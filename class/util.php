<?php
class Util{
  
  public static function clearXss($str){
    return htmlspecialchars($str);
  }

    public static function convert_size_to_num($size)
    {
        //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
        $l = substr($size, -1);
        $ret = substr($size, 0, -1);
        switch(strtoupper($l)){
            case 'P':
                $ret *= 1024;
            case 'T':
                $ret *= 1024;
            case 'G':
                $ret *= 1024;
            case 'M':
                $ret *= 1024;
            case 'K':
                $ret *= 1024;
                break;
        }
        return $ret;
    }

    public static function get_max_fileupload_size()
    {
        $max_upload_size = min(Util::convert_size_to_num(ini_get('post_max_size')), Util::convert_size_to_num(ini_get('upload_max_filesize')));

        return $max_upload_size;
    }

    public static function generateRandomString() {
        return md5(microtime().rand());
    }
  
    public static function generateRandomToken($length){
        //allowed characters
        $chars = "abcdefghijkmnpqrstuvxyz23456789";
        $str = "";
        //add a random char until the length is filled
        while (strlen($str) < $length){
          $str .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
  
    public static function reArrayFiles($file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    public static function uploadImage($folder, $file) {
        $errorMessage = "";
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $file["name"]);
        $extension = strtolower(end($temp));
        $data['name'] = $file['name'];

        if ((($file["type"] == "image/gif")
                || ($file["type"] == "image/jpeg")
                || ($file["type"] == "image/jpg")
                || ($file["type"] == "image/pjpeg")
                || ($file["type"] == "image/x-png")
                || ($file["type"] == "image/png"))
            && ($file["size"] < IMAGE_MAXSIZE)
            && in_array($extension, $allowedExts)){


            if ($file["error"] > 0) {
                $errorMessage .= "Tiedoston siirtäminen palvelimelle epäonnistui. <br />";
            } else {
                $imageName = Util::generateRandomString() . "." . $extension;
                if (file_exists("uploads/".$folder.'/' . $imageName)) {
                    $file_exists = true;
                    $errorMessage .= 'Kuva on jo ladattu palvelimelle. <br />';
                } else {
                    move_uploaded_file($file["tmp_name"],
                        "uploads/".$folder.'/' . $imageName);

                }
            }
        }
        $data['errorMessage'] = $errorMessage;
        if(!isset($imageName)) $imageName = null;
        $data['imageName'] = $imageName;
        return $data;
    }
  
    public static function alphaNum($str){
        return preg_replace("/[^A-Za-z0-9 ]/", '', $str);
    }


    public static function imageResizeToMax($path,$img,$max){
        if (!empty($img) && $img!='' && strlen($img)>0 && is_string($img)){            
            $im = explode('.', $img);
            $filename = $im[0].'_max'.$max.'.'.$im[1];
            
            if (file_exists($path.'/'.$filename)){
                //return UPLOADPATH.'uploads/'.$filename;
                return $path.'/'.$filename;
            } else {
               // if its not, and the original file exists, try to resize          
                    $image = new SimpleImage($path,$img);   
                    $image->resizeToMax($max);
                    $image->setName($filename);
                    $image->save();
                    // because of window machines.....
                    return $path.'/'.$filename;
            }
        }
        return false;        
    }

    public static function imageResize($path, $img, $w=200, $h=200){

        if (!empty($img) && $img!='' && strlen($img)>0 && is_string($img)){
            // generate new file name: [IMAGE_NAME]_[WIDTH]x[HEIGHT].[EXTENSION]            
            $im = explode('.', $img);

            if ($h == false){
                $filename = $im[0].'_w'.$w.'.'.$im[1];
            } else if ($w == false){
                $filename = $im[0].'_h'.$h.'.'.$im[1];
            } else {
                $filename = $im[0].'_'.$w.'x'.$h.'.'.$im[1];
            }

            // SimpleImage.php -- image resizing
            // if image is resized already...
            if (file_exists($path.'/'.$filename)){
                //return UPLOADPATH.'uploads/'.$filename;
                return $path.'/'.$filename;
            } else {
                // if its not, and the original file exists, try to resize          
                    $image = new SimpleImage($path,$img);   
                    
                    if ($h == false){
                        $image->resizeToWidth($w);
                    } else if ($w == false) {
                        $image->resizeToWidth($h);
                    } else {                        
                        $image->crop($w,$h);
                    }

                    $image->setName($filename);
                    $image->save();
                    //return UPLOADPATH.'uploads/'.$filename; 


                    // because of window machines.....
                    return $path.'/'.$filename;
            }
        }

        // when nothing else was found....
        return false;
    }

    public static function getSqlTime($unixTimestamp){
        return date("Y-m-d H:i:s", $unixTimestamp);  
    }

    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public static function getBrowser(){
        $BROWSER = true;

        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
            $BROWSER = 'android';
        }
        
        if(preg_match('/(?i)msie [8-9]/',$ua)){
            $BROWSER = 'ie';
        }

        if(preg_match('/(?i)msie [2-7]/',$ua)){
            $BROWSER = false;
        }

        if (strpos($ua, 'iphone')!==false || strpos($ua, 'ipad')!==false){
            $BROWSER = 'ios';
        }

        return $BROWSER;
    }     
}