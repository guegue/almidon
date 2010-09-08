<?php
    if (is_array($session)) {
      $rows = $session;
      $filename = $_REQUEST['session'];
    } else {
      $rows = $this->readData();
      $filename = $this->name;
    }
    if (empty($rows)) return false;
    $hs = $rows[0];
    $results = '';
    foreach($hs as $k=>$v)
      $headers[] = $k;
    switch($format) {
    case 'php':
      $results = "<pre>\n" . print_r($rows, 1) . "\n</pre>";
      break;
    case 'html':
      $results = "<table border=1>\n";
      foreach($headers as $h)
        $results .= "<th>$h</th>";
      foreach($rows as $row) {
        $results .= "<tr>";
        foreach($row as $column)
          $results .= "<td>$column</td>";
        $results .= "</tr>";
      }
      $results .= "</table>";
      break;
    case 'ftxt':
      # FIXME: Use printf !!!
      $results = "<pre>\n";
      foreach($headers as $h)
        $results .=  $h . "\t";
      $results .= "\n";
      foreach($rows as $row) {
        foreach($row as $column)
          $results .= "$column\t";
        $results .= "\n";
      }
      $results .= "</pre>";
      break;
    case 'txt':
      $results = "<pre>\n";
      foreach($headers as $h)
        $results .=  $h . "\t";
      $results .= "\n";
      foreach($rows as $row) {
        foreach($row as $column)
          $results .= "$column\t";
        $results .= "\n";
      }
      $results .= "</pre>";
      break;
    case 'sql':
      $results = "<pre>\n";
      $fields = implode($headers, ',');
      foreach($rows as $row) {
        $results .= "INSERT INTO $this->name ($fields) VALUES (";
        foreach($row as $column)
          $cols[] = "'$column'";
        $results .= implode($cols, ",");
        unset($cols);
        $results .= ");\n";
      }
      $results .= "</pre>";
      break;
    case 'sqlschema':
      require_once('setup.gensql.php');
      $results = "<pre>\n";
      $results .= genSql($this->name);
      $results .= "</pre>";
      break;
    case 'alm':
      header('Content-type: text/plain');
      header('Content-Disposition: attachment; filename="'.$filename.'.php";');
      $results = "<?php\nrequire('../classes/app.class.php');\n";
      $results .= '$data = new '.$this->name.'Table();'."\n";
      $results .= '$data->readEnv();'."\n";
      $results .= 'if (isset($data->request[$data->key])) {'."\n";
      $results .= '  $row = $data->readRecord();'."\n";
      $results .= '  $smarty->assign(\'row\',$row);'."\n";
      if (isset($this->child)) {
        $results .= '  if (isset($data->child)) {'."\n";
        $results .= '    $children = preg_split(\'/,/\',$data->child);'."\n";
        $results .= '    foreach ($children as $child) {'."\n";
        $results .= '      $namec = $child . \'Table\';'."\n";
        $results .= '      $datac = new $namec;'."\n";
        $results .= '      $datac->filter = $datac->name . \'.\' . $data->key . \'=\' . $row[$data->key];'."\n";
        $results .= '      $$child = $datac->readData();'."\n";
        $results .= '      $smarty->assign($child,$$child);'."\n";
        $results .= '    }'."\n";
        $results .= '  }'."\n";
      }
      $results .= '} else {'."\n";
      $results .= '  $rows = $data->readData();'."\n";
      $results .= '  $smarty->assign(\'rows\',$rows);'."\n";
      $results .= '}'."\n";
      $results .= '$smarty->display($data->name.\'.tpl\');'."\n";
      break;
    case 'tpl':
    case 'tpltable':
      global $global_dd;
      if ($format === 'tpltable') {
        $datini = '<table border="1">';
        $datfin = '</table>';
        $rowini = '<tr>';
        $rowfin = '</tr>';
        $colini = '<td>';
        $colfin = '</td>';
      } else {
        $datini = '';
        $datfin = '';
        $rowini = '';
        $rowfin = "<br/>";
        $colini = '';
        $colfin = "\t";
      }
      header('Content-type: text/plain');
      header('Content-Disposition: attachment; filename="'.$filename.'.tpl";');
      $results = "<html><head><title>".$this->title."</title></head><body><h1>".$this->title."</h1>\n";
      $results .= $datini.'{section name=i loop=$rows}'."\n";
      # prints headers...
      $results .= '{if $smarty.section.i.first}'."$rowini\n";
      $cols = 0;
      foreach($this->dd as $d) {
        $results .= $colini.$d['label'].$colfin;
        $cols++;
        if ($cols>5) break;
      }
      $results .= "$rowfin\n{/if}\n";
      # prints rows...
      $results .= $rowini;
      $cols = 0;
      foreach($this->dd as $d) {
        if ($d['name'] === $this->key)
          $results .= $colini.'<a href="?'.$d['name'].'={$rows[i].'.$d['name'].'}">{$rows[i].' . $d['name'] .'}</a>'.$colfin;
        else {
          switch($d['type']) {
          case 'image':
            $results .= $colini.'<img src="/cms/pic/100/'.$this->name.'/{$rows[i].' . $d['name'] .'}"/>'.$colfin;
            break;
          default:
            $results .= $colini.'{$rows[i].' . $d['name'] .'}'.$colfin;
          }
        }
        $cols++;
        if ($cols>5) break;
      }
      $results .= "$rowfin\n{/section}\n$datfin\n";
      # prints detail data
      $results .= '{if $row}'."\n";
      $results .= "$datini\n";
      foreach($this->dd as $d) {
        $field = ($d['references'] !== 0) ? $d['references'] : $d['name'];
        $results .= $rowini.$colini.$d['label'].$colfin.$colini.'{$row.' . $field . "}$colfin$rowfin\n";
      }
      $results .= "$datfin\n";
      # prints children's tables
      if (isset($this->child)) {
        $children = preg_split('/,/',$this->child);
        foreach ($children as $child) {
          $namec = $child . 'Table';
          $datac = new $namec;
          $results .= '<h2>'.$datac->title.'</h2>';
          $results .= $datini.'{section name='.$datac->name.' loop=$'.$datac->name.'}'."\n$rowini";
          foreach($datac->dd as $dc) {
            if ($dc['name'] == $this->key) continue;
            $field = ($dc['references'] !== 0) ? $dc['references'] : $dc['name'];
            $results .= $colini.'{$'.$datac->name.'['.$datac->name.'].' . $field .'}'.$colfin."\n";
          }
          $results .= "$rowfin\n{/section}$datfin\n";
        }
      }
      $results .= "{/if}\n</body>\n</html>";
      break;
    case 'csv':
      header('Content-type: text/csv');
      header('Content-Disposition: attachment; filename="'.$filename.'.csv";'); 
      foreach($headers as $h)
        $cols[] = "\"$h\"";
      $results .= implode($cols, ",") . "\n";
      unset($cols);
      foreach($rows as $row) {
        foreach($row as $column)
          $cols[] = strip_tags("\"$column\"");
        $results .= implode($cols, ",");
        unset($cols);
        $results .= "\n";
      }
      break;
    }
?>
<?=$results?>
