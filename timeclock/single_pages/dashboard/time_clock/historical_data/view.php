
<div class="ccm-dashboard-header-buttons">
	<!--<a href="/dashboard/clockmanager/add" class="btn btn-primary">Add a Clock Entry</a>-->
	<a href="/dashboard/time_clock/clock_manager/download2/0" class="btn btn-info">Download Data (XLS)</a>&nbsp;
</div>
<h3>Filter by User</h3>
<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
<table>
    <tr>
        <td><?php echo $form->label('ak_full_name', 'Full Name: ') ?></td>&nbsp;
        <td><?php echo $form->text('ak_full_name',$data['ak_full_name']) ?></td>&nbsp;
        <td><?php echo $form->label('uID', 'User ID: ') ?></td>&nbsp;
        <td><?php echo $form->text('uID',$data['uID']) ?></td>&nbsp;
    </tr>
</table>    
</div>
<input type="submit" class="btn btn-primary" value="Search User" name="search" />
</form>

<!-- <h3>Total hours logged by users: <?php // $total1 = $total->fetchRow(); echo $total1['total'] ?></h3><br /> -->


<table class="table table-striped">
<thead>
<h3>Clock Log</h3>
<p>This data does not include the current period.</p>
<tr>
	<th>User ID</th>
	<th>User Name</th>
	<th>Clock In</th>
	<th>Clock Out</th>
	<th>Hours Logged</th>
	<th>Description</th>
</tr>
</thead>
<tbody id='categorylist'>
<?php
while ($row = $r->fetchRow()) {
	$time = strtotime($row['clockin']);
	$time2 = strtotime($row['clockout']);
	echo "<tr id='category". $row['hoursID'] ."'>
		<td>". $row['uID'] ."</td>
		<td>". $row['ak_full_name'] ."</td>
		<td>". date('g:i:s A n/j/Y', $time) ."</td>
		<td>". date('g:i:s A n/j/Y', $time2) ."</td>";
		if ($row['logged'] <= -1000){
			echo "<td>Clocked In</td>";
		}else{
			echo "<td>". $row['logged'] ."</td>";
		}
		echo "<td>". $row['description'] ."</td>
	</tr>";

}

?>
</tbody>
</table>


<?php
/*			jQuery.post(
				'/dashboard/jrc_crud/savecategoryorder/',
				{'categoryorder':jQuery('#categorylist').sortable('toArray').join(',').replace(/category/g,'')}
			);*/
