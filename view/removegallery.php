<?php
if (isAdmin()){
	if (isset($_REQUEST['slug']) && isset($_REQUEST['gallery'])){
		$slug = Util::clearXss( $_REQUEST['slug'] );
		$id = Util::alphaNum( $_REQUEST['gallery'] );
		
		/*
		$client = ORM::for_table('client')->where('slug',$slug)->find_one();
		$gallery = ORM::for_table('gallery')->where('id',$id)->find_one();
		
		if (is_object($gallery) && is_object($client)){
			$clientPath = 'uploads/'.$client->slug;
			$folder = $clientPath.'/'.$gallery->id.'/';
			$result = ORM::for_table('photo')->where('client_id',$client->id)->where('gallery_id',$gallery->id)->delete();			

			if (file_exists($folder)){
				Util::deleteDir($folder);			
			}
			$gallery->delete();
		}*/
		Photo::deleteGallery($id,$slug);

		header('Location: /'.BASE_URL);
	}
}
?>