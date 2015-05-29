<?php
/*  Copyright 2013 Viktorix Innovative  (email : support@viktorixinnovative.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$plugin_name = 'contact-form-7/wp-contact-form-7.php';
$is_active = is_plugin_active($plugin_name);
if ($is_active == '1') {
} else {
  echo '<br /><br /><div class="alert alert-error">Contact Form 7 plugin is required to configure this Nimble plugin. Please install and activate Contact Form 7 plugin and then return to this page.</div>';
  exit;
}

require_once('api_nimble.php');

$nimble = new NimbleAPI();


if (isset($_POST['nimble_reset']))
{
		update_option('nimble_access_token','');
		update_option('nimble_refresh_token','');
		update_option('nimble_client_id', ''); 
		update_option('nimble_client_secret', '');	
		update_option('nimble_redirect_uri', '');		
}

$flag = 1;
if ((isset($_POST['client_id'])) && (isset($_POST['client_secret'])) && (isset($_POST['redirect_uri']))) {
    $handle = 'https://api.nimble.com/oauth/authorize?client_id=' . $_POST["client_id"] . '&redirect_uri=' . $_POST["redirect_uri"] . '&response_type=code';
   echo '<script type="text/javascript">  window.location = "' . $handle . '"  </script>';
  //  echo "samad";
  
  		$client_id = $_POST['client_id'];  
         update_option('nimble_client_id', $client_id);  
   
		 $client_secret = $_POST['client_secret'];	
		update_option('nimble_client_secret',$client_secret);
         
		$redirect_uri = $_POST['redirect_uri'];
		 update_option('nimble_redirect_uri',$redirect_uri);
 
} else if (isset($_GET["code"])) {

		 
    $response_data = $nimble->nimble_get_access_token($_GET["code"]);
    if ($response_data[0] == 200) {
	
		update_option('nimble_access_token',$response_data[1]->access_token);
		update_option('nimble_refresh_token',$response_data[1]->refresh_token);
		 echo "<div class='updated'><p>Nimble API settings have been sucessfully saved. Please map Nimble fields with Contact Form 7 fields to complete the integration process.</p></div>";
		
        $flag = 0;
    } else {
        echo "<div class='error'><p>Error  = " . $response_data[1]->msg . "<br>". $response_data[1]->error ."</p></div>";
        
    }
 
 
}

?>


<script type="text/javascript">


function aabb()
{
var a=document.forms["nimbleform"]["client_id"].value;
var b=document.forms["nimbleform"]["client_secret"].value;
var c=document.forms["nimbleform"]["redirect_uri"].value;
var flag;

if (a=='' || a=='client_id')
{
   document.getElementById("client_id").style.borderColor="#FF0000";
   document.getElementById("client_id").style.borderWidth="1px";
   document.getElementById("client_id").value="client_id";
}
else
{
 document.getElementById("client_id").style.borderColor="#D6D6D6";
    document.getElementById("client_id").style.borderWidth="0px";
}

if (b=='' || b=='client_secret')
{
   document.getElementById("client_secret").style.borderColor="#FF0000";
      document.getElementById("client_secret").style.borderWidth="1px";
   document.getElementById("client_secret").value="client_secret";
}
else
{
 document.getElementById("client_secret").style.borderColor="#D6D6D6";
      document.getElementById("client_secret").style.borderWidth="0px";
}

if (c=='' || c=='redirect_uri')
{
  document.getElementById("redirect_uri").style.borderColor="#FF0000";
  document.getElementById("redirect_uri").style.borderWidth="1px";
 document.getElementById("redirect_uri").value="redirect_uri";
}
else
{
 document.getElementById("redirect_uri").style.borderColor="#D6D6D6";
   document.getElementById("redirect_uri").style.borderWidth="0px";
}



if ( ((a=='' ) || (a=='client_id')) || ((b=='' ) || (b=='client_secret')) || ((c=='' ) || (c=='redirect_uri')) )
return false;
}

</script>

<br />
<h1>Nimble App Connection</h1>
<?php
if ($flag == 1) {


if ((get_option('nimble_access_token')=='')&&(get_option('nimble_refresh_token')=='')) {

?>
<div class="updated" style="width:400px;"> <p>You must request API credentials from Nimble, they have closed their developer center for now. <a href="http://support.nimble.com/customer/portal/articles/1194074-nimble-api-access#1" target="_blank">Get API credentials &raquo;</a></p></div>
<br />
<div id="footercontent" class="container">
	    <div class="row">
		<div class="span4">

<form onsubmit="return aabb();" name="nimbleform" method="POST" action="<?php
    echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']);
?>">
	<table style="width:400px;" cellspacing="15">
    <tr>
        <td><input style="width:250px;" type="text" class="span4" onblur="if(value=='') value = 'Client ID'" onfocus="if(value!='') value = ''" value="Client ID" id="client_id" name="client_id" >
</td>
    </tr>
    <tr>
        <td><input style="width:250px;" type="text" class="span4" onblur="if(value=='') value = 'Client Secret'" onfocus="if(value!='') value = ''" value="Client Secret" id="client_secret" name="client_secret" >
	</td>
    </tr>
    <tr>
        <td><input style="width:250px;" type="text" class="span4" onblur="if(value=='') value = 'Redirect URI'"  value="<?php echo  "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ?>" id="redirect_uri" name="redirect_uri">
	 </td>
    </tr>
    <tr><td>
    <input type="hidden" name="bcpl_hidden" value="Y">  
	 <button  class="button button-primary button-large">Click to Authorize Connection &raquo;</button>
    </td></tr>
</table>		
	 
</form>
</div>
</div>	
</div>	
	
<?php
}
else
{
  echo "<br /><div class='nimble-wrap' style='width:550px;'><div class='updated'><p>Nimble API settings have been sucessfully saved. Please map Nimble fields with Contact Form 7 fields to complete the integration process.</p></div><br />";
 ?>

<form name="nimbleform2" style="text-align:center;" method="POST" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	  <input type="hidden" name="nimble_reset" value="Y">   
	 <button  class="button button-secondary button-large">Reset Settings</button> <br /><br />
	 <div class="error"> <p>Resetting settings will disconnect plugin from your Nimble account.</p></div> 
</form>

</div><!--nimble-wrap-->
<?php
}
}
?>