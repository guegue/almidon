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
      $results = "<pre>";
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
      $results = "<pre>";
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
      $results = "<pre>";
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
