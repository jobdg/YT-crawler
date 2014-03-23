<?php
	include('head.php');
	include('config.php');
	$id = $_GET['id'];
	$query = "SELECT * from " . $prefix . "_once WHERE query_id=$id";
	$result = mysql_query($query) or die(" ". mysql_error());
	echo "<br><br><table class='table table-striped small'>\n";
	$count = 1;
	echo "<tr><th>#</th><th>Title</th><th>Posted date</th><th>Duration</th><th>Category</th><th>Crawl date</th><th>Info</th></tr>";
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		echo '<tr><td align=right>' . $count . '</td>';
		echo '<td>'."<a href='".$line['video_url']. "' target='_blank'>" .$line['title'] . '</a></td>';
		echo '<td align=center>' . $line['upload_time'] . '</td>';
		echo '<td align=right>' . $line['duration'] . '</td>';		
		echo '<td>' . $line['category'] . '</td>';
		echo '<td>' . $line['collectiondate'] . '</td>';
		echo '<td><a href="showOnce.php?video_id='. $line['video_id'] . '">Once</a> | <a href="showEverytime.php?video_id='. $line['video_id'] . '">Every</a></td>';
		echo "</tr>\n";
		$count++;
  	}
	echo "</table>\n";
include('footer.php');
?>
