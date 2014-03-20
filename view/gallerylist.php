<?php

if (isset($_SESSION['slug']) && !isset($_REQUEST['slug'])){
	$_REQUEST['slug'] = $_SESSION['slug'];
}

if (isset($_REQUEST['slug'])){

	$slug = Util::alphaNum($_REQUEST['slug']);

	$client = ORM::for_table('client')->where('slug',$slug)->find_one();
	if (is_object($client)){

		$galleries = ORM::for_table('gallery')->where('client_id',$client->id)->find_many();
?>		
<div class="jumbotron no-margin">
	<h2>Galleriat</h2>
	<h4><?php echo $client->name; ?></h4>

</div>
<?php if (Photo::isAdmin()){ ?>
<div class="toolbar button-tools button-group">	
	<a href="/<?php echo BASE_URL.'/creategallery/'.$client->slug ?>">
		<button type="button" class="btn btn-success">Lisää uusi</button>
	</a>	 
</div>
		<?php
}
		if (count($galleries)>0 && is_object($client)){
			?>

			<div class="col-md-12 image-gallery">				
				
				<?php
				foreach ($galleries as $gallery) {
					?>
					<a href="/<?php echo BASE_URL.'/gallery/'.$client->slug.'/'.$gallery->id; ?>">
					 	<div class="col-md-2 gallery-cover">
					 	
					 		
					 	<?php
					 		$clientPath = 'uploads/'.$client->slug;					 		
					 		$image = ORM::for_table('photo')->where('gallery_id',$gallery->id)->find_one();
					 		$count = ORM::for_table('photo')->where('gallery_id',$gallery->id)->count();

					 		if (is_object($image)){
					 			$img = Util::imageResize($clientPath.'/'.$gallery->id,$image->file);
					 			?>
					 	<img src="<?php echo '/'.BASE_URL.'/'.$img; ?>" alt="<?php echo $image->name; ?>">
					 			<?php
					 		} else {
					 			?>
						<img src="<?php echo '/'.BASE_URL.'/';?>placeholder.jpg">
						<?php
					 		}
					 	?>
					 	<h4 class="list-group-item-heading"><?php echo $gallery->name; ?></h4>
					 	<span class="counter badge"><?php echo $count; ?></span>					 	
						</div>
					</a>
					<?php
					//echo '<li class="list-group-item"><a href="/'.BASE_URL.'/gallery/'.$client->slug.'/'.$gallery->id.'">'.$gallery->name.'</a></li>';
				}
				?>
			</div>
			<?php
		}
	}
}


?>