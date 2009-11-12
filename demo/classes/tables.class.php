<?php
class agendaTable extends Table {
  function agendaTable() {
    $this->Table('agenda');
    $this->key = 'idagenda';
    $this->title ='Agenda';
    $this->order ='agenda';
    $this->addColumn('agenda','varchar',500,0,0,'Titulo','');
    $this->addColumn('fecha','date',0,0,0,'Fecha','');
    $this->addColumn('idagenda','serial',0,1,0,'ID','');
    $this->addColumn('lugar','varchar',120,0,0,'Lugar','');
    $this->addColumn('organiza','varchar',500,0,0,'Organizado por','');
    $this->addColumn('texto','text',0,0,0,'Evento','');
  }
}
class docTable extends Table {
  function docTable() {
    $this->Table('doc');
    $this->key = 'iddoc';
    $this->title ='Documentos';
    $this->order ='doc';
    $this->addColumn('archivo','file',0,0,0,'Archivo','');
    $this->addColumn('descripcion','xhtml',0,0,0,'Descripcion','');
    $this->addColumn('doc','varchar',500,0,0,'Titulo','');
    $this->addColumn('iddoc','serial',0,1,0,'ID','');
    $this->addColumn('portada','image',0,0,0,'Imagen','');
  }
}
class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->key = 'idenlace';
    $this->title ='Enlaces';
    $this->order ='enlace';
    $this->addColumn('enlace','varchar',500,0,0,'Titulo','');
    $this->addColumn('idenlace','serial',0,1,0,'ID','');
    $this->addColumn('imagen','image',0,0,0,'Imagen','');
    $this->addColumn('texto','text',0,0,0,'Texto','');
    $this->addColumn('url','varchar',600,0,0,'Direccion web','');
  }
}
class fotoTable extends Table {
  function fotoTable() {
    $this->Table('foto');
    $this->key = 'idfoto';
    $this->title ='Fotos';
    $this->order ='foto';
    $this->addColumn('foto','varchar',500,0,0,'Titulo','');
    $this->addColumn('idfoto','serial',0,1,0,'ID','');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria','');
    $this->addColumn('imagen','image',0,0,0,'Foto','100,300x300');
  }
}
class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->key = 'idgaleria';
    $this->title ='Galerias';
    $this->order ='galeria';
    $this->addColumn('fecha','date',0,0,0,'Fecha','');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo','');
    $this->addColumn('idgaleria','serial',0,1,0,'ID','');
  }
}
class noticiaTable extends Table {
  function noticiaTable() {
    $this->Table('noticia');
    $this->key = 'idnoticia';
    $this->title ='Noticias';
    $this->order ='fecha';
    $this->addColumn('fecha','datenull',0,0,0,'Fecha','');
    $this->addColumn('foto','image',0,0,0,'Foto','');
    $this->addColumn('idnoticia','serial',0,1,0,'ID','');
    $this->addColumn('noticia','varchar',500,0,0,'Titulo','');
    $this->addColumn('texto','text',0,0,0,'Texto','');
  }
}
class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->key = 'idpagina';
    $this->title ='Paginas';
    $this->order ='pagina';
    $this->addColumn('descripcion','text',0,0,0,'Descripcion','');
    $this->addColumn('foto','image',0,0,0,'Foto','');
    $this->addColumn('idpagina','serial',0,1,0,'ID','');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo','');
  }
}
