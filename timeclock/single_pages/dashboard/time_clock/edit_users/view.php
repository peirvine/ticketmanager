<?php
if ($show == 'list'){

?>

<table class="table table-striped">
<thead>
<tr>
	<th>User ID</th>
	<th>Full Name</th>
	<th>Actions</th>
</tr>
</thead>
<tbody id='categorylist'>
<?php
while ($row = $r->fetchRow()) {
	$time = strtotime($row['event_date']);
	echo "<tr id='category". $row['uID'] ."'>
		<td>". $row['uID'] ."</td>
		<td>". $row['ak_full_name'] ."</td>
		<td>
			<a href='/dashboard/time_clock/edit_users/edit/". $row['uID'] ."' class='btn btn-default'>Edit</a>
		</td>
	</tr>";

}

?>
</tbody>
</table>

</script>

<?php
/*			jQuery.post(
				'/dashboard/jrc_crud/savecategoryorder/',
				{'categoryorder':jQuery('#categorylist').sortable('toArray').join(',').replace(/category/g,'')}
			);*/
} elseif ($show == 'form'){




?>

<?echo $data['uID']?>
<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
	<?php echo $form->label('ak_full_name', 'Full Name:') ?>
	<?php echo $form->text('ak_full_name',$data['ak_full_name']) ?>
</div>


<input type="hidden" name="id" value="<?php echo $editID ?>" />
<input type="hidden" name="uID" value="<?php echo $data['uID'] ?>" />
<input type="submit" class="btn btn-primary" value="Save Changes" name="edit" />
</form>





<?php


}