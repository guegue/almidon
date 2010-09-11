<?php
    if (!$id1) $id1 = $this->request['old_' . $this->key1];
    if (!$id2) $id2 = $this->request['old_' . $this->key2];
    $values = $this->preUpdateRecord($maxcols, $nofiles);
    $sqlcmd = "UPDATE $this->name SET $values WHERE $this->key1 = '$id1' AND $this->key2 = '$id2'";
    $result = $this->query($sqlcmd);
