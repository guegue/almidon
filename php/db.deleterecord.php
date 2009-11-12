<?php
    if (!$id) $id = $this->request[$this->key];
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
