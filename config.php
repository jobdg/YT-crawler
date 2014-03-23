<?php
 $dbh = mysql_connect('localhost','root','root') or die('Cannot connect to the database: '. mysql_error());
 $db_selected = mysql_select_db('thesis') or die ('Cannot connect to the database: ' . mysql_error());
$project="thesis2";
$prefix="thesis_";
$numvideos=100;
$mpdirectory="magpierss-0.72";
$ytdldirectory="";
?>
