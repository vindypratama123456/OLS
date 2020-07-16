<!DOCTYPE html>
<html>
<link rel="stylesheet" href="css/bootstrap.min.css" media="all" />
<body class="container">
  
<?php

/////////////////////////////////////////////////////////////////////////////
// Globals
static $client_state = "100100";
// The `client_id` and `client_secret` which were added to the database. See README.md.
static $client_id = "bkk13ad";
static $client_secret = "ae035c5653e256aa8a0a53ed3cbd9db6";
$redirect_uri = 'http://bukusekolah.gramedia.com/akunsaya/verify';
$token_endpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/token";
$authorize_endpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/auth";
$profile_endpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/profile";
$sekolah_endpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/bosdata";
$sess_endpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/sessid";

// Fetch the "Authentication Code" from the GET params
$auth_code = isset($_GET["code"]) ? $_GET["code"] : null;
$token_form = isset($_GET["token_form"]) ? true : false;
$profile_form = isset($_GET["profile_form"]) ? true : false;
$sekolah_form = isset($_GET["sekolah_form"]) ? true : false;

if ($auth_code && !$token_form) {
	// We just got redirected to with an auth token so we display it and the next steps
?>
	<h1>Simulasi Sistem Dapodikdas</h1>
	<h4>We received an Authentication Token from the SSO provider!</h4>
	<p>
		<strong>Authentication Code:</strong> <?php echo $auth_code; ?> <br>
	</p>
	<p>Our simulated dapodikdas server received an authentication token so now it can:</p>
	<h4>1. Upgrade authentication token to an "Access Token"</h4>
	<div>(Push the button to request an access token)</div>
	<div class="row-fluid">
		<iframe src="<?php echo base_url(); ?>akunsaya/sso?token_form=1&code=<?php echo $auth_code?>" class="container well well-small span6">
		</iframe>
	</div>
	<h4>2. Use the "access token" to fetch a user profile resource</h4>
	<div>(Copy the token from above and paste it into the form and request the user profile)</div>
	<div class="row-fluid">
		<iframe src="<?php echo base_url(); ?>akunsaya/sso?profile_form=1" class="container well well-small span6">
		</iframe>
	</div>
	<h4>3. Use the "access token" to fetch a user satuan pendidikan (sekolah)</h4>
	<div>(Copy the token from above and paste it into the form and request the satuan pendidikan)</div>
	<div class="row-fluid">
		<iframe src="<?php echo base_url(); ?>akunsaya/sso?sekolah_form=1" class="container well well-small span6">
		</iframe>
	</div>
	<div><a href="demo_client.php">Start Over</a></div>
		
<?php
} elseif ($auth_code && $token_form) { 
	// We got the "token_form" flag so we render just the token form for the iframe
?>
    <?php echo form_open($token_endpoint, 'method="POST"'); ?>
		<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
		<input type="hidden" name="client_secret" value="<?php echo $client_secret; ?>">
		<input type="hidden" name="grant_type" value="authorization_code">
		<input type="hidden" name="redirect_uri" value="<?php echo $redirect_uri; ?>">
		<input type="hidden" name="code" value="<?php echo $auth_code; ?>">
		<input type="submit" value="Get Access Token">
	<?php echo form_close(); ?>
<?php
} elseif ($profile_form) { 
	// We got the "profile_form" flag so we render just the profile fetch form for the iframe
?>
    <?php echo form_open($sess_endpoint, 'method="POST"'); ?>
		<input type="text" name="access_token" value="" placeholder="access token">
		<div><input type="submit" value="Get User Profile"></div>
	<?php echo form_close(); ?>
	
<?php
} elseif($sekolah_form) {
?>
    <?php echo form_open($sekolah_endpoint, 'method="POST"'); ?>
		<input type="text" name="access_token" value="" placeholder="access token">
		<div><input type="submit" value="Get Sekolah"></div>
	<?php echo form_close(); ?>
<?php
} else {
	echo '<div>Unknown mode?!?</div>';
}
?>

</body>
</html>