<?php
/* vim: tabstop=4:softtabstop=4:shiftwidth=4:noexpandtab */
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later.
 *
 *
 * Email API (adapted from third party code)
 *
 * @filesource  email_api.php
 * @package 	TestLink
 * @author 		franciscom
 * @author 		2002 - 2004 Mantis Team (the code is based on mantis BT project code)
 * @copyright 	2003-2015, TestLink community 
 * @link 		http://www.teamst.org/
 *
 *
 * @internal revisions
 * @since 1.9.13
 *
 */


/** PHPMailer is now loaded via Composer autoloader in config.inc.php */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once( 'lang_api.php' );
require_once( 'common.php');
require_once( 'string_api.php');


/** @var mixed reusable object of class SMTP */
$g_phpMailer = null;


/** 
 * sends the actual email 
 * 
 * @param boolean $p_exit_on_error == true - calls exit() on errors, else - returns true 
 * 		on success and false on errors
 * @param boolean $htmlFormat specify text type true = html, false (default) = plain text
 */
function email_send( $p_from, $p_recipient, $p_subject, $p_message, $p_cc='',
                     $p_exit_on_error = false, $htmlFormat = false, $opt = null ) 
{

	global $g_phpMailer;
	$op = new stdClass();
	$op->status_ok = true;
 	$op->msg = 'ok';

    $options = array('strip_email_links' => true);
    $options = array_merge($options,(array)$opt);

	// Check fatal Error
	$smtp_host = config_get( 'smtp_host' );
	if( is_blank($smtp_host) )
	{
		$op->status_ok=false;
		$op->msg=lang_get('stmp_host_unconfigured');
		return $op;  // >>>---->
	}

	$t_recipient = trim( $p_recipient );
	$t_subject   = string_email( trim( $p_subject ) );
	$t_message = trim($p_message);
	$t_message   = $options['strip_email_links'] ? string_email_links($p_message) : $p_message;

	# short-circuit if no recipient is defined, or email disabled
	# note that this may cause signup messages not to be sent

	# Visit https://github.com/PHPMailer/PHPMailer
	# if you have problems with phpMailer
	$mail = new PHPMailer(true); // Enable exceptions

  	// Need to get strings file for php mailer
  	// To avoid problems I choose English
  	$mail->setLanguage('en');

	# Select the method to send mail
	switch ( config_get( 'phpMailer_method' ) )
	{
		case PHPMAILER_METHOD_MAIL: $mail->isMail();
		break;

		case PHPMAILER_METHOD_SENDMAIL: $mail->isSendmail();
				break;

		case PHPMAILER_METHOD_SMTP: $mail->isSMTP();
			# SMTP collection is always kept alive
			$mail->SMTPKeepAlive = true;

			# Copied from last mantis version
			if ( !is_blank( config_get( 'smtp_username' ) ) ) {
				# Use SMTP Authentication
				$mail->SMTPAuth = true;
				$mail->Username = config_get( 'smtp_username' );
				$mail->Password = config_get( 'smtp_password' );
			}

			if ( !is_blank( config_get( 'smtp_connection_mode' ) ) ) {
				$mail->SMTPSecure = config_get( 'smtp_connection_mode' );
			}

			$mail->Port = config_get( 'smtp_port' );


			// is not a lot clear why this is useful (franciscom)
			// need to use sometime to understand .
			if( is_null( $g_phpMailer ) )  
			{
				register_shutdown_function( 'email_smtp_close' );
			} 
			else 
			{
				$mail = $g_phpMailer;
			}
		break;
	}

	$mail->isHTML($htmlFormat);    # set email format to plain text or HTML
	$mail->WordWrap = 80;
	$mail->Priority = config_get( 'mail_priority' );   # Urgent = 1, Not Urgent = 5, Disable = 0

	$mail->CharSet = config_get( 'charset');
	$mail->Host = config_get( 'smtp_host' );

	// Set From address
	$from_email = !is_blank( $p_from ) ? $p_from : config_get( 'from_email' );
	$mail->setFrom($from_email, '');

	$return_path = config_get( 'return_path_email' );
	if (!is_blank($return_path)) {
		$mail->Sender = $return_path;
	}

	$t_debug_to = '';
	# add to the Recipient list
	$t_recipient_list = explode(',', $t_recipient);

	foreach ($t_recipient_list as $t_recipient) {
		if ( !is_blank( $t_recipient ) ) {
				$mail->addAddress( $t_recipient, '' );
		}
	}

  	$t_cc_list = explode(',', $p_cc);
	foreach ($t_cc_list as $t_cc) {
		if ( !is_blank( $t_cc ) ) {
				$mail->addCC( $t_cc, '' );
		}
	}

	$mail->Subject = $t_subject;
	$mail->Body    = make_lf_crlf( "\n".$t_message );

	if ( !$mail->send() ) {

		if ( $p_exit_on_error )  {
		  PRINT "PROBLEMS SENDING MAIL TO: $p_recipient<br />";
		  PRINT 'Mailer Error: '. $mail->ErrorInfo.'<br />';
		  exit;
		}
		else
		{
		  	$op->status_ok = false;
      		$op->msg = $mail->ErrorInfo;
    		return $op;
		}
	}

	return $op;
}



/**
 * closes opened kept alive SMTP connection (if it was opened)
 * 
 * @param string 
 * @return null
 */
function email_smtp_close() {
	global $g_phpMailer;

	if( !is_null( $g_phpMailer ) ) {
		if( $g_phpMailer->getSMTPInstance() && $g_phpMailer->getSMTPInstance()->connected() ) {
			$g_phpMailer->smtpClose();
		}
		$g_phpMailer = null;
	}
}

# --------------------
# clean up LF to CRLF
function make_lf_crlf( $p_string ) {
	$t_string = str_replace( "\n", "\r\n", $p_string );
	return str_replace( "\r\r\n", "\r\n", $t_string );
}

# --------------------
# Check limit_email_domain option and append the domain name if it is set
function email_append_domain( $p_email ) {
	$t_limit_email_domain = config_get( 'limit_email_domain' );
	if ( $t_limit_email_domain && !is_blank( $p_email ) ) {
		$p_email = "$p_email@$t_limit_email_domain";
	}

	return $p_email;
}
?>