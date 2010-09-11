<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * video_service common function
 *
 * Function: smarty_function_video_services<br>
 * Purpose:  used by other smarty functions to return
 *           an array of video's service
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param void
 * @return array
 */

function smarty_function_get_video_service($key=null) {
    $arr_service = array('youtube' => array('label' => 'YouTube','srcfield' => 'v','html' => '<object width="[width]" height="[height]"><param name="movie" value="http://www.youtube.com/v/[src]"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/[src]" type="application/x-shockwave-flash" wmode="transparent" width="[width]" height="[height]"></embed></object>'),
		     'gvideo' => array('label' => 'Google Video', 'srcfield' => 'docid','html' => '<embed style="width:[width]px; height:[height]px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=[src]&hl=[lang]" flashvars=""> </embed>'),
		     'brightcove' => array('label' => 'Brightcove', 'srcfield' => 'title','html' => '<embed src=\'http://admin.brightcove.com/destination/player/player.swf\' bgcolor=\'#FFFFFF\' flashVars=\'allowFullScreen=true&initVideoId=[src]&servicesURL=http://www.brightcove.com&viewerSecureGatewayURL=https://www.brightcove.com&cdnURL=http://admin.brightcove.com&autoStart=false\' base=\'http://admin.brightcove.com\' name=\'bcPlayer\' width=\'[width]\' height=\'[height]\' allowFullScreen=\'true\' allowScriptAccess=\'always\' seamlesstabbing=\'false\' type=\'application/x-shockwave-flash\' swLiveConnect=\'true\' pluginspage=\'http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\'></embed>')
		     //'vimeo' => array('label' => 'Vimeo')
		    );
    if(!$key)
      return $arr_service;
    else return $arr_service[$key];
}

function smarty_function_video_service()
{
    $arr_service = smarty_function_get_video_service();
    $list_service = array();
    foreach($arr_service as $key => $value)
      $list_service[$key] = $arr_service[$key]['label'];
    return $list_service;
}

/*
Devuelve una cadena con el codigo html para incluir el video, en la cedana van los siguientes valores que deben ser reemplazados

[width]: ancho
[height]: largo
[src] = id del video en algunos casos
*/
function smarty_function_video_service_string ($service) {
    $string_service = smarty_function_get_video_service();
    return $string_service[$service]['html'];
}

/* vim: set expandtab: */

?>
