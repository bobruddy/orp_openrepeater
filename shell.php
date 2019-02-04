<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$pageTitle = "SSH Shell Access"; 

/*
include_once("includes/get_settings.php");
include_once("includes/get_modules.php");
$dbConnection->close();
*/

include('includes/header.php');

$Database = new Database();
$settings = $Database->get_settings();

$ModulesClass = new Modules();
$modules = $ModulesClass->get_modules();

?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="settingsUpdate" name="settingsUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i> SSH Shell Access</h2>
					</div>
					<div class="box-content">
						<p>This provides ssh shell access to your openrepeater. Please be advised this is an advanced feature. You manual configuration may be overriden by the openreater framework or you man render openrepeater unable to manage your repeater.</p>
						<br>

						<fieldset>
							  
							<legend>SSH Access</legend>

								<p>Controls:</p>
								<div>
									<input type="text"   id="input"></input>
									<input type="button" id="execute" value="Execute"></input>
									<input type="button" id="output-enable"  value="Output Enable"></input>
									<input type="button" id="output-disable" value="Output Disable"></input>
									<input type="button" id="reconnect" value="Reconnect"></input>
									<input type="button" id="session-reload" value="Session Status"></input>
									<input type="button" id="session-toggle" value="Session Status Toggle"></input>
								</div>
							
								<p id="session">Session status: ???</p>
							
								<!--
									Embedded shellinabox. In our case src attribute will be added with help
									of JS. -->
								<iframe id="shell" src="" width="100%" height="600px"></iframe>
							
								<!-- Ouput -->
								<p>Terminal output:</p>
								<pre id="output"></pre>
							
								<script>
							
									// Shellinabox url
									var url = "https://localhost:7431";
							
									var input   = document.getElementById("input");
									var iframe  = document.getElementById("shell");
									var output  = document.getElementById("output");
									var session = document.getElementById("session");
							
									document.getElementById("execute").addEventListener("click", function() {
										// Send input to shellinabox
										var message = JSON.stringify({
											type : 'input',
											data : input.value + '\n'
										});
										iframe.contentWindow.postMessage(message, url);
									});
							
									document.getElementById("output-enable").addEventListener("click", function() {
										// Enable output replay from shellinabox iframe
										var message = JSON.stringify({
											type : 'output',
											data : 'enable'
										});
										iframe.contentWindow.postMessage(message, url);
									});
							
									document.getElementById("output-disable").addEventListener("click", function() {
										// Disable output replay from shellinabox iframe
										var message = JSON.stringify({
											type : 'output',
											data : 'disable'
										});
										iframe.contentWindow.postMessage(message, url);
										// Clear output window
										output.innerHTML = '';
									});
							
									document.getElementById("session-reload").addEventListener("click", function() {
										// Request shellianbox session status
										var message = JSON.stringify({
											type : 'session'
										});
										iframe.contentWindow.postMessage(message, url);
									});
							
									document.getElementById("session-toggle").addEventListener("click", function() {
										// Toggles shellinabox session status reporting
										var message = JSON.stringify({
											type : 'onsessionchange',
											data : 'toggle'
										});
										iframe.contentWindow.postMessage(message, url);
									});
							
									document.getElementById("reconnect").addEventListener("click", function() {
										// Request shellianbox session status
										var message = JSON.stringify({
											type : 'reconnect'
										});
										iframe.contentWindow.postMessage(message, url);
									});
							
									// Receive response from shellinabox
									window.addEventListener("message", function(message) {
							
										// Allow messages only from shellinabox
										if (message.origin !== url) {
											return;
										}
							
										// Handle response according to response type
										var decoded = JSON.parse(message.data);
										switch (decoded.type) {
										case "ready":
											// Shellinabox is ready to communicate and we will enable console output
											// by default.
											var message = JSON.stringify({
												type : 'output',
												data : 'enable'
											});
											iframe.contentWindow.postMessage(message, url);
											break;
										case "output" :
											// Append new output
											output.innerHTML = output.innerHTML + decoded.data;
											break;
										case "session" :
											// Reload session status
											session.innerHTML = 'Session status: ' + decoded.data;
											break;
										}
									}, false);
							
									// Add url to our iframe after the event listener is installed.
									iframe.src = url;
							
								</script>




						</fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>   

    
<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
