<?php
# Check credentials once again (just in case rewrite is off)
if ($_SESSION['idalm_idrole'] !== 'full' && $_SESSION['idalm_user'] !== 'admin' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
  error_log("No right permissions");
  exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
if (isset($_REQUEST['session'])) {
  $rows = $_SESSION[$_REQUEST['session']];
} else {
  $object_name = $_REQUEST['table'] . 'Table';
  $object = new $object_name;
  $rows = $object->readData();
}
if (!empty($rows) && isset($_REQUEST['format'])) {
  if ($_REQUEST['action'] === 'export') {
    if (isset($_REQUEST['session']))
      table::dumpData($_REQUEST['format'], $rows);
    else
      $object->dumpData($_REQUEST['format']);
  }
}
if (!isset($_REQUEST['format'])) {
?>
  <form method="GET">
  <input name="table" type="hidden" value="<?=$_REQUEST['table']?>"/>
  <input name="session" type="hidden" value="<?=$_REQUEST['session']?>"/>
  <input name="action" type="hidden" value="<?=$_REQUEST['action']?>"/>
  Choose format: <select name="format">
  <option value="php">PHP Dump</option>
  <option value="html">HTML</option>
  <option value="txt">Text</option>
  <option value="ftxt">Text (Formatted)</option>
  <option value="csv">CSV (Calc, Excel)</option>
  <option value="sql">SQL</option>
  </select>
  <input type="submit" value="Submit"/>
  </form>
<?php
}
