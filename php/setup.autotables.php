<?php
    $alm_table = new alm_tableTable();
    $alm_column = new alm_columnTable();
    $table_data = $alm_table->readData();

    if (!isset($output)) $output = '';

    foreach ($table_data as $table_datum) {
      $output .= "class " . $table_datum['idalm_table'] . "Table extends Table {\n";
      $output .= "  function ".$table_datum['idalm_table']."Table() {\n";
      $output .= "    \$this->Table('".$table_datum['idalm_table']."');\n";
      $output .= "    \$this->key = '".$table_datum['pkey']."';\n";
      $output .= "    \$this->title ='".$table_datum['alm_table']."';\n";
      if (!empty($table_datum['orden']))
        $output .= "    \$this->order ='".$table_datum['orden']."';\n";
      $data = $alm_column->readDataFilter("alm_column.idalm_table='".$table_datum['idalm_table']."'");
      if ($data)
      foreach ($data as $datum) {
        if ($datum['pk'] == 't') $datum['pk'] = 1;
        if ($datum['pk'] == 'f') $datum['pk'] = 0;
        if (empty($datum['fk'])) $datum['fk'] = 0;
        else $datum['fk'] = "'".$datum['fk']."'";
        $output .= "    \$this->addColumn('". $datum['idalm_column'] . "','" . $datum['type'] . "'," . $datum['size'] . "," . $datum['pk'] . "," .$datum['fk'] . ",'" . $datum['alm_column'] . "','" . $datum['extra']  . "');\n";
      }
      $output .= "  }\n}\n";
    }
    if ($autosave === true || (isset($_REQUEST['save']) && $_REQUEST['save'] == '1')) {
      if (!is_writable(ROOTDIR.'/classes/tables.class.php')) {
        $saved = false;
      } else {
        $fp = fopen(ROOTDIR.'/classes/tables.class.php', 'w');
        fwrite($fp, "<?php\n$output");
        fclose($fp);
        $saved = true;
      }
    }
