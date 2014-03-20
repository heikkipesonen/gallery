<?php
ini_set('memory_limit', '-1'); // hax
class SimpleImage {
 
    private $image;
  private $image_type;
  private $filename;
  private $type;
   private $path;


   public function __construct($path=null, $filename = null){
      if ($filename != null){
         $this->path = $path;
         $this->filename = $filename;
         $this->load($path, $filename);
      }
   }

   public function setType($d){
      if ($d == 'image/png'){
         $this->image_type = IMAGETYPE_PNG;
         return true;
      } else if ($d == 'image/jpg' || $d=='image/jpeg'){                  
         $this->image_type = IMAGETYPE_JPEG;
         return true;
      } else if ($d == 'image/gif'){
         $this->image_type = IMAGETYPE_GIF;
         return true;
      } else {
         return false;
      }
   }

  public function load($path, $filename) {
      $filename = $this->path.'/'.$this->filename;

      if (file_exists($filename)){        
         $image_info = getimagesize($filename);         
         $this->image_type = $image_info[2];
         if( $this->image_type == IMAGETYPE_JPEG ) { 
            $this->image = imagecreatefromjpeg($filename);
         } elseif( $this->image_type == IMAGETYPE_GIF ) {
    
            $this->image = imagecreatefromgif($filename);
         } elseif( $this->image_type == IMAGETYPE_PNG ) {         
            
            $this->image = imagecreatefrompng($filename);
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            //$transparent = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
            //imagefilledrectangle($this->image, 0, 0, $this->getWidth(), $this->getWidth(), $transparent);
         }
      } else {         
         $this->setError();
      }
   }

   public function setError($size = null){      
      if ($size == null || !is_array($size)){         
         $size = array(256,256);
      }
      //$this->setType('image/png');
      $this->image_type = IMAGETYPE_PNG;
      $this->image = imagecreatetruecolor($size[0],$size[1]);
      $text_color = imagecolorallocate($this->image, 255, 14, 91);
      $img_color = imagecolorallocate($this->image, 100, 0, 0);
      imagefill($this->image, 0, 0,$img_color);
      //imagestring($this->image, 5, $size[0]/5, $size[1]/2,  'no image available', $text_color);
   }

   public function loadBase64($string){
      $parts = split(',', $string);
      //$type = split(';',substr($parts[0],5))[0];
      $imagefile = base64_decode($parts[1]);      
      $f = finfo_open();
      $type = finfo_buffer($f, $imagefile,FILEINFO_MIME_TYPE); 

      $isimage = $this->setType($type);
      
      if ($isimage){                 
         //$this->loadString($imagefile);
         $this->image = imagecreatefromstring($imagefile);
         return true;  
      } else {
         return false;
      }
   }

   public function loadString($data) {              
     $this->image = imagecreatefromstring($data);     
   }

   public function setName($filename){
      $this->filename = $filename;
   }

   public function setPath($path){
      $this->path = $path;
   }

   public function save($image_type=null, $compression=75, $permissions=null) {         

      if ($image_type == null){         
         $image_type = $this->image_type;
      }

      if( $image_type == IMAGETYPE_JPEG ) {         
         $success = imagejpeg($this->image,$this->path.'/'.$this->filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         
         $success = imagegif($this->image,$this->path.'/'.$this->filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         
         imagealphablending($this->image, true);
         imagesavealpha($this->image, true);
         $success = imagepng($this->image,$this->path.'/'.$this->filename);
      }
     

      if( $permissions != null) {
         chmod($this->path.$this->filename,$permissions);
      }

      return $success;
   }

   public function output($image_type=null) {
      if ($image_type == null){
         $image_type = $this->image_type;
      }

      if( $image_type == IMAGETYPE_JPEG ) {
         header('Content-type:image/jpeg');
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         header('Content-type:image/gif');
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {         
         header('Content-type:image/png');
         imagepng($this->image);
      }
   }

   public function getWidth() {
 
      return imagesx($this->image);
   }

   public function getHeight() {
 
      return imagesy($this->image);
   }

   public function getSize(){
      return Array($this->getWidth(),$this->getHeight());
   }

   public function resizeToMax($size){
      if ($this->getWidth() > $size){
         $this->resizeToWidth($size);
      }

      if ($this->getHeight() > $size){
         $this->resizeToHeight($size);
      }
   }

   public function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   public function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   public function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }

   public function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      if( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG ) {
         $current_transparent = imagecolortransparent($this->image);
         if($current_transparent != -1) {
            $transparent_color = imagecolorsforindex($this->image, $current_transparent);
            $current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($new_image, 0, 0, $current_transparent);
            imagecolortransparent($new_image, $current_transparent);
         } elseif( $this->image_type == IMAGETYPE_PNG) {
            imagealphablending($new_image, false);
            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
            imagesavealpha($new_image, true);
         }
      }
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image; 
   }  

   public function filter($filter){
      $filter = strtolower($filter);
      switch ($filter){
         case 'grayscale':
            imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
         break;

      }
   }

   public function crop($thumb_width,$thumb_height){
      $width = $this->getWidth();
      $height = $this->getHeight();

      $original_aspect = $width / $height;
      $thumb_aspect = $thumb_width / $thumb_height;

      if ( $original_aspect >= $thumb_aspect )
      {
         // If image is wider than thumbnail (in aspect ratio sense)
         $new_height = $thumb_height;
         $new_width = $width / ($height / $thumb_height);
      }
      else
      {
         // If the thumbnail is wider than the image
         $new_width = $thumb_width;
         $new_height = $height / ($width / $thumb_width);
      }

      $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

      // Resize and crop
      imagecopyresampled($thumb,
                         $this->image,
                         0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                         0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                         0, 0,
                         $new_width, $new_height,
                         $width, $height);
      
      $this->image = $thumb;
   }

}

?>