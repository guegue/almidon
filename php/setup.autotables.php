<?php
    $alm_table = new alm_tableTable();
    $alm_column = new alm_columnTable();
    $table_data = $alm_table->readData();

    if (!isset($output)) $output = '';

    foreach ($table_data as $table_datum) {
      $doublekey = preg_match('/,/',$table_datum['pkey']);
      $table_type = ($doublekey) ? 'TableDoubleKey' : 'Table';
      $output .= "class " . $table_datum['idalm_table'] . "Table extends $table_type {\n";
      $output .= "  function ".$table_datum['idalm_table']."Table() {\n";
      $output .= "    \$this->Table('".$table_datum['idalm_table']."');\n";
      if ($doublekey) {
        list($pkey1,$pkey2) = preg_split('/,/',$table_datum['pkey']);
        $output .= "    \$this->key1 = '".$pkey1."';\n";
        $output .= "    \$this->key2 = '".$pkey2."';\n";
      } else {
        $output .= "    \$this->key = '".$table_datum['pkey']."';\n";
      }
      $hidden = ($table_datum['hidden'] == 't') ? 'true' : 'false';
      if ($hidden === 'true') $output .= "    \$this->hidden = ".$hidden.";\n";
      if (!empty($table_datum['parent'])) $output .= "    \$this->parent ='".$table_datum['parent']."';\n";
      if (!empty($table_datum['child'])) $output .= "    \$this->child ='".$table_datum['child']."';\n";
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
        $output .= "    \$this->addColumn('". $datum['idalm_column'] . "','" . $datum['type'] . "'," . $datum['size'] . "," . $datum['pk'] . "," .$datum['fk'] . ",'" . addslashes($datum['alm_column']) . "'";
        if (!empty($datum['extra'])) {
          #$output .= "," . addslashes($datum['extra']);
          $output .= "," . $datum['extra'];
        }
        $output .= ");\n";
      }
      $output .= "  }\n}\n";
    }
    if ($autosave === true || (isset($_REQUEST['save']) && $_REQUEST['save'] == '1')) {
      if (!is_writable(ROOTDIR.'/classes/tables.class.php')) {
        $saved = false;
      } else {
        $today = date('YmdHis');
        copy(ROOTDIR.'/classes/tables.class.php', ROOTDIR.'/logs/tables.class.'.$today.'.php');
        $fp = fopen(ROOTDIR.'/classes/tables.class.php', 'w');
        fwrite($fp, "<?php\n$output");
        fclose($fp);
        $saved = true;
      }
    }
