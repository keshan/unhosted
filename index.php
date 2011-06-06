<?php
if(isset($_GET['checkdir']))
  { $dirpath=getcwd().'/'.$_GET['checkdir'];
    if( is_writable($dirpath))
      echo "True";
    else if (file_exists($dirpath))
      echo "Exists";
    else
      echo "False";
    exit();
    
  }
?>


<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Unhosted SDK Installer</title>
	<script src="install/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="install/standalone.css">	
	<link rel="stylesheet" type="text/css" href="install/tabs.css">
	<style>
/* tab pane styling */
   .panes div {
 display:none;		
 padding:15px 10px;
 border:1px solid #999;
 border-top:0px;
 font-size:14px;
 background-color:#fff;
 }

   .appdirs {
 position: absolute;
 display: inline;
 right: 500px;
 width: 300px;
    }

	</style>
</head>

<body>

  <h2>Unhosted SDK</h2>
  <p>
    Click the checkboxes to install different parts of the SDK. Caution: code is still alpha!
  </p>



<!-- the tabs -->
<ul class="tabs">
	<li><a class="current" href="#first">Server</a></li>
	<li><a href="#second">Flower</a></li>
	<li><a href="#third">Hive</a></li>
</ul>

<!-- tab "panes" -->
<div class="panes">
	<div style="display: block;">
	    <form id="flowerform">
	  <ul>
	    <li><input type="checkbox"> Https
	      <ul>
		<li>Obtain a certificate</li>
		<li>Upload it to the server</li>
		<li>Adapt Apache configuration</li>
	      </ul>
	    </li>
	  </ul>
	</div>
	
	<div style="display: none;">
	  <ul>
	    <li><input type="checkbox" id = "apps" checked = "checked">Apps
	    <ul>
	    <?php 
	    $appdir = "flower/apps/";
$dircontents = scandir($appdir);
if($dircontents && (count($dircontents)>2))
  {
    for($i=0; $i < count($dircontents); $i++)
      {
	if( ($dircontents[$i] != '.') && ($dircontents[$i] != '..') )
	  {
	    echo "\n<li><input class= 'appstoinstall'  value='$dircontents[$i]' name = 'appstoinstall' type='checkbox' checked='checked'>". $dircontents[$i].
	      "<input name='appdir' id = 'app_".$dircontents[$i]."'class='appdirs' value='".$appdir.$dircontents[$i]."' type ='text' style='display:inline'><</li>";
	  }
      }
  }	
else
  {
    echo "No apps found. Check the ". $appdir ."to see if all directories are readable";
  }
?>


	    </ul>
	    </li>
	    <li><input type="checkbox"> Wallet
	      <ul>
		<li>PHP installed</li>
		<li>flower/wallet/wallets writable for www-data</li>
	      </ul>
	    </li>
	  </ul>
  <input type = "submit" value="Install Flower" action="#">
  </form>
  </div>

	<div style="display: none;">
	  <ul>
	    <li><input type="checkbox"> Identity
	      <ul>
		<li>Link /.well-known on the domain to hive/identity/.well-known</li>
		<li>Correct contents in hive/identity/.well-known</li>
		<li>Apache mod_headers activated</li>
		<li>PHP installed, or hard-code webfinger with correct contents</li>
	      </ul>
	    </li>
	    <li><input type="checkbox"> Storage
	      <ul>
		<li>Apache mod_dav_fs activated</li>
		<li>Dav On on hive/storage/webdav/</li>
		<li>hive/storage/webdav/ writable for www-data</li>
	      </ul>
	    </li>
	    <li><input type="checkbox"> Control-panel
	      <ul>
		<li>PHP installed</li>
		<li>hive/storage/webdav/ and hive/storage/pwd/ writable for www-data</li>
	      </ul>
	    </li>
	  </ul>

	</div>
</div>


<!-- This JavaScript snippet activates the tabs -->
<script>

// perform JavaScript after the document is scriptable.
  $(function() {
      // setup ul.tabs to work as tabs for each div directly under div.panes
      $("ul.tabs").tabs("div.panes > div");
    
      /* To do the app checkbox thing*/
      $("#apps").change(function (){
	  if($(this).is(":checked")){
	    $(".appstoinstall").attr("checked","checked");
	    $('[id^="app_"]').show();
	  }
	  else{
	    $(".appstoinstall").removeAttr("checked");
	     $('[id^="app_"]').hide();
	  }
	});
      $(".appstoinstall").change(function (){
	  if($('.appstoinstall:checked').length)
	    $("#apps").attr("checked","checked");
	  else
	    $("#apps").removeAttr("checked");

	  $("#app_"+$(this).val()).toggle(); // app_"+$(this).val() gives the id of corresponding dir
	});
      /*end of the checkbox thing*/
      $('[id^="app_"]').change(function () {
	  currentelement=$(this);
	  $.get("",{ checkdir: $(this).val()},function (data) {
	      if(data=="False")
		{
		  currentelement.css({'background-color':'#ff0000'});
		}
	      else
		currentelement.css({'background-color':'#00ff00'});
	    });
	});
    });
</script>

</body></html>
