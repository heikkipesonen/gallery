<?php
if (isset($_REQUEST['gallery'])){

	$g = Util::alphaNum($_REQUEST['gallery']);

	$gallery = false;
	if (isset($_REQUEST['key'])){
		$key = Util::alphaNum($_REQUEST['key']);
		$gallery = ORM::for_table('gallery')->where('id',$g)->where('key',$key)->find_one();
	} else if (Photo::isAdmin() || Photo::isGalleryOwner($g)){
		$gallery = ORM::for_table('gallery')->where('id',$g)->find_one();
	}
	
	if (is_object($gallery)){
		$client = ORM::for_table('client')->where('id',$gallery->client_id)->find_one();
	}

	if (is_object($gallery) && is_object($client)){
		$clientPath = 'uploads/'.$client->slug;
		?>
		<div class="col-md-12 jumbotron no-margin">
			<h2><?php 
				if (Photo::isAdmin() || Photo::isGalleryOwner($gallery->id)){
					echo '<a href="/'.BASE_URL.'/gallerylist/'.$client->slug.'">';
				}

				echo $client->name.'/';

				if (Photo::isAdmin() || Photo::isGalleryOwner($gallery->id)){
					echo '</a>';
				}

				echo $gallery->name; ?></h2>
			<?php
			if (Photo::isAuthorized($client->id)){
				$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
				$galleryPublicUrl = $http .'://'.$_SERVER['HTTP_HOST'].'/'.BASE_URL.'/gallery/'.$client->slug.'/'.$gallery->id.'/'.$gallery->key;
				echo '<a href="'.$galleryPublicUrl.'"><h4>'.$galleryPublicUrl.'</h4></a>';
			}
			?>
		</div>
		<?php
		
		
		if (Photo::isAdmin()){			
			?>
			<div class="toolbar button-group col-md-12">
				<a href="/<?php echo BASE_URL.'/removegallery/'.$client->slug.'/'.$gallery->id; ?>"><button class="btn btn-danger">Poista</button></a>
			</div>
			<div class="col-md-8 col-md-offset-2 gallery-fileupload">	
				
					<form class="form" action="" method="post" enctype="multipart/form-data">
						<div class="col-md-10">						
							<input class="form-control" type="file" id="imageupload" name="images[]" multiple>
						</div>
						<button class="btn btn-primary col-md-2" type="submit"><span class="glyphicon glyphicon-upload"></span></button>
					</form>
				
			</div>

			<?php
			if (isset($_FILES) && count($_FILES) > 0){
				
				$files = Util::reArrayFiles($_FILES['images']);

				if (!file_exists($clientPath)){
					mkdir($clientPath);
					chmod($clientPath, 0755);
				}

				if (!file_exists($clientPath.'/'.$gallery->id)){
					mkdir($clientPath.'/'.$gallery->id);
					chmod($clientPath.'/'.$gallery->id, 0755);
				}

				foreach ($files as $file) {
					if ($file['error'] == 0){						
						$result = Util::uploadImage($client->slug.'/'.$gallery->id, $file);
						

						if ($result['errorMessage']){
							echo $result['errorMessage'];
						} else {
							$photo = ORM::for_table('photo')->create();
							$photo->client_id = $client->id;
							$photo->file = $result['imageName'];
							$photo->name = $result['name'];
							$photo->gallery_id = $gallery->id;

							$photo->save();					
						}
					}
				}
				
			}
		}

		$auth = Photo::isGalleryOwner($gallery->id);
		if (!$auth) $auth = Photo::isAdmin();

		//if ($auth){
			?><div class="image-gallery col-md-12">	<?php
			$images = ORM::for_table('photo')->where('gallery_id',$gallery->id)->find_many();
			if (count($images)>0){		
				if ($auth){
					?>
					<form class="form" action="/<?php echo BASE_URL; ?>/order" method="post">					
					<?php if (!Photo::isAdmin() && Photo::isGalleryOwner($gallery->id)){ ?>
						<div class="alert alert-info col-md-12">
							<p class="col-md-10">Valitse ensin kuvat listasta, klikkaa tilaa kun olet valmis</p>
							<button class="btn btn-primary col-md-2" type="submit">Tilaa</button>
						</div>
					<?php
					}
				}
				?>
					<?php 
					
					foreach ($images as $image){
						$img = Util::imageResize($clientPath.'/'.$gallery->id,$image->file);
						?>
							<div class="image-container col-md-2">	
								<a href="<?php echo '/'.BASE_URL.'/photos/'.$client->slug.'/'.$gallery->id.'/'.$gallery->key.'/'.$image->id; ?> ">									
									<img src="<?php echo '/'.BASE_URL.'/'.$img; ?>" alt="<?php echo $image->name; ?>">
								</a>				
								<label><?php 
									if ($auth){
										?>
										<input type="checkbox" name="image[]" value="<?php echo $image->id; ?>">
										<?php
									}
								echo $image->name;
								?>
								</label>
							</div>
						<?php
					}	
					if ($auth){						
						?>	
						</form>
						<?php
					}					
					?>
					<div class="clear"></div>
				</div>
				<?php
			//}
		}
	} else {
		?>
			<div class="error">
				<h3 class="error">Galleriaa ei löytynyt</h3>
			</div>
		<?php
	}
}