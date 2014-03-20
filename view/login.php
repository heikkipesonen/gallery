<div class="login col-md-4 col-md-offset-4">	
	<div class="logo col-md-12">
		<img src="peruna.png">
	</div>
<?php
if (!Photo::isLogin()){
	if (isset($_POST['username']) && isset($_POST['password']) ){
		if ( $_POST['username'] == ADMIN_USERNAME){
			if ($_POST['password'] == ADMIN_PASSWORD){
				$_SESSION['lvl'] = 10;
				$_SESSION['auth'] = true;

				header('Location: .');
			}
		} 

		$username = Util::clearXss($_POST['username']);
		$password = Util::clearXss($_POST['password']);

		$result = ORM::for_table('client')->where('password',md5($password))->where('email',$username)->find_one();

		if (is_object($result)){
			$_SESSION['lvl']=3;
			$_SESSION['auth']=$result->id;
			$_SESSION['slug']=$result->slug;

			header('Location: index.php/gallerylist/'.$result->slug);
		}
	}

	if (isset($_SESSION['lvl'])){
		if ($_SESSION['lvl'] > 0){	
			header('Location: ./');
		}
	}
	?>
	<div class="col-md-12">
		<form class="form" action="" method="post">
			<div class="input-group">				
				<span class="glyphicon glyphicon-user input-group-addon"></span>
				<input class="form-control" type="text" name="username" placeholder="käyttäjä" value="<?php if (isset($_REQUEST['username']) ) echo $_REQUEST['username']; ?>"> 
			</div>
			<div class="input-group">
				<span class="glyphicon glyphicon-lock input-group-addon"></span>
				<input class="form-control" type="password" name="password" placeholder="salasana">	
			</div>
			<button class="form-control" type="submit"><span class="glyphicon glyphicon-log-in"></span></button>
		</form>	
	</div>
	<?php
}
?>
</div>