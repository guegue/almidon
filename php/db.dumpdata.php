<?php
    print "<table border=1>";
    $rows = $this->readData();
    if ($rows)
      foreach($rows as $row) {
        print "<tr>";
        foreach($row as $column)
          print "<td>$column</td>";
        print "</tr>";
      }
    print "</table>";
