<?php
include('head.php');
include('config.php');
$count=0;

//tot num of videos
$query = "SELECT count(DISTINCT yt_id) as vid_count FROM ". $prefix . "_once";
$result = mysql_query($query);		
$data = mysql_fetch_array($result, MYSQL_ASSOC);


//new vids today
$query2 = "SELECT count(video_id) as vid_count FROM ". $prefix . "_once WHERE collectiondate = '".date("Y-m-d")."'";
$result2 = mysql_query($query2);		
$data2 = mysql_fetch_array($result2, MYSQL_ASSOC);

//num if collection dates
$query2 = "SELECT count(DISTINCT collectiondate) as vid_count FROM ". $prefix . "_everytime";
$result2 = mysql_query($query2);		
$data3 = mysql_fetch_array($result2, MYSQL_ASSOC);

//num if collection dates
$query2 = "SELECT count(DISTINCT id) as vid_count FROM ". $prefix . "_everytime WHERE collectiondate = '".date("Y-m-d")."'";
$result2 = mysql_query($query2);		
$data4 = mysql_fetch_array($result2, MYSQL_ASSOC);

$count2=$data['vid_count']-$data4['vid_count'];
?>
<h2>Actions</h2>
	<input class="btn btn-primary"  type="submit" value="Run CollectOnce" onclick="start_action('collectOnce.php','once')">
	<input class="btn btn-primary"  type="submit" value="Run CollectRelated"  onclick="start_action('collectRelated.php','related')">
	<input class="btn btn-primary"  type="submit" value="Run CollectEverytime" onclick="start_action('collectEverytime.php','everytime')">
<h2>Stats</h2>
<table class="table">
	<tr>
		<td>Total # of vids crawled:</td>
		<td><?PHP echo $data['vid_count'] ?></td>
	</tr>
	<tr>
		<td>Total # of new vids crawled today:</td>
		<td id="new_today"><?PHP echo $data2['vid_count'] ?></td>
	</tr>
	<tr>
		<td># collection dates:</td>
		<td><?PHP echo $data3['vid_count'] ?></td>
	</tr>
	<tr>
		<td>number of videos crawled today:</td>
		<td id="num_today"><?PHP echo $data4['vid_count']; ?></td>
	</tr>
	<tr>
		<td>number of videos not crawled today:</td>
		<td class="red"><?PHP echo $count2 ?></td>
	</tr>
</table>
<h2>Status</h2>
<div class="alert alert-info" id="status">Crawler is not running</div>
<pre class="well" id="log">No entries in logfile</pre>

<?php
include('footer.php');
?>
