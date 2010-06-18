<?php
class personTable extends Table {
  function personTable() {
    $this->Table('person');
    $this->key = 'idperson';
    $this->child ='planguage,pcountry,peom';
    $this->title ='Person';
    $this->order ='familyname,name';
    $this->addColumn('idperson','serial',0,1,0,'Id');
    $this->addColumn('name','varchar',200,0,0,'Given name');
    $this->addColumn('title','varchar',10,0,0,'Title');
    $this->addColumn('familyname','varchar',200,0,0,'Family Name');
    $this->addColumn('homeaddress1','varchar',200,0,0,'Home Address 1');
    $this->addColumn('hometown1','varchar',100,0,0,'Home Town 1');
    $this->addColumn('homecode1','varchar',10,0,0,'Home Postcode 1');
    $this->addColumn('idcountry1','char',2,0,'country','Country of Residence 1');
    $this->addColumn('homeaddress2','varchar',200,0,0,'Home Address 2');
    $this->addColumn('hometown2','varchar',100,0,0,'Home Town 2');
    $this->addColumn('homecode2','varchar',10,0,0,'Home Postcode 2');
    $this->addColumn('idcountry2','char',2,0,'country','Country of Residence 2');
    $this->addColumn('landline','varchar',50,0,0,'Telephone landline');
    $this->addColumn('mobile','varchar',50,0,0,'Telephone cellphone');
    $this->addColumn('fax','varchar',50,0,0,'Fax');
    $this->addColumn('email','varchar',100,0,0,'E-mail');
    $this->addColumn('skype','varchar',100,0,0,'Skype Id');
    $this->addColumn('nationality1','char',2,0,'country','Country of Nationality 1');
    $this->addColumn('nationality2','char',2,0,'country','Country of Nationality 2');
    $this->addColumn('lastdiploma','int',0,0,0,'Year of last diploma');
    $this->addColumn('pexperience','int',0,0,0,'Years of Professional Experience');
    $this->addColumn('rexperience','int',0,0,0,'Yers of Related Experience');
    $this->addColumn('mexperience','bool',0,0,0,'EOM Experience?');
    $this->addColumn('idorigincv','int',0,0,'origincv','Origin of CV');
    $this->addColumn('comments','text',0,0,0,'Comments');
  }
}
class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->key = 'idpagina';
    $this->hidden = true;
    $this->title ='Paginas';
    $this->order ='pagina';
    $this->addColumn('idpagina','serial',0,1,0,'ID');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo');
    $this->addColumn('foto','image',0,0,0,'Foto');
    $this->addColumn('descripcion','text',0,0,0,'Descripcion');
  }
}
class planguageTable extends TableDoubleKey {
  function planguageTable() {
    $this->Table('planguage');
    $this->key1 = 'idperson';
    $this->key2 = 'idlanguage';
    $this->parent ='person';
    $this->title ='Person - Languages';
    $this->order ='planguage.idperson,planguage.idlanguage';
    $this->addColumn('idperson','int',0,1,'person','Person');
    $this->addColumn('idlanguage','int',0,1,'language','Language');
    $this->addColumn('level','int',0,0,0,'Level (1 - excellent; 5 - basic)');
  }
}
class docTable extends Table {
  function docTable() {
    $this->Table('doc');
    $this->key = 'iddoc';
    $this->hidden = true;
    $this->title ='Documentos';
    $this->order ='doc';
    $this->addColumn('iddoc','serial',0,1,0,'ID');
    $this->addColumn('doc','varchar',500,0,0,'Titulo');
    $this->addColumn('archivo','file',0,0,0,'Archivo');
    $this->addColumn('portada','image',0,0,0,'Imagen');
    $this->addColumn('descripcion','xhtml',0,0,0,'Descripcion');
  }
}
class pcountryTable extends TableDoubleKey {
  function pcountryTable() {
    $this->Table('pcountry');
    $this->key1 = 'idperson';
    $this->key2 = 'idcountry';
    $this->title ='Person - Countries';
    $this->order ='pcountry.idperson,pcountry.idcountry';
    $this->addColumn('idperson','int',0,1,'person','Person',array('display'=>"familyname||', '||name"));
    $this->addColumn('idcountry','char',2,1,'country','Country of experience');
    $this->addColumn('comments','text',0,0,0,'Comments');
  }
}
class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->key = 'idenlace';
    $this->hidden = true;
    $this->title ='Enlaces';
    $this->order ='enlace';
    $this->addColumn('idenlace','serial',0,1,0,'ID');
    $this->addColumn('enlace','varchar',500,0,0,'Titulo');
    $this->addColumn('url','varchar',600,0,0,'Direccion web');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
  }
}
class eomTable extends Table {
  function eomTable() {
    $this->Table('eom');
    $this->key = 'ideom';
    $this->title ='EOM';
    $this->order ='ideom';
    $this->addColumn('ideom','serial',0,1,0,'Id');
    $this->addColumn('eom','varchar',200,0,0,'EOM');
  }
}
class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->key = 'idgaleria';
    $this->title ='Galerias';
    $this->order ='galeria';
    $this->addColumn('idgaleria','serial',0,1,0,'ID');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','date',0,0,0,'Fecha');
  }
}
class peomTable extends TableDoubleKey {
  function peomTable() {
    $this->Table('peom');
    $this->key1 = 'idperson';
    $this->key2 = 'ideom';
    $this->title ='Person EOM';
    $this->order ='peom.idperson,peom.ideom';
    $this->addColumn('idperson','int',0,1,'person','Person');
    $this->addColumn('ideom','int',0,1,'eom','EOM');
    $this->addColumn('datefrom','date',0,0,0,'From');
    $this->addColumn('dateto','date',0,0,0,'To');
  }
}
class fotoTable extends Table {
  function fotoTable() {
    $this->Table('foto');
    $this->key = 'idfoto';
    $this->title ='Fotos';
    $this->order ='foto';
    $this->addColumn('idfoto','serial',0,1,0,'ID');
    $this->addColumn('foto','varchar',500,0,0,'Titulo');
    $this->addColumn('imagen','image',0,0,0,'Foto');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria');
  }
}
class peducationTable extends Table {
  function peducationTable() {
    $this->Table('peducation');
    $this->key = 'idpeducation';
    $this->title ='Education';
    $this->order ='peducation.idperson,idpeducation';
    $this->addColumn('idpeducation','serial',0,1,0,'Id');
    $this->addColumn('idperson','int',0,0,'person','Person');
    $this->addColumn('institution','varchar',500,0,0,'Institution');
    $this->addColumn('datefrom','date',0,0,0,'From');
    $this->addColumn('dateto','date',0,0,0,'To');
    $this->addColumn('diploma','varchar',200,0,0,'Diploma obtained');
  }
}
class agendaTable extends Table {
  function agendaTable() {
    $this->Table('agenda');
    $this->key = 'idagenda';
    $this->title ='Agenda';
    $this->order ='agenda';
    $this->addColumn('idagenda','serial',0,1,0,'ID');
    $this->addColumn('agenda','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','date',0,0,0,'Fecha');
    $this->addColumn('lugar','varchar',120,0,0,'Lugar');
    $this->addColumn('texto','text',0,0,0,'Evento');
    $this->addColumn('organiza','varchar',500,0,0,'Organizado por');
  }
}
class noticiaTable extends Table {
  function noticiaTable() {
    $this->Table('noticia');
    $this->key = 'idnoticia';
    $this->title ='Noticias';
    $this->order ='fecha';
    $this->addColumn('idnoticia','serial',0,1,0,'ID');
    $this->addColumn('noticia','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','datenull',0,0,0,'Fecha');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('foto','image',0,0,0,'Foto');
  }
}
class countryTable extends Table {
  function countryTable() {
    $this->Table('country');
    $this->key = 'idcountry';
    $this->title ='Countries';
    $this->order ='country';
    $this->addColumn('idcountry','char',2,1,0,'Id');
    $this->addColumn('country','varchar',200,0,0,'Country',array('search'=>true));
  }
}
class languageTable extends Table {
  function languageTable() {
    $this->Table('language');
    $this->key = 'idlanguage';
    $this->title ='Languages';
    $this->order ='language';
    $this->addColumn('idlanguage','serial',0,1,0,'Id');
    $this->addColumn('language','varchar',100,0,0,'Language',array('search'=>true));
    $this->addColumn('selected','bool',0,0,0,'Selected');
  }
}
class origincvTable extends Table {
  function origincvTable() {
    $this->Table('origincv');
    $this->key = 'idorigincv';
    $this->title ='Origin of CV';
    $this->order ='origincv';
    $this->addColumn('idorigincv','serial',0,0,0,'Id');
    $this->addColumn('origincv','varchar',100,0,0,'Origin of CV');
  }
}
