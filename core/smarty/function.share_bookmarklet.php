<?php
/**
 * Smarty {share_bookmarklet} function plugin
 *
 * File:   function.share_bookmarklet.php<br>
 * Type:   function<br>
 * Name:   share_bookmarklet<br>
 * Params: [ css (bool) | outside (bool) | domain (varchar) | site (varchar) | url (varchar) ]
 * Date:   18.jul.2009<br>
 * Author: Christian Torres <chtorrez at gmail dot com>
 */

function smarty_function_share_bookmarklet($params, &$smarty) {
  require_once $smarty->_get_plugin_filepath('shared','share_bookmarklet');

  $str = '';
  if($params['css']!==false) {
    $str .= '<style type="text/css">';
    $str .= '.almidon_share_bookmarklet { list-style:none; margin:0; padding:0; }';
    $str .= '.almidon_share_bookmarklet li { float:left; margin:2px; }';
    $str .= '</style>';
  }

  # What's the domain's name?
  if(empty($params['domain'])) {
    $domain = $_SERVER["SERVER_NAME"];
  } else $domain = $params['domain']; 
  # Which site am I?
  if(empty($params['site'])) {
    $siteURL = 'http';
    if ($_SERVER["HTTPS"] == "on")  $siteURL .= "s";
    $siteURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") $siteURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
    else  {
      $siteURL .= $_SERVER["SERVER_NAME"];
    }
  } else $siteURL = $params['site'];
  # Where am I? (page)
  if(empty($params['url'])) {
    $pageURL = $siteURL . $_SERVER["REQUEST_URI"];
  } else $pageURL = $params['url'];

  $str .= "<ul class=\"almidon_share_bookmarklet\">";
  $networks = smarty_function_social_network_codes();
  $i = 0; $total = count($networks);
  foreach($networks as $key => $network) {
    if ( $params[$key] === false ) { $total--; continue; }
    $i++;
    $url = str_replace('[site]',rawurlencode($domain),$network['url']);
    $url = str_replace('[siteurl]',rawurlencode($siteURL),$url);
    $url = str_replace('[title]',rawurlencode($params['title']),$url);
    $url = str_replace('[desc]',rawurlencode($params['desc']),$url);
    $url = str_replace('[url]',rawurlencode($pageURL),$url);
    $str .= '<li' . (($i==1&&$i<$total)?" class=\"first\"":(($i==$total&&$total!=1)?" class=\"last\"":"")) . '><a href="' . $url . '"'.($params['outside']!==false?" target=\"_blank\"":"").(!empty($network['js_events'])?" ".$network['js_events']:"").'><img src="' . (empty($params['path'])?"/" . ALM_URI . "/themes/" . ALM_ADMIN_THEME . "/img/networks/":$params['path']) . $network['image'] . '" alt="' . htmlentities($network['label'],ENT_COMPAT,"UTF-8") . '" title="' . htmlentities($network['label'],ENT_COMPAT,"UTF-8") . '" border="0" /></a></li>';
  }
  $str .= "</ul>";
  return $str;
}
?>
