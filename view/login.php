<div class="login col-md-4 col-md-offset-4">	
	<div class="logo col-md-12">
		<img src="peruna.png">
	</div>
	<div class="col-md-12">
		<form class="form" action="./" method="post">
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
</div>