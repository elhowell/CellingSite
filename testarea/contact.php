<?php
$domainName = 'Medrebels.org'; //this is the domain name for which you are setting up the form.
$toaddress = 'medrebels@gmail.com';  //This is the address or addresses the email should be sent to.  You can add more than one by separating with commas.

$issent = 0; //set to zero until the mail is sent.  We will use this to show / hide the form based on whether it has been sent or not.
if(isset($_POST['submitted'])){ //The form has been submitted.  Now time to process the form input

	$errors = array(); //somewhere to put the errors
	
	$successmessage = array(); //somwhere to put the success message
	
	//The heal function is used to clean up malicious code that can cause Email header injection issues
	//I have this here in case it is necessary to have the email come from the address that was submitted with the form
	//I DO NOT recommend this, though.  Just put the email address in the body and have the email come from
	//noreply@thedomain.com (of course thedomain.com is the domain you are setting this up for
	//This will cause fewer delivery issues.
	function heal($str) {
		$injections = array('/(\n+)/i','/(\r+)/i','/(\t+)/i','/(%0A+)/i','/(%0D+)/i','/(%08+)/i','/(%09+)/i');
		$str= preg_replace($injections,'',$str);
		return $str;
	}
	//The check_email_address function returns true if it is passed a valie email address
	function check_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}
	
	//function to check an email
	function check_phone($phone) {
		if(!ereg("^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$",$phone)) {
			return false;
		}
		return true;
	}
	
	//validate the name
	if(!empty($_POST['name'])) { //if it is not empty do some more checks
		$name = strip_tags(trim($_POST['name'])); //strip whitespace and tags
		if(strlen($name) < 3 || strlen($name) > 40) { //make sure it is between 3 and 40 characters long
			$errors['namelength'] = 'Name must be between 3 and 40 characters long.';
		}
	} else { //if it is empty add an error to the array
		$errors['nameblank'] = 'Name is required.';
	}
	
	//validate the email
	if(!empty($_POST['email'])) { //if it is not empty run the check
		$email = trim($_POST['email']);
		if(!check_email_address($email)) {  //if it does not pass the test throw an error
			$errors['emailinvalid'] = 'That is not valid email address.';
		}
	} else { //if it is empty add an error
		$errors['emailblank'] = 'Email address is required.';
	}
	
	if(!empty($_POST['comments'])) { //if you want to require comments add and error this does not require comments as it is now
		$comments = strip_tags($_POST['comments']);
	}
	
	if(!empty($_POST['phone'])) { //if a phone number is added we will validate it .  Otherwise not.
		$phone = trim($_POST['phone']);
		$phonematch = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/'; 
		if(!preg_match($phonematch,$phone)) {
			$errors['phoneinvalid'] = 'That is not a valid phone number.';
		}
	}
	
	if(empty($errors)) { //all input data validated.  Nice work.
	
		//gathering a little data about the person submitting the form just in case we have issues
		$ip = $_SERVER['REMOTE_ADDR'];
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$refer = $_SERVER['HTTP_REFERER'];
		
		//Now we will build the email body based on the input
		$emailbody = '';
		$emailbody .= 'Name:  ' . $name . "\n";
		$emailbody .= 'Email:  ' . $email . "\n";
		$emailbody .= 'Phone:  ' . $phone . "\n";
		$emailbody .= 'Comments:  ' . stripslashes($comments) . "\n";
		$emailbody .= "\n\n\n==============================\n";
		$emailbody .= "==============================\n";
		$emailbody .= "===EMAIL ORIGIN INFORMATION===\n";
		$emailbody .= "==============================\n";
		$emailbody .= "==============================\n";
		$emailbody .= 'IP: ' . $ip . "\n";
		$emailbody .= 'Browser: ' . $browser . "\n";
		$emailbody .= 'Referer: ' . $refer . "\n";
		$emailbody .= "==============================\n";
		$emailbody .= "==============================\n";
		
		//now we will build the email headers to send a valid email in the proper format
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
		$header .= 'From: noreply <noreply@' . $domainName . '>' . "\r\n";
		$header .= 'Reply-to: noreply <noreply@' . $domainName . '>' . "\r\n";
		$header .= 'Return-path: postmaster <postmaster@' . $domainName . '>' . "\r\n";
		
		$subject = 'Web form submission from ' . $domainName; //email subject
		
		//message is ready...headers are set.  Now send an email.
		if(mail($toaddress,$subject,$emailbody,$header)) {
			$issent = 1; //we have a successful submission
			$successmessage[] = 'Your message has been submitted to ' . $domainName;
			$successmessage[] = 'We will be in touch soon.';
			$successmessage[] = 'Thank You!';
		} else {
			$errors['emailsenderror'] = 'There was a technical issue sending the message.'; //could not send an email for whatever reason
		}
	}
}

