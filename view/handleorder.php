<?php
if (Photo::isAdmin()){
	if (isset($_REQUEST['endorder']) && isset($_REQUEST['id'])){

		$id = Util::alphaNum($_REQUEST['id']);		
		$order = ORM::for_table('order')->where('id',$id)->find_one();

		if (is_object($order)){
			$order->expired = true;
			$order->save();
		}

		header('Location: /'.BASE_URL.'/orders/');

	} else if (isset($_REQUEST['slug']) && isset($_REQUEST['gallery'])){
		$order_id = Util::alphaNum( $_REQUEST['gallery'] );
		$client_slug = Util::alphaNum( $_REQUEST['slug'] );

		$order = ORM::for_table('order')->where('id',$order_id)->find_one();
		
		if (is_object($order)){
			$client = ORM::for_table('client')->where('id',$order->client_id)->find_one();

			$photos = ORM::for_table('order_content')
					->join('photo',array('photo.id','=','order_content.photo_id'))
					->join('gallery',array('photo.gallery_id','=','gallery.id'))
					->select('order_content.order_id','id')
					->select('gallery.name','gallery_name')
					->select('photo.name','photo_name')
					->where('order_content.order_id',$order->id)
					->find_many();			
?>

<div class="col-md-8 col-md-offset-2">
	
	<h3>Tiedot</h3>
	<table class="table table-striped table-hover">
		<tr>
			<th>Tilausnumero</th>
			<th>Nimi</th>
			<th>Sähköposti</th>
			<th>Tunniste</th>
			<th>Pvm</th>
			<th>klo</th>
		</tr>
		<tr>
			<td class="col-md-1"><?php echo $order->id; ?></td>			
			<td><?php echo $client->name; ?></td>
			<td><?php echo $client->email; ?></td>
			<td><?php echo $client->slug; ?></td>
			<td><?php echo date('d.m.Y', strtotime($order->timestamp)); ?></td>
			<td><?php echo date('H:i:s', strtotime($order->timestamp)); ?></td>
		</tr>		
	</table>

	<h3>Tilatut kuvat</h3>

	<table class="table table-striped table-hover">
		<tr>
			<th>id</th>
			<th>Nimi</th>
			<th>Galleria</th>
		</tr>

	<?php
	if (count($photos)>0){
		foreach ($photos as $photo){		
?>
		<tr>
			<td class="col-md-1"><?php echo $photo->id; ?></td>			
			<td><?php echo $photo->photo_name; ?></td>
			<td><?php echo $photo->gallery_name; ?></td>
		</tr>		
<?php
		}

	}
	?>
	</table>
	<hr/>
	<form action="" method="post">
		<input type="hidden" name="id" value="<?php echo $order->id; ?>">
		<input type="hidden" name="endorder" value="true">

		<div class="alert alert-danger col-md-12">		
			<div class="col-md-8">
				<p>Tällä napilla tilaus katoaa</p>
			</div>
			<div class="col-md-4">				
				<button class="btn btn-danger pull-right">Päätä tilaus</button>
			</div>
		</div>
	</form>
</div>

			<?php
		}
	}
}
?>