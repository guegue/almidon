<?php
    for ($i = 0; $i < $this->num; $i++) {
      $row = almdata::fetchRow($this->data);
      if (isset($row[$this->key]))
      if ($row[$this->key] == $this->current_id)
        $this->current_record = $row;
      if ($this->html)
        foreach ($row as $key => $val)
          $row[$key] = htmlentities($val, ENT_COMPAT, 'UTF-8');
      $array_rows[] = $row;
    }
