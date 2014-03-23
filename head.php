<html>
<head>
<!-- Latest compiled and minified CSS -->
<script type="text/javascript"   src="http://code.jquery.com/jquery-2.1.0.min.js"></script>

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<script>
function start_action(action,name)
{
var today = new Date();
var h = today.getHours();
var m = today.getMinutes();
var s = today.getSeconds();
var text = name + ' has started (' + h + ':' + m + ':' + s + ') <i class="fa fa-refresh fa-spin"></i>';
$("#status").html(text);  
var jqxhr = $.ajax( action )
  .done(function() {
    $("#status").html('success');  
    //alert( "success" );
  })
  .fail(function() {
    $("#status").html('error');  
    alert( "error" );
  })
  .always(function() {
    var text = name + ' has been completed (' + h + ':' + m + ':' + s + ')';
    $("#status").html(text);  
    //alert( "complete" );
  });
}
$(document).ready(function() {
  setInterval(function() {
    $( "#log" ).load( "load_status.php?a=log" );
    $( "#num_today" ).load( "load_status.php?a=num_today" );
    $( "#new_today" ).load( "load_status.php?a=new_today" );
  }, 4000);
});
</script>

<style type="text/css">
.main{
	width: 90%;
	padding: 25px;
	margin-left: auto;
	margin-right: auto;
	background-color: #FFF;
	margin-top: 25px;
}
body{
	background-color: #EEE;
}
.red{
	color: #F00;
}
#log{
  max-height: 200px;
  overflow: scroll;
}
</style>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">YouTube crawler</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="dash.php">Dashboard</a></li>
        <li><a href="dash_query.php">Queries</a></li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="main">