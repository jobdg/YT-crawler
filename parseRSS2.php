<?php
// Based on TubeKit by Chirag Shah

//json parser
function parseVideoEntry($entry) {   
$obj= new stdClass;  
if ($entry) 
	{
	$obj->title = $entry->data->title;
	$obj->description = $entry->data->description;
	$obj->category = $entry->data->category;
	$obj->watchURL = $entry->data->player->default; 
	$obj->thumbnailURL = $entry->data->thumbnail->hqDefault; 
	$obj->length = $entry->data->duration;
	$obj->viewCount = $entry->data->viewCount;
	$obj->favoriteCount = $entry->data->favoriteCount;
	$obj->likeCount = $entry->data->likeCount;
	$obj->published = $entry->data->uploaded;
	$obj->rating = $entry->data->rating; 
	$obj->numrating = $entry->data->ratingCount;
	$obj->commentsCount = $entry->data->commentCount;
	$obj->username = $entry->data->uploader;
	//swithcing to user:
	$usr=$entry->data->uploader;
	$user = json_decode(file_get_contents("https://gdata.youtube.com/feeds/api/users/".$usr."?v=2&alt=json"));
	$obj->name = $user->entry->author[0]->name->{'$t'};
	$obj->views = $user->entry->{'yt$statistics'}->totalUploadViews;
	$obj->usr_created = $user->entry->published->{'$t'};

	foreach($user->entry->{'gd$feedLink'} as $link)
		{
		if($link->rel=="http://gdata.youtube.com/schemas/2007#user.subscriptions")
			{
			$obj->subs = $link->countHint;
			}
		elseif($link->rel=="http://gdata.youtube.com/schemas/2007#user.favorites")
			{
			$obj->usr_favs = $link->countHint;
			}
		elseif($link->rel=="http://gdata.youtube.com/schemas/2007#user.contacts")
			{
			$obj->usr_contacts = $link->countHint;
			}
		elseif($link->rel=="http://gdata.youtube.com/schemas/2007#user.uploads")
			{
			$obj->usr_uploads = $link->countHint;
			}
		}
	return $obj;      
	}
else 
	{
	return NULL;
	}
}

//XML parser
function parseVideoEntry2($entry) {   

$obj= new stdClass;  
if ($entry) 
	{
	// get nodes in media: namespace for media information
	if ($media = $entry->children('http://search.yahoo.com/mrss/')) 
		{
		$attrs = $media->group->player->attributes();
		$obj->watchURL = $attrs['url']; 
		$obj->title = $media->group->title;

		}
	return $obj;
	}
else 
	{
	return NULL;
	}
}
?>