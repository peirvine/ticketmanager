<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<h2>Ticket Submissions</h2>
<p>Did you forget to clock in or clock out? Fill out the form below with how many hours you need to adjust your clock with and a short description of the work you did. If you forgot to clock in, please just put what you did and don't write "Forgot to clock in" or something like that.</p>
<?php
$u = new User();
if($u->isLoggedIn()) {
?>
<form action="" method="post" class="form-horizontal">

	<!-- Add or Subtract -->
    <div class="form-group">
		<label class="col-md-4 control-label" for="hoursdifference">Add or Subtract Hours</label>  
		<div class="col-md-8">
            <!--<input name="plus" type="radio" id="plus" value="plus" checked>&nbsp;Add<br />
            <input name="minus" type="radio" id="minus" value="minus">&nbsp;Subtract-->
            <input type="radio" name="plusminus" value="0" checked>&nbsp;Add<br>
            <input type="radio" name="plusminus" value="1">&nbsp;Subtract
		</div>
	</div>
    
    <!-- Hours difference -->
    <div class="form-group">
		<label class="col-md-4 control-label" for="hoursdifference">Hours Difference</label>  
		<div class="col-md-8">
			<input id="hoursdifference" name="hoursdifference" type="number" 
                   min="0" step="0.01" class="form-control input-md">
		</div>
	</div>

	<!-- Textarea -->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="description1">Description of Work Done</label>
	  <div class="col-md-8">                     
		<textarea class="form-control" id="textarea" name="description1"></textarea> <br />
        <input type="submit" name="tickets" value="Submit Ticket" class="btn btn-default"/>
	 </div>
	</div>
	
</form>

<h3>Your submitted tickets</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Ticket ID</th>
			<th>Hour Difference</th>
			<th>Description</th>
			<th>Handled</th>
			<th>Approved/Rejected</th>
		</tr>
	</thead>
	<tbody id='categorylist'>
	<?php
	while ($t = $tickets->fetchRow()) {
		if($t['handled']){
			$handled = "Yes";
		}else{
			$handled = "No";
		}
		if($t['accepted'] == 0){
			$accepted = "Unhandled";
		}else if($t['accepted'] == 1){
			$accepted = "Rejected";
		}else if($t['accepted'] == 2){
			$accepted = "Accepted";
		}
			echo "<tr id='category". $t['ticket_id'] ."'>
					<td>". $t['ticket_id'] ."</td>
					<td>". $t['hoursdifference'] ."</td>
					<td>". $t['description'] ."</td>
					<td>". $handled ."</td>
					<td>". $accepted ."</td>
				</tr>";		
	}
	?>
	</tbody>
</table>
<?php

    }else{
        echo "To submit a ticket, please <a href='/login/'>log in</a>";
    }
?>