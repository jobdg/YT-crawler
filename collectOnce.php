<?php// Based on TubeKit by Chirag Shah// stats? - graphs, num of views, likes etc//related videos waiting?//data load in parseRSS2.phpini_set("memory_limit","100M");require_once("config.php");require_once("$mpdirectory/rss_fetch.inc");require_once("parseRSS2.php");require_once("functions.php");//set config:$t=getdate();$DATA['today_in']=date('Y-m-d',$t[0]);$qTableName = $prefix . "_queries";$oTableName_in = $prefix . "_once";//startsetstatus("STARTING Collect Once",1);//laod all queries to be checked:$query = "SELECT * FROM $qTableName";$vresult = mysql_query($query) or die(" ". mysql_error());//for each querywhile ($line = mysql_fetch_array($vresult, MYSQL_ASSOC)) 	{	//load query	$vquery = $line['query'];	echo "<h1>Processing $vquery...</h1>";	setstatus("Processing $vquery...",1);    $vquery = urlencode($vquery);    $qid_in = $line['id'];	$maxIndex = $numvideos-49;	//testing override	$maxIndex = 400;	for ($index=1; $index<=$maxIndex; $index+=50)		{		// for Categorized values		$key='AIzaSyDAD9HmHl6xZ_Ib-nbw-VtsEE7T0PCRWIc';		$url = "http://gdata.youtube.com/feeds/api/videos?category=$vquery&key=".$key."&max-results=50&start-index=$index";		echo "<hr>Fetching $url\n";		$rss = fetch_rss($url);		//foreaching search results		foreach ($rss->items as $item) 			{			$yt_url = $item["link"]; //CHANGED			$ytID_in = substr($yt_url,31,11);			echo "<hr><b>".$ytID_in."</b>";			//checkl if there is a video in the DB with this ID			$query = "SELECT * from $oTableName_in WHERE yt_id='$ytID_in' AND query_id='$qid_in'";			$result = mysql_query($query) or mysql_error();			$num_rows = mysql_num_rows($result);			// Only if there wasn't already a video with the same ID for the same query, process further  			if ($num_rows == 0)					  				{				echo "<hr>--new video:".$ytID_in;				$feedURL = "http://gdata.youtube.com/feeds/api/videos/$ytID_in?v=2&alt=jsonc";				echo "<br>Loading Feed Url: ".$feedURL;								//load data				$data = loaddata($feedURL,$ytID_in,"json");				if($data[1]!='')					{					continue;					}				else					{					$entry=$data[0];					}				//process data				$video = parseVideoEntry($entry);				$timestamp = time();				if($video)					{					echo "<br>Adding video:" .$video->title."\n";					//preparing data					$DATA['title'] = str_replace("'", "#39", $video->title);					$DATA['description'] = str_replace("'", "#39", $video->description);					$DATA['username'] = str_replace("'", "#39",$video->username);					$DATA['name'] = str_replace("'", "#39",$video->name);					$DATA['upload_time'] = $video->published;					$DATA['duration'] = $video->length;					$DATA['category'] = $video->category;					//$DATA['keywords'] = $video->keywords;					$DATA['video_url'] = $video->watchURL;					$DATA['user_subs'] = $video->subs;					$DATA['user_views'] = $video->views;					$DATA['user_upl'] = $video->usr_uploads;					$DATA['user_favs'] = $video->usr_favs;					$DATA['user_contacts'] = $video->usr_contacts;					$DATA['user_created'] = $video->usr_created;					$DATA['thumb_url'] = $video->thumbnailURL;//'http://i1.ytimg.com/vi/' . $ytID_in . '/0.jpg';					$DATA['timestamp'] = $timestamp;					$DATA['qid_in'] = $qid_in;					$DATA['ytID_in'] = $ytID_in;                                    		   					//storing DATA					insert_DB_new($DATA);					// Related video code					$related_url='http://gdata.youtube.com/feeds/api/videos/'.$DATA['ytID_in'].'/related?v=2'.'&max-results=20';										//loading related data					$data = loaddata($related_url,$ytID_in,"xml");					if($data[1]!='')						{						continue;						}					else						{						$relatedFeed=$data[0];						}					$count = 0;					foreach ($relatedFeed->entry as $related)						{						$count++;						$video_in = parseVideoEntry2($related);						$timestamp_in = time();						echo "<hr>Related Video Title [".$count."] :";						$ex=explode("=",$video_in->watchURL);						$ex=explode("&",$ex[1]);						$relatedvid=$ex[0];						echo $video_in->title."<br>";						$query = "INSERT INTO thesis__related (source,related,degree)  								VALUES ('".$DATA['ytID_in']."', '".$relatedvid."',1)";									if(!mysql_query($query))							{							setstatus("FATAL ERROR: DB saving related video",1);							echo "<pre>".$query."</pre>";							echo "<br>ERRROR:".mysql_error();							die();		                                                       							} 						}						}//$video check				} // if ($num_rows == 0)			else				{				echo " Already exists";				}					} // foreach ($rss->items as $item) 		} // for ($index=1; $index<=51; $index+=50)	}echo "<hr> Done";setstatus("DONE: collect once",1);				 ?>