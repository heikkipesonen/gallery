<?php
if (Photo::isAdmin()){

	$clients = ORM::for_table('client')->find_many();

	?>
	<div class="jumbotron">
		<h2>Asiakkaat</h2>
		
		<div class="toolbar button-tools button-group">	
			<a href="/<?php echo BASE_URL.'/client'; ?>">
				<button type="button" class="btn btn-success">Lisää uusi</button>
			</a>
		</div>			
	</div>
	<div class="list-container col-md-8 col-md-offset-2">
		<table class="table table-hover table-striped">
		<tr>			
			<th></th>
			<th>Nimi</th>
			<th>Sähköposti</th>
			<th>slug</th>
			<th>Muokkaa</th>
			<!-- <th>Poista</th> -->
		</tr>
		<?php
		if (is_object($clients)){
			
			foreach ($clients as $client) {
				?>
					<tr>
						<td class="col-md-1">
							<a href="/<?php echo BASE_URL; ?>/gallerylist/<?php echo $client->slug; ?>">
								<span class="glyphicon glyphicon-th-large"></span>
							</a>
						</td>
						<td class="col-md-3">
							<a href="/<?php echo BASE_URL; ?>/gallerylist/<?php echo $client->slug; ?>">
								<p><?php echo $client->name; ?></p>
							</a>
						</td>
						<td class="col-md-3">
							<p><?php echo $client->email; ?></p>
						</td>
						<td class="col-md-2">
							<p><?php echo $client->slug; ?></p>
						</td>
						<td class="col-md-1">
							<a href="/<?php echo BASE_URL.'/client/'.$client->slug; ?>">
							<span class="glyphicon glyphicon-pencil"></span>
							</a>
						</td>
						<?php
						/*
						<td class="col-md-1">
							<span class="glyphicon glyphicon-remove"></span>
						</td>
						*/
						?>
					</tr>
				<?php			
			}
		}
		?>
		</table>
	</div>
<?php
}
?>