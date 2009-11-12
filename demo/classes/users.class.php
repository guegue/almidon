<?php
class almuserTable extends Table {
  function almuserTable() {
    $this->Table('almuser');
    $this->key = 'idalmuser';
    $this->title = 'Usuarios';
    $this->maxrows = 20;
    $this->order = 'idalmuser';
    $this->addColumn('idalmuser','varchar',16,1,0,'Id');
    $this->addColumn('password','password',200,0,0,'Clave');
    $this->addColumn('almuser','varchar',100,0,0,'Nombre');
    $this->addColumn('email','varchar',200,0,0,'Correo');
    $this->addColumn('idalmrole','varchar',8,0,'almrole','Rol por defecto');
  }
}

class almroleTable extends Table {
  function almroleTable() {
    $this->Table('almrole');
    $this->key = 'idalmrole';
    $this->title = 'Roles';
    $this->maxrows = 20;
    $this->order = 'idalmrole';
    $this->addColumn('idalmrole','varchar',8,1,0,'Id');
    $this->addColumn('almrole','varchar',100,0,0,'Descripcion');
  }
}

class almaccessTable extends Table {
  function almaccessTable() {
    $this->Table('almaccess');
    $this->key = 'idalmaccess';
    $this->title = 'Acceso';
    $this->maxrows = 20;
    $this->order = 'idalmaccess';
    $this->addColumn('idalmaccess','serial',0,1,0,'Id');
    $this->addColumn('idalmrole','varchar',8,0,'almrole','Rol');
    $this->addColumn('idalmuser','varchar',16,0,'almuser','Usuario');
    $this->addColumn('idalmtable','varchar',32,0,'almtable','Tabla');
  }
}

class almtableTable extends Table {
  function almtableTable() {
    $this->Table('almtable');
    $this->key = 'idalmtable';
    $this->title = 'Tablas';
    $this->maxrows = 20;
    $this->order = 'idalmtable';
    $this->addColumn('idalmtable','varchar',32,1,0,'Id');
    $this->addColumn('almtable','varchar',100,0,0,'Descripcion');
    $this->addColumn('key','varchar',32,0,0,'Primary Key');
    $this->addColumn('orden','varchar',100,0,0,'Orden');
  }
}

class almcolumnTable extends TableDoubleKey {
  function almcolumnTable() {
    $this->Table('almcolumn');
    $this->key1 = 'idalmcolumn';
    $this->key2 = 'idalmtable';
    $this->title = 'Campos';
    $this->maxrows = 20;
    $this->order = 'idalmtable,idalmcolumn';
    $this->addColumn('idalmcolumn','varchar',32,1,0,'Id');
    $this->addColumn('idalmtable','varchar',32,0,'almtable','Table');
    $this->addColumn('type','varchar',16,0,0,'Type');
    $this->addColumn('size','int',0,0,0,'Size');
    $this->addColumn('pk','bool',0,0,0,'Primary Key?');
    $this->addColumn('fk','varchar',16,0,0,'Foreign Key Table');
    $this->addColumn('almcolumn','varchar',100,0,0,'Description');
    $this->addColumn('extra','varchar',500,0,0,'Extras');
  }
}
