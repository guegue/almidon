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
      $results = "<?php\n";
      break;
    case 'tpl':
      header('Content-type: text/plain');
      header('Content-Disposition: attachment; filename="'.$filename.'.tpl";');
      $results = "<html><head><title>".$this->title."</title></head><body>\n";
      $results .= '{section name=i loop=$rows}'."\n";
      # prints headers...
      $results .= '{if $smarty.i.first}'."\n";
      foreach($this->dd as $d)
        $results .= $d['label'] . "\t";
      $results .= "<br/>{/if}\n";
      # prints rows...
      foreach($this->dd as $d)
        $results .= '{$rows[i].' . $d['name'] . "}\t";
      $results .= "<br/>\n{/section}\n";
      # prints detail data
      $results .= '{if $row}'."\n";
      foreach($this->dd as $d)
        $results .= $d['label'] . "\t" . '{$row.' . $d['name'] . "}<br/>\n";
      $results .= "{/if}\n</body>\n</html>";
      break;
    case 'tpltable':
      header('Content-type: text/plain');
      header('Content-Disposition: attachment; filename="'.$filename.'.tpl";');
      $results = "<html><head><title>".$this->title."</title></head><body>\n";
      $results .= '{section name=i loop=$rows}'."\n";
      # prints headers...
      $results .= '{if $smarty.i.first}'."\n".'<table border="1"><tr>'."\n";
      foreach($this->dd as $d)
        $results .= '<td>'.$d['label'].'</td>';
      $results .= "</tr>\n{/if}\n";
      # prints rows...
      $results .= '<tr>';
      foreach($this->dd as $d)
        $results .= "<td>".'{$rows[i].' . $d['name'] .'}</td>';
      $results .= "</tr>\n{/section}\n</table>\n";
      # prints detail data
      $results .= '{if $row}'."\n";
      $results .= '<table border="1">'."\n";
      foreach($this->dd as $d)
        $results .= '<tr><td>'.$d['label'].'</td><td>{$row.' . $d['name'] . "}</td></tr>\n";
      $results .= "</table>\n{/if}\n</body>\n</html>";
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
