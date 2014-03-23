<?php
require_once("config.php");
if($_GET['a']=='log')
	{
	//laod all queries to be checked:
	$query = "SELECT * FROM thesis_status ORDER BY id DESC LIMIT 100";
	$logs = mysql_query($query);
	while($LOG=mysql_fetch_array($logs))
		{
		echo $LOG['timestamp'].' : '.$LOG['status']."<br>";
		}
	}
if($_GET['a']=='num_today')
	{
	//laod all queries to be checked:
	$query2 = "SELECT count(DISTINCT id) as vid_count FROM thesis__everytime WHERE collectiondate = '".date("Y-m-d")."'";
	$result2 = mysql_query($query2);		
	$data4 = mysql_fetch_array($result2, MYSQL_ASSOC);
	echo $data4['vid_count'].' <i class="fa fa-refresh fa-spin"></i>';
	}
if($_GET['a']=='new_today')
	{
	//laod all queries to be checked:
	$query2 = "SELECT count(video_id) as vid_count FROM thesis__once WHERE collectiondate = '".date("Y-m-d")."'";
	$result2 = mysql_query($query2);		
	$data2 = mysql_fetch_array($result2, MYSQL_ASSOC);
	echo $data2['vid_count']. ' <i class="fa fa-refresh fa-spin"></i>';
	}
if($_GET['a']=='total')
	{
	//laod all queries to be checked:
	$query = "SELECT count(DISTINCT yt_id) as vid_count FROM ". $prefix . "_once";
	$result = mysql_query($query);		
	$data = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $data['vid_count'];
	}

?>