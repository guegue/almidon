<?php
    $column = array('name'=>$name,'type'=>$type,'size'=>$size,'references'=>$references, 'label'=>$label, 'extra'=>$extra);
    $this->definition[] = $column;
    $this->dd[$name] = $column;
    if ($references)
      $this->join = 1;
    $this->refreshFields();
    $this->cols++;
