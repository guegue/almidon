<?php
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin();
    } else {
      $sqlcmd = "SELECT $this->fields FROM $this->name";
    }
    if (isset($this->filter) || isset($filter))
      $sqlcmd .= " WHERE ".((isset($this->filter))?$this->filter." AND ":"")."$filter";
    if ($this->order)
    	$sqlcmd .= " ORDER BY $this->order";
    if ($this->limit)
      $sqlcmd .= " LIMIT $this->limit";
    if ($this->offset)
      $sqlcmd .= " OFFSET $this->offset";

    /* checks cache options */
    if (is_null($cache))
      $cache = (ALM_CACHE && !ADMIN);
    $this->filecache = ROOTDIR.'/cache/'.md5($sqlcmd).".$this->name.".__FUNCTION__.'dat';
    if (!($cache === true && file_exists($this->filecache) && (time()-filemtime($this->filecache)<=ALM_CACHE_TIME)))
      $this->execSql($sqlcmd);
