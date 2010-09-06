<?php
$configfile = dirname(__FILE__).'/config.php';
if (file_exists($configfile)) {
  require_once($configfile);
} else {
  die('No config: ' . $configfile);
}
$almidondir = defined('ALMIDONDIR') ? ALMIDONDIR : $_SERVER['DOCUMENT_ROOT'] . '/../../';
require_once($almidondir . '/cloudfiles/cloudfiles.php');
require_once($almidondir . '/php/almidon.php');
