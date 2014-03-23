<?php
if (isset($_REQUEST['gallery']) && isset($_REQUEST['slug']) && isset($_REQUEST['photo'])){
  $g = Util::clearXss($_REQUEST['gallery']);
  $s = Util::clearXss($_REQUEST['slug']);
  $hasKey = false;

  $client = ORM::for_table('client')->where('slug',$s)->find_one();


  if (is_object($client)){
		if (isset($_REQUEST['key'])){
			$k = Util::clearXss($_REQUEST['key']);
			$rightKey = ORM::for_table('gallery')->where('id',$g)->where('key',$k)->find_one();

			if (is_object($rightKey)){
				$hasKey = true;
			}
		}

		$auth = false;
		if (Photo::isGalleryOwner($_REQUEST['gallery'])) $auth = true;
		if (Photo::isAdmin()) $auth = true;
		if ($hasKey == true) $auth = true;

        if ($auth == true){

        $clientPath = 'uploads/'.$client->slug.'/'.$g;
        $photos = ORM::for_table('photo')->where('gallery_id',$g)->find_many();
        $gallery = ORM::for_table('gallery')->where('id',$g)->find_one();

        if (isset($_REQUEST['photo'])){
        	$p_id = Util::alphaNum($_REQUEST['photo']);
        	$photo = ORM::for_table('photo')->where('id',$p_id)->find_one();
        }

?>
<div class="preview-gallery-header">
	<h4><?php if (Photo::isGalleryOwner($gallery->id) || Photo::isAdmin() ){
			?>
		<a href="/<?php echo BASE_URL.'/gallerylist/'.$client->slug; ?>"><?php echo $client->name; } 

		if (Photo::isGalleryOwner($gallery->id) || Photo::isAdmin() ){?></a>/<?php } ?><a href="/<?php echo BASE_URL.'/gallery/'.$client->slug.'/'.$gallery->id.'/'.$gallery->key; ?>"><?php echo $gallery->name; ?></a>/<?php echo $photo->name;?></h4>

</div>

	<div class="img-container">
	<?php
if (is_object($photo)){
		$img = Util::imageResizeToMax($clientPath.'/',$photo->file,800);

	?>
	    <div class="image-big">    	
			<img src="<?php echo '/'.BASE_URL.'/'.$img; ?>" alt="<?php echo $photo->name; ?>">
	    </div>
	<?php
}
	?>


	</div>


	<div class="img-gallery-scroller">		
		<div class="img-gallery-scroll" style="width:<?php echo count($photos) * 96; ?>px">			
<?php
        foreach ($photos as $image){
			$selected = '';
   			if (isset($photo)){
   				if ($photo->id === $image->id){
   					$selected = 'selected';
   				}
   			}

			$img = Util::imageResize($clientPath.'/',$image->file);        	
        	?>
		    <div class="image-preview <?php echo $selected; ?>" >    	
		    	<a href="<?php echo '/'.BASE_URL.'/photos/'.$client->slug.'/'.$gallery->id.'/'.$gallery->key.'/'.$image->id; ?> ">
					<img src="<?php echo '/'.BASE_URL.'/'.$img; ?>" alt="<?php echo $image->name; ?>">
				</a>
		    </div>
        	<?php
        }
?>
		<div class="clear"></div>
		</div>
	</div>

<?php
      }
    }
}
?>