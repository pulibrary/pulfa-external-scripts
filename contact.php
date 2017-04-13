<?php
$sites = array(
    	"mudd" => "mudd@princeton.edu",
    	"rbsc" => "rbsc@princeton.edu",
		"feedback" => "shaune@princeton.edu",
		"engineering library" => "wdressel@princeton.edu",
		"test" => "shaune@princeton.edu",
		"eng" => "wdressel@princeton.edu",
		"lae" => "rbsc@princeton.edu",
		"mss" => "rbsc@princeton.edu",
		"rarebooks" => "rbsc@princeton.edu",
		"ga" => "jmellby@princeton.edu",
		"publicpolicy" => "mudd@princeton.edu",
		"univarchives" => "mudd@princeton.edu"
	);

if(!array_key_exists($_REQUEST['site'], $sites)){
	echo "Sorry, an error occurred.";
	exit();
}else{
	// clean the params
	$url = htmlentities($_REQUEST['url']);
  $title = htmlentities($_REQUEST['title']);
  $suggest = 0;
  if ($_REQUEST['suggest']=='1'){
    $suggest = 1;
    $sites['mss'] = "mssdiv@princeton.edu";
    $sites['mudd'] = "muddts@princeton.edu";
    $sites['publicpolicy'] = "muddts@princeton.edu";
    $sites['univarchives'] = "muddts@princeton.edu";
  }
  $to_email = $sites[$_REQUEST['site']];
}

