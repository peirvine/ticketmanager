
<div class="ccm-dashboard-header-buttons">
	<!--<a href="/dashboard/clockmanager/add" class="btn btn-primary">Add a Clock Entry</a>-->
	<a href="/dashboard/clock_manager/download2/0" class="btn btn-info">Download Data (XLS)</a>&nbsp;
	<a href="/dashboard/clock_manager/backup/0" class="btn btn-danger" onClick="return confirm('Are you sure you wish to DROP the database? Have you downloaded a copy of the databaes? You will not be able to access it again. This can not be undone.');">Backup Database and Start New Year</a>&nbsp;
</div>

<table class="table table-striped">
	<thead>
		<h3>User Summary</h3>
		<tr>
			<th>User ID</th>
			<th>User Name</th>
			<th>Total Hours Logged</th>
			<th>Current Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody id='categorylist'>
		<?php
		for($i=0; $status1 = $status->fetchRow(); $i++){
			$a[$i] = $status1['uID'];
		}
		while ($hours1 = $hours->fetchRow()) {
			echo "<tr id='category". $hours1['hoursID'] ."'>
				<td>". $hours1['uID'] ."</td>
				<td>". $hours1['fullname'] ."</td>
				<td>". $hours1['hours1'] ."</td>";
			
			for($k = 0; $k < count($a); $k++){
				if ($a[$k] == $hours1['uID']){
					$in = "in";
				}else{
					$in = "out";
				}
			}
			if ($in == "in"){
				echo "<td>Clocked In</td>
				<td>
					<a href='/dashboard/clock_manager/clockout/". $hours1['uID'] ."' class='btn btn-warning'>Clock Out</a>
				</td>";
			}else{
				echo "<td>Clocked Out</td>";
				echo "<td>No Actions</td>";
			}
			
			echo"
			
			</tr>";
		}
		?>
	</tbody>
</table>

<!-- <h3>Total hours logged by users: <?php // $total1 = $total->fetchRow(); echo $total1['total'] ?></h3><br /> -->


<table class="table table-striped">
<thead>
<h3>Clock Log</h3>
<tr>
	<th>User ID</th>
	<th>User Name</th>
	<th>Clock In</th>
	<th>Clock Out</th>
	<th>Hours Logged</th>
	<th>Description</th>
	<!--<th>Actions</th>-->
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
		<!--<td>
			<a href='/dashboard/clock_manager/edit/". $row['hoursID'] ."' class='btn btn-default'>Edit</a>
			<a href='/dashboard/clock_manager/?delete=". $row['hoursID'] ."' class='btn btn-danger' onClick='return confirm(\"Are you sure you wish to delete this competition? This can not be undone.\");'>Delete</a>
		</td>-->
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
