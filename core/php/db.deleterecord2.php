<?php
    if (!$id1) $id1 = $this->request[$this->key1];
    if (!$id2) $id2 = $this->request[$this->key2];
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key1 = '$id1' AND $this->key2 = '$id2'";
    $result = $this->query($sqlcmd);
