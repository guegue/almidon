<?php
class alm_userTable extends Table {
  function alm_userTable() {
    $this->Table('alm_user');
    $this->key = 'idalm_user';
    $this->title = 'Usuarios';
    $this->maxrows = 20;
    $this->order = 'idalm_user';
    $this->addColumn('idalm_user','varchar',16,1,0,'Id');
    $this->addColumn('password','password',200,0,0,'Clave');
    $this->addColumn('alm_user','varchar',100,0,0,'Nombre');
    $this->addColumn('email','varchar',200,0,0,'Correo');
    $this->addColumn('idalm_role','varchar',8,0,'alm_role','Rol por defecto');
  }
}

class alm_roleTable extends Table {
  function alm_roleTable() {
    $this->Table('alm_role');
    $this->key = 'idalm_role';
    $this->title = 'Roles';
    $this->maxrows = 20;
    $this->order = 'idalm_role';
    $this->addColumn('idalm_role','varchar',8,1,0,'Id');
    $this->addColumn('alm_role','varchar',100,0,0,'Descripcion');
  }
}

class alm_accessTable extends Table {
  function alm_accessTable() {
    $this->Table('alm_access');
    $this->key = 'idalm_access';
    $this->title = 'Acceso';
    $this->maxrows = 20;
    $this->order = 'idalm_access';
    $this->addColumn('idalm_access','serial',0,1,0,'Id');
    $this->addColumn('idalm_role','varchar',8,0,'alm_role','Rol');
    $this->addColumn('idalm_user','varchar',16,0,'alm_user','Usuario');
    $this->addColumn('idalm_table','varchar',32,0,'alm_table','Tabla');
  }
}

class alm_tableTable extends Table {
  function alm_tableTable() {
    $this->Table('alm_table');
    $this->key = 'idalm_table';
    $this->title = 'Tablas';
    $this->maxrows = 20;
    $this->order = 'rank';
    $this->addColumn('idalm_table','varchar',32,1,0,'Id');
    $this->addColumn('alm_table','varchar',100,0,0,'Descripcion');
    $this->addColumn('pkey','varchar',32,0,0,'Primary Key');
    $this->addColumn('orden','varchar',100,0,0,'Order By');
    $this->addColumn('rank','int',0,0,0,'Orden');
  }
}

class alm_columnTable extends TableDoubleKey {
  function alm_columnTable() {
    $this->Table('alm_column');
    $this->key1 = 'idalm_column';
    $this->key2 = 'idalm_table';
    $this->title = 'Campos';
    $this->maxrows = 20;
    $this->order = 'idalm_table,rank';
    $this->addColumn('idalm_table','varchar',32,0,'alm_table','Table');
    $this->addColumn('idalm_column','varchar',32,1,0,'Id');
    $this->addColumn('type','varchar',16,0,0,'Type');
    $this->addColumn('size','int',0,0,0,'Size');
    $this->addColumn('rank','int',0,0,0,'Orden');
    $this->addColumn('pk','bool',0,0,0,'Primary Key?');
    $this->addColumn('fk','varchar',16,0,0,'Foreign Key Table');
    $this->addColumn('alm_column','varchar',100,0,0,'Description');
    $this->addColumn('extra','text',0,0,0,'Extras');
  }
}
