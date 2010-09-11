<?php
    if ($cache === true && file_exists($this->filecache) && (time()-filemtime($this->filecache)<=ALM_CACHE_TIME))
      $array_rows = unserialize(file_get_contents($this->filecache));
    else
    for ($i = 0; $i < $this->num; $i++) {
      $row = almdata::fetchRow($this->data);
      if (isset($row[$this->key]))
      if ($row[$this->key] == $this->current_id)
        $this->current_record = $row;
      if ($this->html)
        foreach ($row as $key => $val)
          $row[$key] = htmlentities($val, ENT_COMPAT, 'UTF-8');
      $array_rows[] = $row;
      if (isset($array_rows) && ($cache === true))
        file_put_contents($this->filecache, serialize($array_rows));
    }
