<?php
class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->key = 'idpagina';
    $this->title ='Paginas';
    $this->order ='pagina';
    $this->addColumn('idpagina','serial',0,1,0,'ID','');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo','');
    $this->addColumn('foto','image',0,0,0,'Foto','');
    $this->addColumn('descripcion','text',0,0,0,'Descripcion','');
  }
}
class personTable extends Table {
  function personTable() {
    $this->Table('person');
    $this->key = 'idperson';
    $this->title ='Person';
    $this->order ='familyname,name';
    $this->addColumn('idperson','serial',0,1,0,'Id','');
    $this->addColumn('name','varchar',200,0,0,'Given name','');
    $this->addColumn('familyname','varchar',200,0,0,'Family Name','');
    $this->addColumn('title','varchar',10,0,0,'Title','');
    $this->addColumn('homeaddress1','varchar',200,0,0,'Home Address 1','');
    $this->addColumn('hometown1','varchar',100,0,0,'Home Town 1','');
    $this->addColumn('homecode1','varchar',10,0,0,'Home Postcode 1','');
    $this->addColumn('idcountry1','char',2,0,'country','Country of Residence 1','');
    $this->addColumn('homeaddress2','varchar',200,0,0,'Home Address 2','');
    $this->addColumn('hometown2','varchar',100,0,0,'Home Town 2','');
    $this->addColumn('homecode2','varchar',10,0,0,'Home Postcode 2','');
    $this->addColumn('idcountry2','char',2,0,'country','Country of Residence 2','');
    $this->addColumn('landline','varchar',50,0,0,'Telephone landline','');
    $this->addColumn('mobile','varchar',50,0,0,'Telephone cellphone','');
    $this->addColumn('fax','varchar',50,0,0,'Fax','');
    $this->addColumn('email','varchar',100,0,0,'E-mail','');
    $this->addColumn('skype','varchar',100,0,0,'Skype Id','');
    $this->addColumn('nationality1','char',2,0,'country','Country of Nationality 1','');
    $this->addColumn('nationality2','char',2,0,'country','Country of Nationality 2','');
  }
}
class planguageTable extends TableDoubleKey {
  function planguageTable() {
    $this->Table('planguage');
    $this->key1 = 'idperson';
    $this->key2 = 'idlanguage';
    $this->title ='Person Languages';
    $this->order ='planguage.idperson';
    $this->addColumn('idperson','int',0,1,'person','Person',array('display'=>"familyname||', '||name"));
    $this->addColumn('idlanguage','int',0,1,'language','Language','');
    $this->addColumn('level','int',0,0,0,'Level (1 - excellent; 5 - basic)','');
  }
}
class docTable extends Table {
  function docTable() {
    $this->Table('doc');
    $this->key = 'iddoc';
    $this->title ='Documentos';
    $this->order ='doc';
    $this->addColumn('iddoc','serial',0,1,0,'ID','');
    $this->addColumn('doc','varchar',500,0,0,'Titulo','');
    $this->addColumn('archivo','file',0,0,0,'Archivo','');
    $this->addColumn('portada','image',0,0,0,'Imagen','');
    $this->addColumn('descripcion','xhtml',0,0,0,'Descripcion','');
  }
}
class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->key = 'idenlace';
    $this->title ='Enlaces';
    $this->order ='enlace';
    $this->addColumn('idenlace','serial',0,1,0,'ID','');
    $this->addColumn('enlace','varchar',500,0,0,'Titulo','');
    $this->addColumn('url','varchar',600,0,0,'Direccion web','');
    $this->addColumn('texto','text',0,0,0,'Texto','');
    $this->addColumn('imagen','image',0,0,0,'Imagen','');
  }
}
class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->key = 'idgaleria';
    $this->title ='Galerias';
    $this->order ='galeria';
    $this->addColumn('idgaleria','serial',0,1,0,'ID','');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo','');
    $this->addColumn('fecha','date',0,0,0,'Fecha','');
  }
}
class fotoTable extends Table {
  function fotoTable() {
    $this->Table('foto');
    $this->key = 'idfoto';
    $this->title ='Fotos';
    $this->order ='foto';
    $this->addColumn('idfoto','serial',0,1,0,'ID','');
    $this->addColumn('foto','varchar',500,0,0,'Titulo','');
    $this->addColumn('imagen','image',0,0,0,'Foto','100,300x300');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria','');
  }
}
class agendaTable extends Table {
  function agendaTable() {
    $this->Table('agenda');
    $this->key = 'idagenda';
    $this->title ='Agenda';
    $this->order ='agenda';
    $this->addColumn('idagenda','serial',0,1,0,'ID','');
    $this->addColumn('agenda','varchar',500,0,0,'Titulo','');
    $this->addColumn('fecha','date',0,0,0,'Fecha','');
    $this->addColumn('lugar','varchar',120,0,0,'Lugar','');
    $this->addColumn('texto','text',0,0,0,'Evento','');
    $this->addColumn('organiza','varchar',500,0,0,'Organizado por','');
  }
}
class noticiaTable extends Table {
  function noticiaTable() {
    $this->Table('noticia');
    $this->key = 'idnoticia';
    $this->title ='Noticias';
    $this->order ='fecha';
    $this->addColumn('idnoticia','serial',0,1,0,'ID','');
    $this->addColumn('noticia','varchar',500,0,0,'Titulo','');
    $this->addColumn('fecha','datenull',0,0,0,'Fecha','');
    $this->addColumn('texto','text',0,0,0,'Texto','');
    $this->addColumn('foto','image',0,0,0,'Foto','');
  }
}
class countryTable extends Table {
  function countryTable() {
    $this->Table('country');
    $this->key = 'idcountry';
    $this->title ='Countries';
    $this->order ='country';
    $this->addColumn('idcountry','char',2,1,0,'Id','');
    $this->addColumn('country','varchar',200,0,0,'Country','');
  }
}
class languageTable extends Table {
  function languageTable() {
    $this->Table('language');
    $this->key = 'idlanguage';
    $this->title ='Languages';
    $this->order ='language';
    $this->addColumn('idlanguage','serial',0,1,0,'Id','');
    $this->addColumn('language','varchar',100,0,0,'Language','');
  }
}
