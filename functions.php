<?PHP
function pre_dump($var)
	{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	}

function curl_load($url)
	{
	$ch = curl_init();
  
    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $url);
 
    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727)");
 
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);
 
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
 
    // Download the given URL, and return output
    $output = curl_exec($ch);

    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $data=array($output,$http_status);

    return $data;
	}

function loaddata($feedURL,$id,$type)
	{
	//load via curl
	$load_data=curl_load($feedURL);
	$http_status=$load_data[1];
	$output=$load_data[0];

    //check http status

    //deleted videos give a 404 error
    if($http_status=='404')
		{	
		echo "<br><b>ERROR:404 - not found</b>";
		setstatus("Notice: 404 $id",2);
		$er="404";
		} 
	//private videos give a 403 error	
	elseif ($http_status=='403' && ($output == "GDataServiceForbiddenExceptionPrivate video" or $output == '{"apiVersion":"2.1","error":{"code":403,"message":"Private video","errors":[{"domain":"GData","code":"ServiceForbiddenException","internalReason":"Private video"}]}}')) 
		{
		echo "<br><b>Notice:403 - Private</b>";
		setstatus("Notice: private $id",2);
		$er="403";
		}
	//403 error is often a limit overload error
	elseif ($http_status=='403') 
		{
		echo "<br><b>ERROR:403 - Forbidden</b>";
		setstatus("Error: 403 $id ( $feedURL )",2);
		$er="403";
		echo $output;
		//die();
		setstatus("sleeping..",2);
		sleep(60);
		//retry after 2min
		curl_load($feedURL);
		$http_status=$data[1];
		$output=$data[0];
		if ($http_status=='403') 
			{
			//403 error is often a limit overload error
			echo "<br><b>ERROR:403 - Forbidden</b>";
			setstatus("Fatal Error: 403 $id ( $feedURL )",1);
			$er="403";
			die();
			}
		}
	//success status
	elseif ($http_status=='200') 
		{
		//processing data
		if($type=="json")
			{
			if(!$entry = json_decode($output))
				{
				echo "<hr> JSON ERROR";
				setstatus("NOTICE: JSON error $id",2);
				}
			}
		else
			{
			if(!$entry = simplexml_load_string($output))
				{
				echo "<hr>XML ERROR";
				setstatus("NOTICE: XML error $id",2);
				}
			}
		}
	else
		{
		//unknown error
		echo "<hr>".date("H:i:s")."<br>";
		echo $http_status;
		setstatus("FATAL ERROR: $id | $http_status",1);
		die();
		}
	return $data = array($entry,$er);
	}

//fucntion to insert new videos into DB
function insert_DB_new($DATA)
	{
	// Start formualting the Query
	$query = "INSERT INTO thesis__once 
				(
				query_id,
				yt_id,
				timestamp,
				collectiondate,
				title,
				description,
				username,
				upload_time,
				duration,
				category,
				video_url,
				user_name,
				user_views,
				user_subs,
				user_upl,
				user_favs,
				user_contacts,
				user_created,
				thumb_url
				)
			  VALUES
			  	(
			  	'".$DATA['qid_in']."',
		  		'".$DATA['ytID_in']."',
		  		'".$DATA['timestamp']."',
		  		'".$DATA['today_in']."',
				 '".$DATA['title']."',
				 '".$DATA['description']."', 
				 '".$DATA['username']."',
				 '".$DATA['upload_time']."', 
				 '".$DATA['duration']."',
				 '".$DATA['category']."',
				 '".$DATA['video_url']."',
				 '".$DATA['name']."',
				 '".$DATA['user_views']."',
				 '".$DATA['user_subs']."',
				 '".$DATA['user_upl']."', 
				'".$DATA['user_favs']."',
				'".$DATA['user_contacts']."', 
				'".$DATA['user_created']."',
				 '".$DATA['thumb_url']."'
				 )";			
	if(mysql_query($query))
		{
		echo "<br>done saving";
		}
	else
		{
		setstatus("FATAL ERROR: Error saving data",1);
		echo "<pre>".$query."</pre>";
		echo "<br>ERRROR:".mysql_error();
		die();		                                                       
		} 
	}
function setstatus($status,$type)
	{
	$query = "INSERT INTO thesis_status (timestamp,status,type)  
			  VALUES (NOW(), '".$status."', '".$type."')";			
	mysql_query($query);
	}
?>