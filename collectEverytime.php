<?php// Based on TubeKit by Chirag Shahini_set("memory_limit","100M");require_once("config.php");require_once("$mpdirectory/rss_fetch.inc");require_once("parseRSS2.php");require_once("functions.php");setstatus("STARTING Collect EveryTime",1);$t=getdate();$today=date('Y-m-d',$t[0]);$oTableName = $prefix . "_once";$eTableName = $prefix . "_everytime";$query = "SELECT * FROM $oTableName";$vresult = mysql_query($query) or die(" ". mysql_error());while ($line = mysql_fetch_array($vresult, MYSQL_ASSOC)) 	{	//getting video IDs	$ytID = $line['yt_id'];	$id = $line['video_id'];	$qid = $line['query_id'];	echo "<hr>checking -- ".$line['yt_id'];	$query2 = "SELECT * FROM $eTableName WHERE video_id = '$id' AND collectiondate = '$today'";	$vresult2 = mysql_query($query2);	$num_rows2 = mysql_num_rows($vresult2);	// Only if there wasn't already a video with the same ID for the same query, process further  	if ($num_rows2 == 0)					  		{		echo "-- updating:".$line['yt_id'];					$feedURL = "http://gdata.youtube.com/feeds/api/videos/$ytID?v=2&alt=jsonc";				//connection handling		$data = loaddata($feedURL,$line['yt_id'],"json");		if($data[1]!='')			{			continue;			}		else			{			$entry=$data[0];			}				$video = parseVideoEntry($entry);		$timestamp = time();		//set data		$view_count = $video->viewCount;		$rating_count = $video->numrating;		$like_count = $video->likeCount;		$rating_avg = $video->rating;		$comment_count = $video->commentsCount;		$favorite_count = $video->favoriteCount;					$query = "INSERT INTO $eTableName					(					video_id,					timestamp,					collectiondate,					view_count,					like_count,					rating_count,					rating_avg,					comment_count,					favorite_count					)				  VALUES				  	(			  		'".$id."',			  		'".$timestamp."',			  		'".$today."',					 '".$view_count."',					 '".$like_count."',					 '".$rating_count."', 					 '".$rating_avg."',					 '".$comment_count."', 					 '".$favorite_count."'					 )";					if(mysql_query($query))			{			echo "<br>done saving";			}		else			{			setstatus("FATAL ERROR: DB collectedEveryTime data",1);			echo "<pre>".$query."</pre>";			echo "<br>ERRROR:".mysql_error();			die();		                                                       			} 		}//num rows = 0	else		{		echo " -- Already updated";		}	} // while ($line = mysql_fetch_array($vresult, MYSQL_ASSOC))echo "<hr> Done";setstatus("DONE: collecteverytime",1);	 		?>