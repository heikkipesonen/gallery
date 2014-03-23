<?php
$id = '';
$name = '';
$email = '';
$slug = '';
$password = '';

if (Photo::isAdmin()){	
	 if (isset($data['name']) && isset($data['email']) && isset($data['slug'])){
	 	if (isset($data['id'])){
	 		$id = Util::alphaNum($data['id']);
	 		$client = ORM::for_table('client')->where('id', $id)->find_one();

	 		if (!is_object($client)){
	 			$client = ORM::for_table('client')->create();
	 		}
	 	} else {
			$client = ORM::for_table('client')->create();		
	 	}


		$client->name = Util::clearXss( $data['name'] );
		$client->email = Util::clearXss( $data['email'] );
		$client->slug = Util::clearXss( $data['slug'] );

		if (!empty($data['password'])){
			$client->password = md5( Util::clearXss( $data['password']) );
		}

		$client->save();

		$data['view'] = 'list';		

	} else if (isset($data['slug']) && !empty($data['slug']) ){
		$slug = Util::alphanum($data['slug']);
		$client = ORM::for_table('client')->where('slug',$data['slug'])->find_one();

		if (is_object($client)){			
			$id = $client->id;
			$name = $client->name;
			$email = $client->email;
			$slug = $client->slug;
		}

	} else {
		$slug = Photo::getSlug();
		$password = Util::generateRandomToken(6);
	}
}