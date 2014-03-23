<?php
include('head.php');
require_once("config.php");

if ($_GET['query'])
	  {
	    $vquery = $_GET['query'];
		$qTableName = $prefix . "_queries";
	    $query = "INSERT INTO $qTableName (id,query) VALUES ('','$vquery')";
		$result = mysql_query($query) or die(" ". mysql_error());
	  }
?>
	   	Add a seed category 
	   	<form action="dash_query.php" method="get">
	   	<table>
	   		<tr>
		   		<td><input class="form-control" type="text" name="query"></input></td>
		   		<td> <input class="btn btn-primary" type="submit" value="Submit"></input></td>
		   	</tr>
		</table>
		</form>

		<table class="table table-striped">
		<tr>
			<td>Category:</td>
			<td>Video count:</td>
		</tr>
		<?php
		$query = "SELECT * FROM ". $prefix . "_queries";
		
		$result = mysql_query($query) or die(" ". mysql_error());
		
		while ( $line = mysql_fetch_array($result, MYSQL_ASSOC)) 
		  {
		    $query = $line['query'];
		    echo "
		    	<tr>
						<td><a href=\"dash_query_det.php?id=".$line['id']. "\">$query</a></td>
					";
				$query2 = "SELECT count(video_id) as res_count FROM ". $prefix . "_once WHERE query_id=".$line['id']."";
			$result2 = mysql_query($query2);		
			$data = mysql_fetch_array($result2, MYSQL_ASSOC);
			echo "<td>".$data['res_count']."</td></tr>";
			 
		  }
		  ?>
		</table>
 <?php
 include('footer.php');
 ?>