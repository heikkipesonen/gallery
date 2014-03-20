<?php
if (Photo::isAdmin()){

	$clients = ORM::for_table('client')->find_many();

	?>
	<div class="jumbotron">
		<h2>Asiakkaat</h2>
	</div>
	<div class="list-container col-md-8 col-md-offset-2">
		<table class="table">
		<tr>
			
			<th>Nimi</th>
			<th>Sähköposti</th>
			<th>slug</th>
			<th>Muokkaa</th>
			<th>Poista</th>
		</tr>
		<?php
			foreach ($clients as $client) {
				?>
					<tr>
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
							<span class="glyphicon glyphicon-pencil"></span>
						</td>
						<td class="col-md-1">
							<span class="glyphicon glyphicon-remove"></span>
						</td>

					</tr>
				<?php			
			}
		?>
		</table>
	</div>
<?php
}
?>