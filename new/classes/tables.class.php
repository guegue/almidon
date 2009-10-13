<?php

# FIXME: agregar soporte a roles y usuarios

class categoriaTable extends Table {
  function categoriaTable() {
    $this->Table('categoria');
    $this->key = 'idcategoria';
    $this->title = 'Categorias';
    $this->maxrows = 20;
    $this->order = 'categoria';
    $this->addColumn('idcategoria','serial',0,1,0,'Id');
    $this->addColumn('categoria','varchar',500,0,0,'Categoria');
  }
}

class paginaTable extends Table {
  function paginaTable() {
    $this->Table('pagina');
    $this->key = 'idpagina';
    $this->title = 'Paginas';
    $this->addColumn('idpagina','varchar',32,1,0,'Id');
    $this->addColumn('pagina','varchar',500,0,0,'Titulo');
    $this->addColumn('texto','text',0,0,0,'Texto');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
    $this->addColumn('url','varchar',500,0,0,'URL');
  }
}

class galeriaTable extends Table {
  function galeriaTable() {
    $this->Table('galeria');
    $this->key = 'idgaleria';
    $this->title = 'Galeria';
    $this->order = 'idgaleria DESC';
    $this->addColumn('idgaleria','serial',0,1,0,'Id');
    $this->addColumn('galeria','varchar',500,0,0,'Titulo');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
    $this->addColumn('idcategoria','int',0,0,'categoria','Categoria');
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
    $this->addColumn('idcategoria','int',0,0,'categoria','Categoria');
    $this->addColumn('fecha','date',0,0,0,'Fecha','-20:+1');
    $this->addColumn('resumen','text',0,0,0,'Resumen');
    $this->addColumn('portada','bool',0,0,0,'Portada?');
    $this->addColumn('archivo','file',0,0,0,'Archivo');
    $this->addColumn('imagen','image',0,0,0,'Imagen');
  }
}

class enlaceTable extends Table {
  function enlaceTable() {
    $this->Table('enlace');
    $this->key = 'idenlace';
    $this->title ='Enlaces';
    $this->order ='idenlace DESC';
    $this->addColumn('idenlace','serial',0,1,0,'Id');
    $this->addColumn('enlace','varchar',500,0,0,'Titulo');
    $this->addColumn('url','varchar',500,0,0,'URL');
    $this->addColumn('idcategoria','int',0,0,'categoria','Categoria');
  }
}

?>
