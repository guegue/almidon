<?php
    if ( !empty($this->key) && trim($name)==trim($this->key) && !$pk ) $pk = 1;
    $column = array('name'=>$name,'type'=>$type,'size'=>$size,'pk'=>$pk,'references'=>$references, 'label'=>$label, 'extra'=>$extra);
    $this->definition[] = $column;
    $this->dd[$name] = $column;
    if ($references)
      $this->join = 1;
    $this->refreshFields();
    $this->cols++;
    if ($column['pk']) $this->keys[] = $column['name'];
