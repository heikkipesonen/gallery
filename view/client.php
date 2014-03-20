<?php

$id = '';
$name = '';
$email = '';
$slug = '';
$password = '';
if (Photo::isAdmin()){


	if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
		$id = Util::alphanum($_REQUEST['id']);
		$data = ORM::for_table('client')->where('id',$_REQUEST['id'])->find_one();

		$id = $data->id;
		$name = $data->name;
		$email = $data->email;
		$slug = $data->slug;

	} else if (isset($_REQUEST['name']) && isset($_REQUEST['email']) && isset($_REQUEST['slug'])){

		$client = ORM::for_table('client')->create();

		$client->name = Util::clearXss( $_REQUEST['name'] );
		$client->email = Util::clearXss( $_REQUEST['email'] );
		$client->slug = Util::clearXss( $_REQUEST['slug'] );

		if (!empty($_REQUEST['password'])){
			$client->password = md5( Util::clearXss( $_REQUEST['password']) );
		}

		$client->save();

		$id = $client->id;
		$name = $client->name;
		$email = $client->email;
		$slug = $client->slug;

		header('Location: /'.BASE_URL.'/list');

	} else {
		$slug = Photo::getSlug();
		$password = Util::generateRandomToken(6);
	}

	?>
	<div class="col-md-8 col-md-offset-2">	
		<h2>Asiakas</h2>
		<form action="" method="post" class="form form-horizontal">
			<input type="hidden" value="client" name="view">
			<input type="hidden" value="<?php echo $id; ?>" name="id">
			
			<div class="form-group">
				<label for="user-name" class="col-md-2 control-label">Nimi</label>
				<div class="col-md-10">
					<input id="user-name" class="form-control" type="text" name="name"  value="<?php  echo $name; ?>" placeholder="nimi">
				</div>
			</div>
			<div class="form-group">	
				<label for="user-email" class="col-md-2 control-label">Sähköposti</label>
				<div class="col-md-10">
					<input id="user-email" class="form-control" type="text" name="email" value="<?php echo $email; ?>" placeholder="mail@mail.mail">
				</div>
			</div>
			<div class="form-group">
				<label for="user-slug" class="col-md-2 control-label">Slug</label>
				<div class="col-md-10">
					<input id="user-slug" class="form-control" type="text" name="slug" value="<?php echo $slug; ?>" placeholder="slug">
				</div>
			</div>
			<div class="form-group">
				<label for="user-password" class="col-md-2 control-label">Salasana</label>
				<div class="col-md-10">
					<input id="user-password" class="form-control" type="text" name="password" value="<?php echo $password ?>" placeholder="password">
				</div>
			</div>

			<button type="submit" class="btn col-md-2 col-md-offset-10 btn-default"><span class="glyphicon glyphicon-upload"></span></button>

		</form>
	</div>
	<?php
}
?>