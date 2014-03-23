<div class="col-md-8 col-md-offset-2">

<?php
if (isset($_REQUEST['image']) && isset($_REQUEST['slug']) && isset($_REQUEST['id']) && isset($_REQUEST['gallery'])){

	$id = Util::alphaNum($_REQUEST['id']);
	$s = Util::alphaNum($_REQUEST['slug']);
	$g = Util::alphaNum($_REQUEST['gallery']);

	$images = array();
	foreach ($_REQUEST['image'] as $img){
		$images[] = Util::alphaNum($img);
	}

	$client = ORM::for_table('client')->where('id', $id)->where('slug',$s)->find_one();
	$clientPath = 'uploads/'.$client->slug;

	if (is_object($client)){

			if (isset($_REQUEST['confirm'])){

				$order = ORM::for_table('order')->create();
				$order->client_id = $client->id;
				$order->meta = Util::clearXss($_REQUEST['meta']);
				$order->save();

				foreach ($images as $image) {
					$order_content = ORM::for_table('order_content')->create();
					$order_content->order_id = $order->id();
					$order_content->photo_id = $image;
					$order_content->save();
				}
		?>
			<div class="jumbotron">
				<h2>Tilauksesi on tallennettu</h2>
				<div class="alert alert-success">
					<p>Tilauksesi on nyt tallennettu, otan sinuun yhteyttä mahdollisimman pian.</p>
				</div>
				<!--
				<div class="alert alert-info">
					<p>Tilauksesi tunnus on:<?php echo $order->id(); ?></p>
				</div>
				-->
			</div>
		<?php

			} else {

				$gallery = ORM::for_table('gallery')->where('id',$g)->find_one();
				$photos = ORM::for_table('photo')->where('gallery_id',$gallery->id)->where_in('id',$images)->find_many();
				?>


			<div class="jumbotron">
				<h2>Vahvista tilaus</h2>
				<div class="alert alert-info">
					<p>Olet tilaamassa alla listassa näkyvät valitut kuvat.</p>
				</div>
			</div>

		<form action="" class="form" method="post">
			<input type="hidden" name="id" value="<?php echo $client->id; ?>">
			<input type="hidden" name="slug" value="<?php echo $client->slug; ?>">
			<input type="hidden" name="gallery" value="<?php echo $gallery->id; ?>">
			<input type="hidden" name="confirm" value="true">


			<table class="table table-striped table-hover ordered">	
				<tr><th>Tilatut kuvat</th><th>Tiedostonimi</th><th>Valittu</th></tr>
				<?php
				foreach ($photos as $photo){
					?>
				<tr>
					<td class="col-md-2">
						<?php 
							$img = Util::imageResize($clientPath.'/'.$gallery->id,$photo->file);				
						?>
						<img src="<?php echo '/'.BASE_URL.'/'.$img; ?>" alt="<?php echo $img->name;?>">
					</td class="col-md-9">
					<td>
						<p><?php echo $photo->name; ?></p>
					</td>
					<td class="col-md-1">
						<input type="checkbox" name="image[]" value="<?php echo $photo->id; ?>" checked="checked"> 
					</td>
				</tr>

					

					<?php
				}
			?>
			</table>
		
		<div class="form-group">
			<span class="form-group-addon">Lisätietoja</span>
			<textarea class="form-control" name="meta" cols="30" rows="3" placeholder="jotain"></textarea>
		</div>

		<button class="btn btn-success pull-right">Vahvista tilaus</button>
		</form>
			<?php
				}
			}
} else {
	?>
		<div class="alert alert-warning">
			<h3>Oho</h3>
			<p>Jokin meni vikaan...</p>
		</div>
	<?php
}
?>
</div>