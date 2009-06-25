<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_radios} function plugin
 *
 * File:       function.html_radios.php<br>
 * Type:       function<br>
 * Name:       html_radios<br>
 * Date:       24.Feb.2003<br>
 * Purpose:    Prints out a list of radio input types<br>
 * Input:<br>
 *           - name       (optional) - string default "radio"
 *           - values     (required) - array
 *           - options    (optional) - associative array
 *           - checked    (optional) - array default not set
 *           - separator  (optional) - ie <br> or &nbsp;
 *           - output     (optional) - the output next to each radio button
 *           - assign     (optional) - assign the output as an array to this variable
 * Examples:
 * <pre>
 * {html_radios values=$ids output=$names}
 * {html_radios values=$ids name='box' separator='<br>' output=$names}
 * {html_radios values=$ids checked=$checked separator='<br>' output=$names}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.html.radios.php {html_radios}
 *      (Smarty online manual)
 * @author     Christopher Kvarme <christopher.kvarme@flashjab.com>
 * @author credits to Monte Ohrt <monte at ohrt dot com>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_include_video_service($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','video_service');
   
    $width = ($params['width'])?$params['width']:425;
    $height = ($params['height'])?$params['height']:350;
    $lang = ($params['lang'])?$params['lang']:'es';
    
    if(!(isset($params['src'])||isset($params['xmlsrc']))) {
        $smarty->trigger_error("include_video_service: missing 'src' or 'xmlsrc' parameter");
        return;
    }

    $urlsrc = $params['src'];
    if(!$urlsrc) {
      //      XML
      require_once 'XML/Unserializer.php';
      $options = array(
         XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => true,
         XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false
      );
      $unserializer = &new XML_Unserializer($options);
      $status = $unserializer->unserialize($params['xmlsrc'], false);
      if (PEAR::isError($status)){
        echo 'Error: ' . $status->getMessage();
      }else{
        $vs = $unserializer->getUnserializedData();
      }
      $urlsrc = $vs['src'];
      $type = $vs['tipo'];
    }else {
      if(!isset($params['type'])) {
        $smarty->trigger_error("include_video_service: missing 'type' parameter");
        return;
      }
      
      $type = $params['type'];
    }

    $service = smarty_function_get_video_service($type);
    $arr = parse_url($urlsrc);
    parse_str($arr['query'],$vars);
    $str_output = $service['html'];
    $str_output = str_replace ('[width]',$width,$str_output);
    $str_output = str_replace ('[height]',$height,$str_output);
    $str_output = str_replace ('[src]',$vars[$service['srcfield']],$str_output);
    $str_output = str_replace ('[lang]',$lang,$str_output);
    return $str_output;
}

?>
