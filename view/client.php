<?php
if (Photo::isAdmin()){
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

			<div class="toolbar button-tools button-group">	
				<button type="submit" class="btn col-md-2 col-md-offset-5 btn-success">Tallenna</button>
			</div>
		</form>
	</div>
	<?php
}
?>