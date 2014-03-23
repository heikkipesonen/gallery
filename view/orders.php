<?php
if (Photo::isAdmin()){
	
	$orders = ORM::for_table('order')	
		->join('client',array('client.id','=','order.client_id'))
		->select('order.id')
		->select('client.slug','slug')
		->select('client.name','client_name')
		->select('client.id','client_id')
		->select('order.timestamp')

		->where('order.expired',false)->find_many();
	?>
	<div class="jumbotron">
		<h2>Tilaukset</h2>
	</div>

	<table class="table table-striped table-hover">
	<tr>
		<th>#</th>
		<th>Tilaaja</th>
		<th>Pvm</th>
		<th>klo</th>
		<th>Öppnas här</th>
	</tr>
	<?php


	foreach ($orders as $order){
		?>
		<tr>
			<td class="col-md-1"><?php echo $order->id; ?></td>
			<td><?php echo $order->client_name; ?></td>			
			<td><?php echo date('d.m.Y', strtotime($order->timestamp)); ?></td>
			<td><?php echo date('H:i:s', strtotime($order->timestamp)); ?></td>
			<td><a href="/<?php echo BASE_URL; ?>/handleorder/<?php echo $order->slug.'/'.$order->id; ?>"><button class="btn btn-success">avaa</button></td></a>
		</tr>
		<?php
	}
?>
	</table>
	<?php
}
?>