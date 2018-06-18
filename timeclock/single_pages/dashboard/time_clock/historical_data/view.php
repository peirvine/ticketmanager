
<div class="ccm-dashboard-header-buttons">
	<!--<a href="/dashboard/clockmanager/add" class="btn btn-primary">Add a Clock Entry</a>-->
    
    <script>
        function myFunction() {
          // Declare variables 
          var input, filter, table, tr, td, i;
          input = document.getElementById("myInput");
          filter = input.value;
          table = document.getElementById("myTable");
          tr = table.getElementsByTagName("tr");

          // Loop through all table rows, and hide those who don't match the search query
          for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            td2 = tr[i].getElementsByTagName("td")[1];
            if (td) {
              if (td.innerHTML.indexOf(filter) > -1 || td2.innerHTML.indexOf(filter) > -1) {
                tr[i].style.display = "";
              } else {
                tr[i].style.display = "none";
              }
            } 
          }
        }
    </script>
    
    
    <form method="GET">
		<input type="text" id="myInput" class="form-control" style="width: 150px; display: inline-block" onKeyDown="myFunction();" placeholder="Username" autofocus>
	<a href="/dashboard/time_clock/clock_manager/download2/0" class="btn btn-info">Download Data (XLS)</a>&nbsp;</form>
</div>
<h3>Filter by User</h3>

<!-- <h3>Total hours logged by users: <?php // $total1 = $total->fetchRow(); echo $total1['total'] ?></h3><br /> -->
<table id="myTable" class="table table-striped">
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
