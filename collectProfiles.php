<?php   
$t = getdate();
$date = date('Y-m-d', $t[0]);

// read 'author profile feed' into SimpleXML object
// parse and store author bio
require_once("config.php");
$query = "SELECT distinct username FROM thesis__once"; // Formulate the query
$results = mysql_query($query) or die(" ". mysql_error()); // Execute the query, get the results
$n=1;
while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) // Go record by record
{	
	$userName = $line['username'];
	$query1 = "SELECT * from yt_profiles WHERE username='$userName'";
	$result1 = mysql_query($query1) or mysql_error();
	$num_rows = mysql_num_rows($result1);

	// Only if there wasn't already a profile with the same username, process further                                                                                              
	if ($num_rows == 0)					  
	{
		echo "<hr>Processing user $userName\n";
		$url = "http://www.youtube.com/user/".$userName;
		$video->authorURL = "http://gdata.youtube.com/feeds/api/users/".$userName;
		echo "<br>".$feedURL = $video->authorURL;
		$n++;
		if($n==10)
			{die();}
		if(!$authorFeed = simplexml_load_file($feedURL))
			{
			file_get_contents($feedURL);
			if($http_response_header[0]=='HTTP/1.0 404 Not Found')
				{
				//deleted videos give a 404 error
				echo "<br><b>ERROR:404 - not found</b>";
				continue;
				} 
			elseif ($http_response_header[0]=='HTTP/1.0 403 Forbidden') 
				{
				//private videos give a 403 error
				echo "<br><b>ERROR:403 - forbidden</b>";
				continue;
				}
			elseif ($http_response_header[0]=='HTTP/1.0 400 Bad Request') 
				{
				//private videos give a 403 error
				echo "<br><b>ERROR:400 - bad request</b>";
				continue;
				}
			else
				{
				echo "<hr>".date("H:i:s")."<br>";
				echo $http_response_header[0];
				die();
				//sleep(60);
				}		
			if(!$authorFeed = simplexml_load_file($feedURL))
				{
				echo "<hr>".date("H:i:s");
				sleep(120);
				if(!$authorFeed = simplexml_load_file($feedURL))
					{
					echo "<hr>".date("H:i:s");
					die('<hr><hr><hr>connection fail');
					}
				}	
			}
		if ($authorFeed)
		{
		   	$authorData = $authorFeed->children('http://gdata.youtube.com/schemas/2007');
			$firstName = addslashes($authorData->firstName);
			$lastName = addslashes($authorData->lastName);
			$gender = addslashes($authorData->gender);
		   	$age = $authorData->age;
			$hometown = addslashes($authorData->hometown);
		    $location =  addslashes($authorData->location);  
		  	$occupation = addslashes($authorData->occupation);
			$company = addslashes($authorData->company);
			$school = addslashes($authorData->school);
			$hobbies = addslashes($authorData->hobbies);
			$movies = addslashes($authorData->movies);
			$music = addslashes($authorData->music);
			$books = addslashes($authorData->books);
			echo $query = "INSERT INTO yt_profiles VALUES('','$userName','$firstName','$lastName','$gender','$age','$hometown','$location','$occupation','$company','$school','$hobbies','$movies','$music','$books','$date')";
			$result = mysql_query($query) or die(" ". mysql_error());
		} // if ($authorFeed)
	} // if ($num_rows == 0)	
} // while ($line = mysql_fetch_array($results, MYSQL_ASSOC))
?>
