<?php
if (Photo::isAdmin()){

	if (isset($_REQUEST['slug'])){
		$slug = Util::clearXss($_REQUEST['slug']);

		$client = ORM::for_table('client')->where('slug',$slug)->find_one();
	}

	if (isset($_REQUEST['name']) && isset($_REQUEST['id']) && isset($_REQUEST['meta'])){
		$name = Util::clearXss($_REQUEST['name']);
		if (!empty($name)){

			$gallery = ORM::for_table('gallery')->create();
			$gallery->client_id = Util::alphaNum($_REQUEST['id']);
			$gallery->name = Util::clearXss($_REQUEST['name']);
			$gallery->meta = Util::clearXss($_REQUEST['meta']);
			$gallery->key = Util::generateRandomToken(6);
		
			$gallery->save();

			//header('Location: /'.BASE_URL.'/gallerylist/'.$client->slug);

			$data['view'] = 'gallerylist';
			$data['client'] = $client->slug;
		}
	}
}