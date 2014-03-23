<div class="col-md-10 col-md-offset-1">			
	<h4>Uusi galleria</h4>

	<form action="" method="post" class="form">
		<input type="hidden" name="id" value="<?php echo $client->id; ?>">					
		
		<div class="form-group">				
			<span class="form-group-addon">Nimi</span>
			<input class="form-control" type="text" name="name" placeholder="gallery name"><br/>
		</div>

		<div class="form-group">
			<span class="form-group-addon">Meta</span>
			<textarea class="form-control" name="meta" cols="30" rows="3"></textarea>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success btn-lg col-md-1"><span class="glyphicon glyphicon-save"></span></button>
		</div>
	</form>
</div>