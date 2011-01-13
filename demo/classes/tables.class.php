<?php
class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->title ='Paginas';
    $this->order ='pagina';
    $this->addColumn('idpagina','serial',0,1,0,'ID');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo');
    $this->addColumn('foto','image',0,0,0,'Foto');
    $this->addColumn('descripcion','text',0,0,0,'Descripcion');
  }
}
class docTable extends Table {
  function docTable() {
    $this->Table('doc');
    $this->title ='Documentos';
    $this->order ='doc';
    $this->addColumn('iddoc','serial',0,1,0,'ID');
    $this->addColumn('doc','varchar',500,0,0,'Titulo');
    $this->addColumn('archivo','file',0,0,0,'Archivo');
    $this->addColumn('portada','image',0,0,0,'Imagen');
    $this->addColumn('descripcion','xhtml',0,0,0,'Descripcion');
  }
}
class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->title ='Enlaces';
    $this->order ='enlace';
    $this->addColumn('idenlace','serial',0,1,0,'ID');
    $this->addColumn('enlace','varchar',500,0,0,'Titulo');
    $this->addColumn('url','varchar',600,0,0,'Direccion web');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
  }
}
class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->title ='Galerias';
    $this->order ='galeria';
    $this->addColumn('idgaleria','serial',0,1,0,'ID');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','date',0,0,0,'Fecha');
  }
}
class fotoTable extends Table {
  function fotoTable() {
    $this->Table('foto');
    $this->title ='Fotos';
    $this->order ='foto';
    $this->addColumn('idfoto','serial',0,1,0,'ID');
    $this->addColumn('foto','varchar',500,0,0,'Titulo');
    $this->addColumn('imagen','image',0,0,0,'Foto');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria');
  }
}
class agendaTable extends Table {
  function agendaTable() {
    $this->Table('agenda');
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
    $this->title ='Noticias';
    $this->order ='fecha';
    $this->addColumn('idnoticia','serial',0,1,0,'ID');
    $this->addColumn('noticia','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','datenull',0,0,0,'Fecha');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('foto','image',0,0,0,'Foto');
  }
}
class emailTable extends Table {
  function emailTable() {
    $this->Table('email');
    $this->title ='Email';
    $this->order ='login,domain,ctld';
    $this->addColumn('login','varchar',32,1,0,'Login',array('search'=>true));
    $this->addColumn('domain','varchar',128,1,0,'Domain',array('search'=>true));
    $this->addColumn('ctld','varchar',8,1,0,'cTLD');
    $this->addColumn('observaciones','text',0,0,0,'cTLD');
  }
}
class countryTable extends Table {
  function countryTable() {
    $this->Table('country');
    $this->title ='Country';
    $this->order ='country';
    $this->addColumn('idcountry','char',2,1,0,'Pais');
    $this->addColumn('country','varchar',200,0,0,'Pais',array('search'=>true));
  }
}