if(isset($_POST['send'])) {

    if(trim($_POST['name']) == '') {
        $hasError = true;
    } else {
        $name = trim($_POST['name']);
    }

    if(trim($_POST['subject']) == '') {
        $hasError = true;
    } else {
        $subject = "[PULFA] " . trim($_POST['subject']);
    }

    if(trim($_POST['email']) == '')  {
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }

    if(trim($_POST['message']) == '') {
        $hasError = true;
    } else {
        if(function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['message']));
        } else {
            $comments = trim($_POST['message']);
        }
    }

    $context = $_POST['context'];
    $boxnum = trim($_POST['boxnum']);

    if(isset($_POST['boxnum']) && $boxnum != '') {
      $context = $context . "\n\nBox/Container Number:\n" . $boxnum;
    }

    if(!isset($hasError)) {
        $emailTo = $to_email;
        $body = "Name: $name \n\nEmail: $email \n\nSubject: $subject \n\nComments:\n $comments \n\nContext:\n $context";
        // $headers = "From: " . $name . " <" . $email . ">" . "\r\n" . "Reply-To: " . $email;
        /*
        $headers = 'From: lsupport@princeton.edu' . "\r\n" .
                    'Reply-To: lsupport@princeton.edu' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        */
        $headers = 'From:' . $email . "\r\n" .
                    'Reply-To:' . $email . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Finding Aids - Contact</title>

        <!--[if IE]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="//api2.libanswers.com/1.0/widgets/850"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <link href="override.css" rel="stylesheet" media="screen, projection" />


    </head>
    <body>
      <div id="wrapper" class="clearFix">
          <div class="clearFix">
            <div class="container">


              <div class="row">
                  <div class="<?php if($suggest || $emailSent == true) { echo 'col-xs-12'; } else { echo 'col-xs-6'; } ?>" style="border-right:1px solid #EEE">
                    <?php if(isset($emailSent) && $emailSent == true) { ?>
                      <div class="alert alert-success"><strong>Thank you for your <?php echo ($_REQUEST['site']=='feedback' ? 'feedback' : 'submission'); ?>.</strong> <?php if(!$suggest){ echo "We'll get back to you soon."; } ?></div>
                    <?php } else { ?>
                    <form style="padding-top: 10px; background:white;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Name</label>

                          <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="<?php if(isset($hasError) && isset($name)) {echo $name;} ?>">

                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="control-label">Email</label>

                          <input type="email" class="form-control" id="email" name="email" placeholder="Your Email Address" value="<?php if(isset($hasError) && isset($email)) {echo $email;} ?>">

                      </div>


                      <div class="form-group">
                        <?php if($suggest) { ?>
                            <label for="inputBoxNum" class="control-label">Box/Container Number (optional)</label>
                            <input type="text" class="form-control" id="boxnum" name="boxnum" placeholder="#" value="<?php if(isset($hasError) && isset($boxnum)) {echo $boxnum;} ?>">
                            <input type="hidden" name="subject" value="Suggest a Correction">
                        <?php } else { ?>
                          <label for="inputSubject" class="control-label">Subject</label>

                            <select id="subject" name="subject" class="form-control">
                              <option value="<?php echo $title; ?>">This Collection</option>
                              <option value="reproduction">Reproductions &amp; Photocopies</option>
                              <option value="permission">Rights &amp; Permissions</option>
                              <option value="access">Access</option>
                              <option value="how much">Other</option>
                            </select>
                        <?php } ?>
                      </div>

                      <div class="form-group">
                        <label for="question" class="control-label">Message</label>

                          <textarea id="message" name="message" class="form-control" rows="5" placeholder="<?php if($suggest) { echo "Please use this area to report errors or omissions in the description of this collection. Corrections may include misspellings, incorrect or missing dates, misidentified individuals, places, or events, mislabeled folders, misfiled papers, etc."; } else { echo "Your message."; } ?>"><?php if(isset($hasError) && isset($comments)) {echo $comments;} ?></textarea>
                          <?php if(isset($hasError)) { ?>

                            <p class="help-block" style="color:red;"><strong>Oops!</strong> Please check that you've filled in all the fields.</p>

                          <?php } ?>

                          <?php if(!isset($emailSent) && !isset($hasError)) { ?>
                            <p class="help-block"><?php if($suggest) { echo "If this is not a correction, please close this window and click the &quot;Ask a Question&quot; button."; } else { echo "Please be as specific as possible."; } ?></p>
                          <?php } ?>

                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="hidden" name="context" id="context" value="<?php echo ($_REQUEST['site']=='feedback' ? 'Feedback' : 'Question'); ?> about: <?php echo $url; ?>"/>
                          <input type="hidden" name="site" value="<?php echo $_REQUEST['site']?>">
                          <input type="hidden" name="url" value="<?php echo $url; ?>">
                          <input type="hidden" name="title" value="<?php echo $title; ?>">
                          <input type="hidden" name="suggest" value="<?php echo $suggest; ?>">
                          <button type="submit" name="send" id="send" class="btn btn-default pull-right">Send</button>
                        </div>
                      </div>
                    </form>
                    <?php } ?>
                  </div>
                  <?php if(!$suggest && $emailSent != true) { ?>
                  <div class="col-xs-6" style="border-right:1px solid #EEE">
                    <h4>Quick Answers</h4>
                    <p>Has your question already been answered?</p>
                    <div id="s-la-widget-850"></div>

                    <div class="faq-set" style="padding-top: 10px;">

                      <p class="faq"><a href="http://faq.library.princeton.edu/rbsc/faq/26972" target="_blank">How can I access Princeton University Library special collections?</a></p>
                      <p class="faq"><a href="http://faq.library.princeton.edu/rbsc/faq/64876" target="_blank">How can I purchase copies from rare books and special collections?</a></p>
                      <p class="faq"><a href="http://faq.library.princeton.edu/rbsc/faq/56766" target="_blank">Do I need permission to quote or publish from rare books and special collections?</a></p>

                    </div>
                  </div>
                  <?php } ?>
            </div>
          </div>
      </div>




      <script language="JavaScript">

        jQuery( document ).ready(function() {

          // check for all empty fields and make them red.
                  // remove red border around field when text is entered

          //window.onload = function(){
          jQuery( "#subject" ).on( "change", function( event ) {
              str = jQuery( "#subject option:selected" ).val();
              refreshFAQs(str);
          });

          refreshFAQs("Special Committee on the Structure of the University Records");

          function clearFAQs(){
               jQuery( ".faq-set" ).empty();
            }

          function sanitize(str){
            str = str.replace(/ /g, '_');
            str = str.replace(/\W/g, '');
            str = str.replace(/_/g, '+');
            return str;
          }

          function refreshFAQs(keywords){
            keywords = sanitize(keywords);

            $.getJSON( "http://library.princeton.edu/utils/faq/" + keywords, {group_id:"1059"},function( data ) {
              if(data.records.length > 0){
                clearFAQs();

                var items = [];
                $.each( data.records, function( key, val ) {

                items.push( "<p class='faq'><a href='" + val.url + "' target='_blank'>" + val.question + "</a></p>" );
               });

                faqset = '<h4>FAQs related to ' + jQuery( "#subject option:selected" ).text().toLowerCase() + '</h4>';
                jQuery( ".faq-set" ).html( faqset );
                jQuery( items.join( "" ) ).appendTo( ".faq-set" );

                jQuery( ".faq" ).addClass( "faq-flash" );
              }

            });

          }
        //});
        });

      </script>

    </body>

</html>
