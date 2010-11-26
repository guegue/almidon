<?php
    global $global_dd;
    $n = 0;
    $ns = 0;
    $this->fields_noserial = '';
    $this->all_fields = '';
    $this->fields = '';
    $this->table_fields = '';
    foreach($this->definition as $column) {
      if ($n > 0) {
        $this->fields .= ",";
        $this->all_fields .= ",";
      }
      if ($ns > 0 && !in_array($column['type'],array('external','serial','order')) && !($column['type']=='auto'&&empty($column['extra']['default'])) )
        $this->fields_noserial .= ",";
      if (in_array($column['type'],array('external','order','serial')) && ($column['type']=='auto'&&empty($column['extra']['default'])))
        $ns--;
      else 
        $this->fields_noserial .= $column['name'];
      $this->fields .= $column['name'];
      if ($column['type'] == 'external')
        $this->all_fields .= $column['name'];
      else {
        $this->all_fields .= $this->name . "." . $column['name'];
        if ( !empty($this->table_fields) ) $this->table_fields .= ",";
        $this->table_fields .= $this->name . '.' . $column['name'];
      }
      if ($column['references'] && isset($global_dd[$column['references']]['descriptor'])) {
        if (!isset($references[$column['references']]))
          $references[$column['references']] = 0;
        if ($column['references'] == $this->name && !$references[$column['references']])
          $references[$column['references']]+=2;
        else
          $references[$column['references']]++;
        if ($references[$column['references']] == 1) {
          if (!empty($column['extra']['display'])) {
            $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['references'];
            # FIXME: Is 'alias' useless?
            #if(empty($column['extra']['alias']))  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['references'];
            #else  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['extra']['alias'];
          } else {
            # FIXME? Y si existe ya un campo llamado como la tabla foranea en la tabla actual?
            $this->all_fields .= "," . $column['references'] . "." . $global_dd[$column['references']]['descriptor'] . " AS " . $column['references'];
            #$this->all_fields .= "," . $column['references'] . "." . $global_dd[$column['references']]['descriptor'];
          }
        } else {
          # FIXME: Second reference to same table does not enjoy display/alias (not yet)
          $tmptable = $column['references'] . $references[$column['references']];
          $tmpcolumn =  $global_dd[$column['references']]['descriptor'];
          $this->all_fields .= "," . $tmptable . "." . $tmpcolumn . " AS " . $tmptable;
        }
      }
      $n++;
      $ns++;
    }
