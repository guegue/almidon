<?php
    $column = array('name'=>$name,'type'=>$type,'size'=>$size,'references'=>$references, 'label'=>$label, 'extra'=>$extra);
    $this->definition[] = $column;
    $this->dd[$name] = $column;
    if ($references)
      $this->join = 1;
    $this->refreshFields();
    $this->cols++;
    # FIXME: en realidad no deberia de necesitarse esto, keys deberia declararse desde la defincion de la Table
    unset($this->keys);
    if (isset($this->key)) $this->keys[] = $this->key;
    if (isset($this->key1)) $this->keys[] = $this->key1;
    if (isset($this->key2)) $this->keys[] = $this->key2;
