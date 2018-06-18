<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<h2>Time Clock</h2>
<?php

$u = new User();
if($u->isLoggedIn()) {
if ($blah == 'clockin') {
?>
<p>You are clocked out</p>
<form action="" method="post">
	<input type="submit" name="clockin" value="Clock In" class="btn btn-default"/>
</form>
<?php
} else {
?>
You are clocked in <br />
Description of work done: <br/ >
<form action="" method="post" class="jrc_punchclock">
	<textarea name="description" id="textarea" class="form-control"></textarea><br />
	<input type="submit" name="clockout" value="Clock Out" class="btn btn-default"/>
</form>
<?php
}
?>
<br />
<br />

<h3>Recent Work Log</h3>
<p>15 most recent clock entries (newest at the top)</p>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Date</th>
			<th>Hours Worked</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody id='categorylist'>
<?php
		while ($row = $r->fetchRow()) {
		$time = strtotime($row['clockin']);
		$time2 = strtotime($row['clockout']);
		echo "<tr id='category". $row['hoursID'] ."'>
			<td>". $row['ak_full_name'] ."</td>";
            echo "<td>". date('m/d/y',$time) ."</td>";
			if ($row['logged'] <= -1000){
				echo "<td>Clocked In</td>";
			}else{
				echo "<td>". $row['logged'] ."</td>";
			}
			echo "<td>". $row['description'] ."</td>
			<!--<td>
				<a href='/dashboard/clockmanager/edit/". $row['hoursID'] ."' class='btn btn-default'>Edit</a>
				<a href='/dashboard/clockmanager/?delete=". $row['hoursID'] ."' class='btn btn-danger' onClick='return confirm(\"Are you sure you wish to delete this competition? This can not be undone.\");'>Delete</a>
			</td>-->
		</tr>";

}

?>
	</tbody>
</table>
<?php

    }else{
        echo "To clock time, please <a href='/login/'>log in</a>";
    }
?>

















