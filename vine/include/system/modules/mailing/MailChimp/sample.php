<?php
	/**
	 * MailChimp API Wrapper Sample
	 * Author: Andrew Aculana
	 * for suggestions and inquery email me at xdreucker@gmail.com
	 * 
	 * Thank you for using this wrapper
	 */
	
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include("mcapi_rpc.php");

    $type = 'regular';

    $opts['list_id']      = '04e1b11425c';
    $opts['subject']      = 'Test Newsletter Subject';
    $opts['from_email']   = 'no-reply@noemail.com';
    $opts['from_name']    = 'Test From Name';

    $opts['tracking']     = array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);

    $opts['authenticate'] = true;
    $opts['analytics']    = array('google'=>'my_google_analytics_key');
    $opts['title']        = 'Test Newsletter Title';

    $content = array('html'=>'some pretty html content *|UNSUB|* message', 'text' => 'text text text *|UNSUB|*');

    $MCAPI_RPC = new mcapi_rpc("66db2ef0d7223b319170fe858b6317127-us1");
    $MCLists = $MCAPI_RPC->campaignCreate($type, $opts, $content);
    if(mcapi_rpc_error::isError($MCLists)){
        echo "Error:".$MCLists->getErrorMessage();
    }else{
        echo 'Success';
    }

?>
