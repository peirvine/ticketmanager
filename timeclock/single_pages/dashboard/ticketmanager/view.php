<?php
if ($show == 'list'){
?>	
<div class="ccm-dashboard-header-buttons">
<?php
echo '<a href="/dashboard/ticketmanager/download2/0" class="btn btn-info">Download Data (XLS)</a>&nbsp;';
?>
</div>
<table class="table table-striped">
<thead>
<tr>
	<th>Ticket ID</th>
	<th>User ID</th>
	<th>Full Name</th>
	<th>Hours Adjustment</th>
	<th>Description</th>
	<th>Date Submitted</th>
	<th>Handled</th>
	<th>Approved/Rejected</th>
	<th>Actions</th>
</tr>
</thead>
<tbody id='categorylist'>
<?php
while ($row = $r->fetchRow()) {
	$time = strtotime($row['datesubmitted']);
	if($row['handled']){
		$handled = "Yes";
	}else{
		$handled = "No";
	}
	if($row['accepted'] == 0){
		$accepted = "Unhandled";
	}else if($row['accepted'] == 1){
		$accepted = "Rejected";
	}else if($row['accepted'] == 2){
		$accepted = "Accepted";
	}
	echo "<tr id='category". $row['event_id'] ."'>
		<td>". $row['ticket_id'] ."</td>
		<td>". $row['uID'] ."</td>
		<td>". $row['ak_full_name'] ."</td>
		<td>". $row['hoursdifference'] ."</td>
		<td>". $row['description'] ."</td>
		<td>". date('g:i:s A n/j/Y', $time) ."</td>
		<td>". $handled ."</td>
		<td>". $accepted ."</td>";
		if(!$row['handled']){
	
	echo "
		<td>
			<a href='/dashboard/ticketmanager/?approve=". $row['ticket_id'] ."&uid=".$row['uID']."&difference=". $row['hoursdifference']."&description=".$row['description']."' class='btn btn-default' type='submit' name='edit' onClick='return confirm(\"Are you sure you wish to approve this ticket? This can not be undone.\");'>Approve</a>
			<a href='/dashboard/ticketmanager/?reject=". $row['ticket_id'] ."' class='btn btn-danger' onClick='return confirm(\"Are you sure you wish to reject this ticket? This can not be undone.\");'>Reject</a>
		</td>
	</tr>";
		}else{
			echo "<td>Ticket has been handled</td></tr>";
		}

}

?>
</tbody>
</table>

<?php
/*			jQuery.post(
				'/dashboard/jrc_crud/savecategoryorder/',
				{'categoryorder':jQuery('#categorylist').sortable('toArray').join(',').replace(/category/g,'')}
			);*/
} elseif ($show == 'form'){




?>


<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
	<?php echo $form->label('event_name', 'Meeting Name:') ?>
	<?php echo $form->text('event_name',$data['event_name']) ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_date', 'Meeting Date:') ?>
	<?php echo Loader::helper('form/date_time')->date('event_date', $data['event_date']); ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_description', 'Meeting Description') ?>
	<?php echo $form->textarea('event_description',$data['event_description']) ?>
</div>


<input type="hidden" name="id" value="<?php echo $editID ?>" />
<input type="submit" class="btn btn-primary" value="Save Changes" name="edit" />
</form>





<?php


}