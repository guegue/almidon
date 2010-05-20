<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');

# Check credentials once again (just in case rewrite is off)
if ($_SESSION['idalm_role'] !== 'full' && $_SESSION['idalm_user'] !== 'admin' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
  error_log("No right permissions");
  exit;
}

if (isset($_REQUEST['session']) && isset($_SESSION[$_REQUEST['session']])) {
  $rows = $_SESSION[$_REQUEST['session']];
} else {
  $object_name = $_REQUEST['table'] . 'Table';
  $object = new $object_name;
  $rows = $object->readData();
}
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action === 'import' && !empty($_FILES)) {
  $filename = $_FILES['ifile']['tmp_name'];
  $row = 1;
  if (($handle = fopen($filename, 'r')) !== FALSE) {
    $fields = fgetcsv($handle, 1000, ',');
    echo "Import to table: $object->name <br/>";
    echo "Fields to import: ";
    foreach($fields as $k=>$f) {
      $fields[$k] = strtolower($fields[$k]);
      if (!isset($object->dd[$fields[$k]])) {
        echo 'Wrong format, "' . $field_name . '" doesnot exist in "'.$object->name.'"';
        exit;
      }
      echo "$f, ";
    }
    echo "<br/>\n";
    $num = count($fields);
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      #$num = count($data);
      $row++;
      for ($c=0; $c < $num; $c++) {
        $field_name = $fields[$c];
        $object->request[$field_name] = $data[$c];
        echo $fields[$c] . ' = ' . $data[$c] . ', ';
      }
      $object->addRecord();
      echo "<br />\n";
    }
    fclose($handle);
  }
}
if ($action === 'export') {
  if (!empty($rows) && isset($_REQUEST['format'])) {
    if (!empty($_REQUEST['session'])) {
      table::dumpData($_REQUEST['format'], $rows);
    } else {
      $object->dumpData($_REQUEST['format']);
    }
  }
}
if (!isset($_REQUEST['format'])) {
$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
$session = isset($_REQUEST['session']) ? $_REQUEST['session'] : '';
if ($action === 'import' && empty($session)) {
?>
  <form method="POST" enctype="multipart/form-data">
  <input name="table" type="hidden" value="<?=$table?>"/>
  <input name="action" type="hidden" value="<?=$action?>"/>
  Choose CSV file: <input type="file" name="ifile" />
  <input type="submit" value="Submit"/>
  </form>
  <small>Note: First row must contain headers</small>
<?php
} else {
?>
  <form method="GET">
  <input name="table" type="hidden" value="<?=$table?>"/>
  <input name="session" type="hidden" value="<?=$session?>"/>
  <input name="action" type="hidden" value="<?=$action?>"/>
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
}
