<?php
    $n = 0;
    $values ="";
    foreach($this->definition as $column) {
      if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial')
        $values .= ",";
      switch($column['type']) {
        case 'auto':
      	case 'external':
        case 'serial':
        case 'order':
          $n--;
          break;
        case 'int':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1)
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $this->request[$column['name']];
          break;
        case 'image':
          $value = '';
          if (isset($this->files[$column['name']])) {
            if (isset($column['extra']['cdn']) && $column['extra']['cdn'] === true)
              $cloudfiles = almdata::cdn_connect();
            $timemark = mktime();
            $filename =  $timemark . "_" . $this->request[$column['name']];
            $value = almdata::escape($filename);
            if (isset($column['extra']['cdn']) && $column['extra']['cdn'] === true) {
              almdata::cdn_upload($cloudfiles, $filename, $this->files[$column['name']]);
            } else {
              if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
              move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            }
            if (isset($column['extra']['sizes']) && defined('PIXDIR'))  $sizes = explode(',',$column['extra']['sizes']);
            if(isset($sizes)) {
              foreach($sizes as $size) {
                if (isset($column['extra']['cdn']) && $column['extra']['cdn'] === true) {
                  # FIXME: get original image from CDN repo
                  #$image = imagecreatefromstring(file_get_contents(CDN_URL.'/'.$filename));
                } else {
                  $image = imagecreatefromstring(file_get_contents(ROOTDIR.'/files/'.$this->name.'/'.$filename));
                }
                list($ancho,$alto) = preg_split('/x/', $size);
                $alto_original = imagesy($image);
                $ancho_original = imagesx($image);
                if (!$alto) $alto = ceil($alto_original*($ancho/$ancho_original));
                $new_image = imagecreatetruecolor ($ancho, $alto);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
                # this code puts the year and month
                $filename = $ancho.($alto?"x$alto":"").'_'.$filename;
                $filepath = PIXDIR.'/'.date("Y",$timemark).'/'.date("m",$timemark).'/';
                if(file_exists($filepath) || mkdir($filepath, null, true))
                  imagejpeg($new_image, $filepath.'/'.$filename,72);
                if (isset($column['extra']['cdn']) && $column['extra']['cdn'] === true) {
                   almdata::cdn_upload($cloudfiles, $filename, $filepath.'/'.$filename);
                   unlink($filepath.'/'.$filename);
                }
              }
            }
          }
          $values .= "'" . $value . "'";
          break;
        case 'file':
          if ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            $filepath = ROOTDIR . '/files/' . $this->name;
            if (isset($column['extra']['cdn']) && $column['extra']['cdn'] === true) {
              $cloudfiles = almdata::cdn_connect();
              almdata::cdn_upload($cloudfiles, $filename, $this->files[$column['name']]);
            } else {
              if (!file_exists($filepath)) mkdir($filepath);
              move_uploaded_file($this->files[$column['name']], $filepath.'/'.$filename);
            }
            $this->request[$column['name']] = $filename;
          }
        case 'char':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1) {
            $values .= 'NULL';
          } else {
            $value = almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'varchar':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1) {
            $values .= 'NULL';
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'text':
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
          } 
          break;
        case 'bool':
        case 'boolean':
          $value = (isset($this->request[$column['name']])) ? $this->request[$column['name']] : false;
          $value = (!$value || $value == 'false' || $value == '0' || $value == 'f') ? '0' : '1';
          $values .= "'" . $value . "'";
          break;
        case 'date':
        case 'datetime':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != '0-00-0' && !empty($value)) {
            $value = almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
          }
          break;
        default:
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
          }
          break;
      }
      $n++;
    }
    $sqlcmd = "INSERT INTO $this->name ($this->fields_noserial) VALUES ($values)";
    $result = $this->query($sqlcmd);
