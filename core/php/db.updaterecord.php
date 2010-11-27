<?php
    if (!$id && isset($this->request['old_' . $this->key])) $id = $this->request['old_' . $this->key];
    if (!$id) $id = $this->request[$this->key];
    $values = $this->preUpdateRecord($maxcols, $nofiles);
    $sqlcmd = "UPDATE $this->name SET $values WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
