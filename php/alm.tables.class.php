<?php
if ( !class_exists('alm_userTable') ) {
  class alm_userTable extends Table {
    function alm_userTable() {
      $this->Table('alm_user');
      $this->key = 'idalm_user';
      $this->title = ALM_USERS;
      $this->maxrows = 20;
      $this->order = 'idalm_user';
      $this->addColumn('idalm_user','varchar',16,1,0,'Id');
      $this->addColumn('password','password',200,0,0,'Password');
      $this->addColumn('alm_user','varchar',100,0,0,'Name');
      $this->addColumn('email','varchar',200,0,0,'E-Mail');
      $this->addColumn('idalm_role','varchar',8,0,'alm_role','Default Role');
    }
  }
}

if ( !class_exists('alm_roleTable') ) {
  class alm_roleTable extends Table {
    function alm_roleTable() {
      $this->Table('alm_role');
      $this->key = 'idalm_role';
      $this->title = ALM_ROLES;
      $this->maxrows = 20;
      $this->order = 'idalm_role';
      $this->addColumn('idalm_role','varchar',8,1,0,'Id');
      $this->addColumn('alm_role','varchar',100,0,0,'Description');
    }
  }
}

if ( !class_exists('alm_accessTable') ) {
  class alm_accessTable extends Table {
    function alm_accessTable() {
      $this->Table('alm_access');
      $this->key = 'idalm_access';
      $this->title = ALM_ACCESS;
      $this->maxrows = 20;
      $this->order = 'idalm_access';
      $this->addColumn('idalm_access','serial',0,1,0,'Id');
      $this->addColumn('idalm_role','varchar',8,0,'alm_role','Role');
      $this->addColumn('idalm_user','varchar',16,0,'alm_user','Username');
      $this->addColumn('idalm_table','varchar',32,0,'alm_table','Table');
    }
  }
}

if ( !class_exists('alm_tableTable') ) {
  class alm_tableTable extends Table {
    function alm_tableTable() {
      $this->Table('alm_table');
      $this->key = 'idalm_table';
      $this->title = ALM_TABLES;
      $this->maxrows = 20;
      $this->order = 'rank';
      $this->addColumn('idalm_table','varchar',48,1,0,'Id');
      $this->addColumn('alm_table','varchar',100,0,0,'Description');
      $this->addColumn('pkey','varchar',50,0,0,'Primary Key');
      $this->addColumn('orden','varchar',100,0,0,'Order By');
      $this->addColumn('rank','int',0,0,0,'Order');
      $this->addColumn('hidden','bool',0,0,0,'Hidden');
      $this->addColumn('parent','varchar',32,0,0,'Parent');
      $this->addColumn('child','varchar',200,0,0,'Children',array('help'=>'Enter comma separated values'));
      $this->addColumn('restrictby','varchar',50,0,0,'Restricted by');
    }
  }
}

if ( !class_exists('alm_columnTable') ) {
  class alm_columnTable extends TableDoubleKey {
    function alm_columnTable() {
      $this->Table('alm_column');
      $this->key1 = 'idalm_column';
      $this->key2 = 'idalm_table';
      $this->title = ALM_FIELDS;
      $this->maxrows = 20;
      $this->order = 'idalm_table,rank';
      $this->addColumn('idalm_table','varchar',48,0,'alm_table','Table');
      $this->addColumn('idalm_column','varchar',50,1,0,'Id');
      $this->addColumn('type','varchar',16,0,0,'Type',array('list_values'=>array('auth_user'=>'Auth User','bool'=>'Boolean','char'=>'Char','date'=>'Date','datetime'=>'Date and Time','file'=>'File','image'=>'Image','int'=>'integer','numeric'=>'Numeric','password'=>'Password','serial'=>'Serial (Autonumber)','text'=>'Long Text','time'=>'Time','varchar'=>'Varchar (Text)','video'=>'Video')));
      $this->addColumn('size','int',0,0,0,'Size');
      $this->addColumn('rank','int',0,0,0,'Order');
      $this->addColumn('pk','bool',0,0,0,'Primary Key?');
      $this->addColumn('fk','varchar',48,0,0,'Foreign Key Table');
      $this->addColumn('alm_column','varchar',250,0,0,'Description');
      $this->addColumn('idalm_role','varchar',8,0,'alm_role','Allow only to',array('help'=>'This column will only be available to users under this role'));
      $this->addColumn('label_bool','varchar',100,0,0,'Boolean labels',array('help'=>'Enter both labels separated by :'));
      $this->addColumn('help','text',0,0,0,'Help');
      $this->addColumn('search','bool',0,0,0,'Searchable',array('help'=>'Include in quick search'));
      $this->addColumn('cdn','bool',0,0,0,'CDN',array('help'=>'Content Delivery Network'));
      $this->addColumn('display','varchar',200,0,0,'Display as');
      $this->addColumn('list_values','varchar',500,0,0,'List values',array('help'=>'Enter array'));
      #$this->addColumn('sizes','varchar',100,0,0,'Image sizes');
      #$this->addColumn('range','varchar',100,0,0,'Image sizes');
    }
  }
}
