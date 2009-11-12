<?php

# Implementacion cubana de usurios y roles
require_once('users.class.php');

class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->key = 'idpagina';
    $this->title = 'Paginas';
    $this->maxrows = 20;
    $this->order = 'idpagina';
    $this->addColumn('idpagina','serial',0,1,0,'Id');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo');
    $this->addColumn('foto','image',0,0,0,'Foto');
    $this->addColumn('descripcion','text',0,0,0,'Descripcion');
  }
}

class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->key = 'idgaleria';
    $this->title ='Galeria de Fotos';
    $this->order ='idgaleria DESC';
    $this->addColumn('idgaleria','serial',0,1,0,'Id');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo');
    $this->addColumn('fecha','date',0,0,0,'Fecha');
  }
}

class foto2Table extends Table {
  function foto2Table() {
    $this->Table('foto2');
    $this->key = 'idfoto2';
    $this->title ='Fotos 2';
    $this->order ='idgaleria DESC, idfoto DESC';
    $this->addColumn('idfoto2','serial',0,1,0,'Id');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria');
    $this->addColumn('foto2','varchar',500,0,0,'Titulo');
    $this->addColumn('imagen','image',0,0,0,'Foto','100,300x300');
  }
}

class fotoTable extends Table {
  function fotoTable() {
    $this->Table('foto');
    $this->key = 'idfoto';
    $this->title ='Fotos';
    $this->order ='idgaleria DESC, idfoto DESC';
    $this->addColumn('idfoto','serial',0,1,0,'Id');
    $this->addColumn('idgaleria','int',0,0,'galeria','Galeria');
    $this->addColumn('foto','varchar',500,0,0,'Titulo');
    $this->addColumn('imagen','image',0,0,0,'Foto','100,300x300');
  }
}

class docTable extends Table {
  function docTable() {
    $this->Table('doc');
    $this->key = 'iddoc';
    $this->title ='Documentos';
    $this->order ='iddoc DESC';
    $this->addColumn('iddoc','serial',0,1,0,'Id');
    $this->addColumn('doc','varchar',500,0,0,'Titulo');
    $this->addColumn('archivo','file',0,0,0,'Archivo');
    $this->addColumn('portada','image',0,0,0,'Imagen');
    $this->addColumn('descripcion','xhtml',0,0,0,'Descripcion');
  }
}

class agendaTable extends Table {
  function agendaTable() {
    $this->Table('agenda');
    $this->key = 'idagenda';
    $this->title ='Agenda';
    $this->order ='fecha DESC';
    $this->addColumn('idagenda','serial',0,1,0,'Id');
    $this->addColumn('agenda','varchar',500,0,0,'Titulo');
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
    $this->order ='fecha DESC';
    $this->addColumn('idnoticia','serial',0,1,0,'Id');
    $this->addColumn('noticia','varchar',500,0,0,'Titulo');
    $this->addColumn('ante','varchar',500,0,0,'Ante Titulo');
    $this->addColumn('fecha','datenull',0,0,0,'Fecha');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('foto','image',0,0,0,'Foto');
  }
}

class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->key = 'idenlace';
    $this->title = 'Enlaces';
    $this->order = 'idenlace DESC';
    $this->addColumn('idenlace','serial',0,1,0,'Id');
    $this->addColumn('enlace','varchar',500,0,0,'Título');
    $this->addColumn('url','varchar',600,0,0,'Dirección web');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
  }
}
