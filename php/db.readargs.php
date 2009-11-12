<?php
    $args = explode("/", $_SERVER['PATH_INFO']);
    if (is_numeric($args[1])) {
      $this->id = $args[1];
      $this->action = $args[2];
    } else {
      $this->action = $args[1];
    }
