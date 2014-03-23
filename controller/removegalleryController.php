<?php
if (Photo::isAdmin()){
	if (isset($data['slug']) && isset($data['gallery'])){
		$slug = $data['slug'];
		$id = Util::alphaNum( $data['gallery'] );
		
		Photo::deleteGallery($id,$slug);

		$data['view'] = 'gallerylist';
		$data['slug'] = $slug;
	}
}