$pagetitle = 'Contact MedRebels';

include('includes/pagetop.php'); 

?>




</head>

<link href="css/style.css" rel="stylesheet" type="text/css">
<body>



<div id="pagewrap">

	<div id="header">

		<?php include('includes/header.php'); ?>	

	</div><!-- end #header -->

	<div style="clear: both;"></div>

	

	<div id="maincontent">

		<div id="maininterior-black">	

			<div id="contact_form"><!-- You can rename this to whatever works for your site -->
	
				<h2 class="form_header">Contact Us</h2><!-- This is your header for the contact form -->        
				
				<?php 
				if($issent !== 1){ //the form HAS NOT been sent.  We will display it and any errors if they were encountered.
					if(!empty($errors)) { // if there are errors, we will loop through them and show them
						foreach($errors as $error) { //start the error loop
							echo '<p class="formerror">' . $error . '</p>'; //print the errors to the screen.   Note the class.  Style it to your liking
						}
					}
				?>
				<!-- the form was not sent or had errors so we will show the form now -->
                
				<p>Fields with * are required</p> <br>
                
                 <fieldset><legend>Contact</legend>
		  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" name="contactform" method="post" class="contactform">

					<label for="contactname">*Name:</label>
					<input name="name" type="text" id="contactname" class="textinput" size="40" value="<?php if(isset($name)) { echo htmlspecialchars($name, ENT_QUOTES); } ?>" /> <br><br>
						
					<label for="contactphone">&nbsp;Phone:</label>
					<input name="phone" type="text" id="contactphone" class="textinput" size="40" value="<?php if(isset($phone)) { echo htmlspecialchars($phone, ENT_QUOTES); } ?>" /><br><br>
						
					<label for="contactemail">*Email:</label>
					<input name="email" type="text" id="contactemail" class="textinput"  size="40" value="<?php if(isset($email)) { echo htmlspecialchars($email, ENT_QUOTES); } ?>" />  <br><br>                 
						 
                  
					<label for="contactcomments">Message:</label>
					<textarea name="comments" class="textareainput" id="contactcomments" cols="100" rows="5"><?php if(isset($comments)) { echo htmlspecialchars($comments, ENT_QUOTES); } ?>
					</textarea>
					<br><br>
					<div align="center">
					<input name="submitted" type="hidden" value="true" />
					<input type="image" src="images/sendinquiry.JPG"class="contactformsubmit" name="emailus" value="Send" />
							  </div>
			  </form>
              </fieldset>   
			<?php
				} else { //the form was sent.  Hooray!  Now show some success messages
					if(!empty($successmessage)) { // show success
						echo '<div class="formsuccess">';
						foreach($successmessage as $success) { //start the success loop
							echo '<span class="success">' . $success . '</span><br />'; //print the success to the screen.   Note the class.  Style it to your liking
						}
						echo '</div>';
					}
				}
			?>
			</div><!-- /#contact_form -->

		<div style="clear: both;"></div>	

		</div><!-- end #maininterior -->

		

	</div><!-- end #maincontent -->

	<div style="clear:both;"></div>

	<div id="footer">

		<?php include('includes/footer.php'); ?>

	</div><!-- end #footer -->

	<div style="clear: both;"></div>

</div><!-- end #pagewrap -->

<?php include('includes/pagebottom.php'); ?>