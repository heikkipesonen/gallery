<?php
if (isset($_POST['username']) && isset($_POST['password']) ){
	if ( $_POST['username'] == ADMIN_USERNAME){
		if ($_POST['password'] == ADMIN_PASSWORD){
			$_SESSION['lvl'] = 10;
			$_SESSION['auth'] = true;

			$data['view'] = 'main';			
		}
	} else {
		$username = Util::clearXss($_POST['username']);
		$password = Util::clearXss($_POST['password']);

		$result = ORM::for_table('client')->where('password',md5($password))->where('email',$username)->find_one();

		if (is_object($result)){
			$_SESSION['lvl']=3;
			$_SESSION['auth']=$result->id;
			$_SESSION['slug']=$result->slug;

			$data['view'] = 'gallerylist';
			$data['slug'] = $result->slug;
		}
	}
}