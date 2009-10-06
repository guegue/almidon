<?php
class almuserTable extends Table {
  function almuserTable() {
    $this->Table('almuser');
    $this->key = 'idalmuser';
    $this->title = 'Usuarios';
    $this->maxrows = 20;
    $this->order = 'idalmuser';
    $this->addColumn('idalmuser','serial',0,1,0,'Id');
    $this->addColumn('almuser','varchar',100,0,0,'Usuario');
    $this->addColumn('password','password',200,0,0,'Clave');
    $this->addColumn('name','varchar',100,0,0,'Nombre');
    $this->addColumn('email','varchar',200,0,0,'Correo');
  }
}

class almroleTable extends Table {
  function almroleTable() {
    $this->Table('almrole');
    $this->key = 'idalmrole';
    $this->title = 'Roles';
    $this->maxrows = 20;
    $this->order = 'idalmrole';
    $this->addColumn('idalmrole','serial',0,1,0,'Id');
    $this->addColumn('almrole','varchar',100,0,0,'Rol');
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
    $this->addColumn('idalmrole','int',0,0,'almrole','Rol');
    $this->addColumn('idalmuser','int',0,0,'almuser','Usuario');
    $this->addColumn('idalmform','int',0,0,'almform','Tabla');
  }
}

class almformTable extends Table {
  function almformTable() {
    $this->Table('almform');
    $this->key = 'idalmform';
    $this->title = 'Formularios';
    $this->maxrows = 20;
    $this->order = 'idalmform';
    $this->addColumn('idalmform','serial',0,1,0,'Id');
    $this->addColumn('almform','varchar',100,0,0,'Formulario');
  }
}
