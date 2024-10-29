<?php 
/*   
	Plugin Name: Auto Tweet plugin
	Plugin URI: http://www.zpetneodkazy-linkbuilding.com
	Description: Auto Tweet plugin lets you automatically tweet blog posts to your twitter account. Simple set your email address and twitter password and go...
	Author: http://www.zpetneodkazy-linkbuilding.com
	Version: 1.0
	Author URI: http://www.zpetneodkazy-linkbuilding.com
    Disclaimer: No warranty is provided. PHP 5 and Curl are required.
    Requires at least: 3.0
    Tested up to: 3.2
    License: GPLv2
    Text Domain: auto-tweet-plugin
    Domain Path: /
    Inspired by: Richard X. Thripp, Tweet This, http://wordpress.org/extend/plugins/tweet-this/
    
*/
    

require_once('bitly_class.php');
require_once('twitter.php');



function auto_twitter_admin(){ 
    global $l10n;
	if(isset($_POST['submitted'])){
		update_option("auto_twitter_consumerKey", $_POST['auto_twitter_consumerKey']);
		update_option("auto_twitter_consumerSecret", $_POST['auto_twitter_consumerSecret']);
		update_option("auto_twitter_accesToken", $_POST['auto_twitter_accesToken']);
		update_option("auto_twitter_accesTokenSecret", $_POST['auto_twitter_accesTokenSecret']);
        
        update_option("auto_twitter_bitly_username", $_POST['bitly_username']);
        update_option("auto_twitter_bitly_api_key", $_POST['bitly_api_key']);
		echo "<div id=\"message\" class=\"updated fade\"><p><strong>".__('Auto Tweet plugin options updated.','auto-tweet-plugin')."</strong></p></div>";
	}
	
    echo '<script type="text/javascript">function expandid(eid){ '.
		' document.getElementById(eid).style.display = ' .
		'(document.getElementById(eid).style.display != \'block\') ' .
		'? \'block\' : \'none\';}</script>';
    
	echo "<div class=\"wrap\">";
		echo "<h2>".__('Auto Tweet plugin Options','auto-tweet-plugin')."</h2>";
        echo "<h3>".__('OAuth settings','auto-tweet-plugin')."</h3>";
		echo '<h4>'.__('1. Register your website as an application on Twitter`s application registration page. ', 'auto-tweet-plugin') . '<a href="http://dev.twitter.com/apps/new" target="_blank"></a></h4>
						<ul>
						<li>'.__('If you\'re not currently logged in with the Twitter username and password.' , 'auto-tweet-plugin').'</li>
						<li>'.__('Your Application\'s Name will be what shows displays to upwards after "via" in your twitter stream.Your application name protect the word "Twitter." Use the name of your web site. Application Description can be any you want.' , 'auto-tweet-plugin').'</li>
						<li>'.__('Application Description can be any you want.','auto-tweet-plugin').'</li>
						<li>'.__('The WebSite and Callback URL should be ' , 'auto-tweet-plugin').'<strong>'.  get_bloginfo( 'url' ) .'</strong></li>					
						</ul>'.
					'<p>'.__('Agree to the Developer Rules.','auto-tweet-plugin').'</p>
					<h4>'.__('2. Switch to "Settings" tab in Twitter apps','auto-tweet-plugin').'</h4>
						<ul>
						<li>'.__('Select "Read and Write" for the Application Type' , 'auto-tweet-plugin').'</li>
						<li>'.__('Update the application settings' , 'auto-tweet-plugin').'</li>
						<li>'.__('Return to Details tab and create your access token. Refresh page.','auto-tweet-plugin').'</li>		
						</ul>					
					<p><em>'.__('Once you have registered your website as an application, you will be provided with keys.' , 'auto-tweet-plugin').'</em></p>
					<h4>'.__('3. Copy and paste your consumer key and consumer secret into the fields below' , 'auto-tweet-plugin').'</h4>';
                                                    
		echo "<form method=\"post\" >";
			echo "<p><strong><small>".__('Your Twitter Consumer Key:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"auto_twitter_consumerKey\" value='".get_option("auto_twitter_consumerKey")."' style=\"width:250px\" ><br />";
			echo "<p><strong><small>".__('Your Twitter Consumer secret:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"auto_twitter_consumerSecret\" value='".get_option("auto_twitter_consumerSecret")."' style=\"width:250px\" ></p>";
            echo '<h3>Your access token</h3>';
            echo '<h4>'.__('4. Copy and paste your Access Token and Access Token Secret into the fields below','auto-tweet-plugin').'</h4>
					<p>'.__('If the Access level reported for your Access Token is not <strong>"Read and write"</strong>, you need to go back to step 2 and generate a new Access Token.','auto-tweet-plugin').'</p>';

			echo "<p><strong><small>".__('Your Twitter access token:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"auto_twitter_accesToken\" value='".get_option("auto_twitter_accesToken")."' style=\"width:250px\" ><br />";
			echo "<p><strong><small>".__('Your Twitter acces token secret:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"auto_twitter_accesTokenSecret\" value='".get_option("auto_twitter_accesTokenSecret")."' style=\"width:250px\" ></p>";


            
echo	'<p onclick="expandid(\'bit.ly\')" style="cursor:hand;' .
		'cursor:pointer;"><u><strong>' . __('Your Bit.ly account - optinal',
		'auto-tweet-plugin') . '</strong></u></p><div id="bit.ly" div style="display:none"><div style="width:400px">';            
            echo "<p><strong><small>".__('Your Bit.ly user name:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"bitly_username\" value='".get_option("auto_twitter_bitly_username")."'></p>"; 
            echo "<p><strong><small>".__('Your Bit.ly API key:','auto-tweet-plugin')."</small></strong><br />";
			echo "<input type=\"text\" name=\"bitly_api_key\" value='".get_option("auto_twitter_bitly_api_key")."'></p>"; 
            echo "</div></div>";                              
			echo "<p><input class=\"button-primary\" type=\"submit\" value=\"".__('Update options','auto-tweet-plugin')."\"></p>";
			echo "<input type=\"hidden\" name=\"submitted\" value=\"submitted\">";
            
		echo "</form>";
	echo "</div>";

}	

function auto_twitter_admin_menu() {
	if (function_exists('add_options_page')) { add_options_page('Auto Tweet plugin Options', 'Auto Tweet plugin', 8, basename(__FILE__), 'auto_twitter_admin'); }
}

$version = '1.0';
$plugin_path = get_option("siteurl") . '/wp-content/plugins/auto-tweet-plugin';

if (!function_exists('trim_add_elipsis')) {
	function trim_add_elipsis($string, $limit = 100) {
		if (strlen($string) > $limit) {
			$string = substr($string, 0, $limit)."...";
		}
		return $string;
	}
}

function auto_twitter_init(){
load_plugin_textdomain( 'auto-tweet-plugin', false, dirname( plugin_basename( __FILE__ ) ) ); 
 if (is_admin()) {
if ( version_compare( phpversion(), '5.0', '<' ) || !function_exists( 'curl_init' ) ) {
	$warning = __('Auto Tweet plugin requires PHP version 5 or above with cURL support. Please upgrade PHP or install cURL to run Auto Tweet plugin.','auto-tweet-plugin' );
	add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );}
        
   if((get_option("auto_twitter_consumerKey") == '') ||
		(get_option("auto_twitter_consumerSecret") == '')||
        (get_option("auto_twitter_accesToken") == '')||
        (get_option("auto_twitter_accesTokenSecret") == '')
        ) 
    add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf(__('Please update your <a href="%s">Auto Tweet plugin settings</a>', 'auto-tweet-plugin'), admin_url('options-general.php?page=auto-tweet-plugin.php'))."</p></div>';" ) ); 
 }    
}

function to_twitter(){
 // error_reporting(E_ALL);
ini_set('display_errors','On');  
  if(empty($_POST['auto_twitter_auto_tweet']) OR ($_POST['auto_twitter_auto_tweet']!='on')) return ;  
  $get_post_info = get_post( $post_ID );
  
  $title=trim($get_post_info->post_title);
  $content=trim($get_post_info->post_excerpt);
  if($content ==""){
   $content=strip_tags(trim($get_post_info->post_content)); 
  }
  $bitly_user_name=get_option("auto_twitter_bitly_username");
  $bitly_api_key=get_option("auto_twitter_bitly_api_key");
  if(empty($bitly_user_name) OR empty($bitly_api_key)){
    $bitly_api_key='R_4d1d63cc9ed8806207ee2bda5cb0cdc0';
    $bitly_user_name='autotwitter';
  }
  $bitly=new bitlyClass($bitly_user_name,$bitly_api_key);
  $shortUrl=$bitly->getShortURL(get_permalink($post_ID ));
  
  $maxContentLength=140- strlen($shortUrl)-strlen($title)-7;
  
  $content=trim_add_elipsis($content,$maxContentLength);
  $content=$title.', '.$content.' '.$shortUrl;
  $consumerKey=get_option("auto_twitter_consumerKey");
  $consumerSecret=get_option("auto_twitter_consumerSecret");
  $accesToken=get_option("auto_twitter_accesToken");
  $accesTokenSecret=get_option("auto_twitter_accesTokenSecret");  
  
  $twitter = new Twitter($consumerKey,$consumerSecret);

  $twitter->setOAuthToken($accesToken);
  $twitter->setOAuthTokenSecret($accesTokenSecret);
  
  $log=$twitter->statusesUpdate($content);
 
   
  /* 

                     $myFile = "zpracovano.log";
                    $fh = fopen($myFile, 'a') or die("can't open file");
                    $stringData = $file_name."\n";
                    fwrite($fh, $log);
                    fclose($fh);  */
}    
    

function auto_twitter_post_options_init() {
	// Unused version check.
	if(version_compare($GLOBALS['wp_version'], '2.7', '>=')) {
		$context = 'side'; $priority = 'low';}
	else {	$context = 'normal'; $priority = 'high';}
	if(function_exists('add_meta_box')) {
			add_meta_box('auto_twitter', __('Auto Tweet plugin',
			'auto-tweet-plugin'), 'auto_twitter_post_options', 'post',
			$context, $priority);
    }        
	else return false;
 }    
  
function auto_twitter_post_options() {
	global $post;
	if(version_compare($GLOBALS['wp_version'], '3.0', '<'))
		echo	'<div id="light_auto_twitter" class="postbox"><h3>' .
			__('Auto Tweet plugin', 'auto-tweet-plugin') .
			'</h3><div class="inside">';
	echo	'<p>';
	if((get_option("auto_twitter_consumerKey") == '') ||
		(get_option("auto_twitter_consumerSecret") == '')||
        (get_option("auto_twitter_accesToken") == '')||
        (get_option("auto_twitter_accesTokenSecret") == '')
        ) {
		echo '<strong>'; printf(__('Please enter your Twitter OAuth ' .
		'keys under <a target="_blank" href="%1$s/wp-admin/options-' .
		'general.php?page=%2$s">Settings &gt; Auto Tweet plugin' .
		'</a>'), get_bloginfo('url'), 'light_auto_twitter'); echo '</strong>';}
	elseif($post->post_status == 'private')
		echo	'<strong>' . __('Cannot tweet a private post.',
			'light_auto_twitter') . '</strong>';
	else {	
	   echo
       '<label for="auto_twitter_auto_tweet" class="selectit">' .
		'<input name="auto_twitter_auto_tweet" ' .
			'id="auto_twitter_auto_tweet" type="checkbox" checked="checked"/>'.__('Auto tweet post','auto-tweet-plugin').'</label></p>';
       }

}    



    
add_action('admin_menu', 'auto_twitter_admin_menu');
add_action( 'publish_post'.$value, 'to_twitter', 10 );	
if(version_compare($GLOBALS['wp_version'], '3.0', '>='))
add_action('add_meta_boxes', 'auto_twitter_post_options_init');
add_action('init', 'auto_twitter_init');
?>