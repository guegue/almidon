DROP DATABASE IF EXISTS almidondemo;
CREATE DATABASE almidondemo WITH TEMPLATE = template0 ENCODING = 'UTF8';
CREATE USER almidondemo WITH PASSWORD 'secreto1';
CREATE USER almidondemowww WITH PASSWORD 'secreto2';
ALTER DATABASE almidondemo OWNER TO almidondemo;

\connect almidondemo

-- *********************************
-- * Parte esencial de almidon     *
-- *********************************

DROP TABLE IF EXISTS alm_role CASCADE;
CREATE TABLE alm_role (idalm_role varchar(8) PRIMARY KEY, alm_role varchar(8));
ALTER TABLE public.alm_role OWNER TO almidondemo;

DROP TABLE IF EXISTS alm_user CASCADE;
CREATE TABLE alm_user (idalm_user varchar(16) PRIMARY KEY, idalm_role varchar(8) REFERENCES alm_role, password varchar(200) NOT NULL, alm_user varchar(200) NOT NULL, email varchar(200));
ALTER TABLE public.alm_user OWNER TO almidondemo;

DROP TABLE IF EXISTS alm_table CASCADE;
CREATE TABLE alm_table (idalm_table varchar(48) PRIMARY KEY, alm_table varchar(100), pkey varchar(50), orden varchar (100), rank int, hidden bool, parent varchar(32), child varchar(32), restrictby varchar(50), label_bool varchar(100), display varchar(200), help text);
ALTER TABLE public.alm_table OWNER TO almidondemo;

DROP TABLE IF EXISTS alm_column CASCADE;
CREATE TABLE alm_column (idalm_column varchar (50), idalm_table varchar (48) REFERENCES alm_table, type varchar (16), size int, pk bool, fk varchar(48), alm_column varchar(100), extra text, rank int, idalm_role int REFERENCES alm_role, list_values varchar(500), PRIMARY KEY (idalm_column, idalm_table));
ALTER TABLE public.alm_column OWNER TO almidondemo;

DROP TABLE IF EXISTS alm_access CASCADE;
CREATE TABLE alm_access (idalm_role varchar(8) REFERENCES alm_role NULL, idalm_user varchar(16) REFERENCES alm_user , idalm_table varchar(48) REFERENCES alm_table, idalm_access serial PRIMARY KEY);
ALTER TABLE public.alm_access OWNER TO almidondemo;

-- especificamos id porque puede usarse 'hard-coded' en algun lado
INSERT INTO alm_role VALUES ('full', 'Control Total');
INSERT INTO alm_role VALUES ('edit', 'Edicion');
INSERT INTO alm_role VALUES ('delete', 'Correccion');
INSERT INTO alm_role VALUES ('read', 'Lectura');
INSERT INTO alm_role VALUES ('deny', 'Sin Accesso');

-- usuario admin - cambiar password!
INSERT INTO alm_user VALUES ('admin', 'full', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'admin@example.com');

-- *********************************
-- * Parte del demo                *
-- ********************************* 

-- tablas demo
DROP TABLE IF EXISTS agenda CASCADE;
CREATE TABLE agenda (idagenda serial PRIMARY KEY, agenda varchar(500), fecha date, lugar varchar(120), texto text, organiza varchar(500));
ALTER TABLE public.agenda OWNER TO almidondemo;

DROP TABLE IF EXISTS doc CASCADE;
CREATE TABLE doc (iddoc serial PRIMARY KEY, doc varchar(500), portada varchar(500), descripcion text, archivo varchar(500));
ALTER TABLE public.doc OWNER TO almidondemo;

DROP TABLE IF EXISTS enlace CASCADE;
CREATE TABLE enlace (idenlace serial PRIMARY KEY, enlace varchar(500), url varchar(600), texto text, imagen varchar(500));
ALTER TABLE public.enlace OWNER TO almidondemo;

DROP TABLE IF EXISTS galeria CASCADE;
CREATE TABLE galeria (idgaleria serial PRIMARY KEY, galeria varchar(500), fecha date);
ALTER TABLE public.galeria OWNER TO almidondemo;

DROP TABLE IF EXISTS foto CASCADE;
CREATE TABLE foto (idfoto serial PRIMARY KEY, idgaleria integer REFERENCES galeria, foto varchar(500), imagen varchar(500));
ALTER TABLE public.foto OWNER TO almidondemo;

DROP TABLE IF EXISTS noticia CASCADE;
CREATE TABLE noticia (idnoticia serial PRIMARY KEY, noticia varchar(500), fecha date, texto text, foto varchar(500));
ALTER TABLE public.noticia OWNER TO almidondemo;

DROP TABLE IF EXISTS pagina CASCADE;
CREATE TABLE pagina (idpagina serial PRIMARY KEY, pagina varchar(500), foto varchar(500), descripcion text);
ALTER TABLE public.pagina OWNER TO almidondemo;

GRANT SELECT ON agenda,doc,enlace,galeria,foto,noticia,pagina TO almidondemowww;

INSERT INTO agenda (agenda, fecha, lugar, texto, organiza) VALUES ('Quijote de la Mancha', '2007-10-13', 'En un lugar de la Mancha', 'Es, pues, de saber, que este sobredicho hidalgo, los ratos que estaba ocioso (que eran los más del año) se daba a leer libros de caballerías con tanta afición y gusto, que olvidó casi de todo punto el ejercicio de la caza.', 'UCA IAS');
INSERT INTO agenda (agenda, fecha, lugar, texto, organiza) VALUES ('Que trata de la primera salida', '2007-10-13', 'Puerto Lapice', 'Estos pensamientos le hicieron titubear en su propósito; mas pudiendo más su locura que otra razón alguna, propuso de hacerse armar caballero del primero que topase, a imitación de otros muchos que así lo hicieron, según él había leído en los libros que tal le tenían. En lo de las armas blancas pensaba limpiarlas de manera, en teniendo lugar, que lo fuesen más que un armiño: y con esto se quietó y prosiguió su camino, sin llevar otro que el que su caballo quería, creyendo que en aquello consistía la fuerza de las aventuras. Yendo, pues, caminando nuestro flamante aventurero, iba hablando consigo mismo, y diciendo.', 'Wkipedia');

INSERT INTO doc (doc, portada, descripcion, archivo) VALUES ('Memorias 2005', '', '', NULL);
INSERT INTO doc (doc, portada, descripcion, archivo) VALUES ('Portada Memorias 2005', '', '', NULL);
INSERT INTO doc (doc, portada, descripcion, archivo) VALUES ('Memorias 2002', '', '', NULL);
INSERT INTO doc (doc, portada, descripcion, archivo) VALUES ('Memorias 2002', '', '', NULL);
INSERT INTO doc (doc, portada, descripcion, archivo) VALUES ('Memorias 2002', '', '', NULL);

INSERT INTO enlace (enlace, url, texto, imagen) VALUES ('Google', 'http://www.google.com/', '', '1207951914_logo.gif');
INSERT INTO enlace (enlace, url, texto, imagen) VALUES ('Yahoo!', 'http://www.yahoo.com/', '', '1207951982_y3.gif');

INSERT INTO galeria (galeria, fecha) VALUES ('Galeria de ejemplos', NULL);

INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Ballons San Diego', 'balloons_2_bg_060504.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Playa Ocaso', 'beach_3_bg_010503.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Mar Big Sur', 'bigsur_28_bg_101203.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Lago de Canda', 'canada_40_bg_061904.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Zoom de chips de computadora', 'chips_3_bg_102602.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Ciudad en Irlanda', 'ireland_37_bg_070504.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Ciudad desconocida', 'roadtrip_23_bg_021604.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Frutas en fondo oscuro', 'fruit_2_bg_020203.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Granos de café', 'coffee_01_bg_031106.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Treboles de Irlanda', 'ireland_102_bg_061602.jpg');

INSERT INTO noticia (noticia, fecha, texto, foto) VALUES ('Don Quijote Primera Parte Capítulo Dos', '2007-10-13', 'Hechas, pues, estas prevenciones, no quiso aguardar más tiempo a poner en efecto su pensamiento, apretándole a ello la falta que él pensaba que hacía en el mundo su tardanza, según eran los agravios que pensaba deshacer, tuertos que enderezar, sinrazones que enmendar, y abusos que mejorar, y deudas que satisfacer; y así, sin dar parte a persona alguna de su intención, y sin que nadie le viese, una mañana, antes del día (que era uno de los calurosos del mes de Julio), se armó de todas sus armas, subió sobre Rocinante, puesta su mal compuesta celada, embrazó su adarga, tomó su lanza, y por la puerta falsa de un corral, salió al campo con grandísimo contento y alborozo de ver con cuánta facilidad había dado principio a su buen deseo. Mas apenas se vió en el campo, cuando le asaltó un pensamiento terrible, y tal, que por poco le hiciera dejar la comenzada empresa: y fue que le vino a la memoria que no era armado caballero, y que, conforme a la ley de caballería, ni podía ni debía tomar armas con ningún caballero; y puesto qeu lo fuera, había de llevar armas blancas, como novel caballero, sin empresa en el escudo, hasta que por su esfuerzo la ganase.', '');
INSERT INTO noticia (noticia, fecha, texto, foto) VALUES ('Casi todo aquel día caminó sin acontecerle', '2007-10-13', 'Autores hay que dicen que la primera aventura que le avino fue la de PuertoLápice; otros dicen que la de los molinos de viento; pero lo que yo he podido averiguar en este caso, y lo que he hallado escrito en los anales de la Mancha, es que él anduvo todo aquel día, y al anochecer, su rocín y él se hallaron cansados y muertos de hambre; y que mirando a todas partes, por ver si descubriría algún castillo o alguna majada de pastores donde recogerse, y adonde pudiese remediar su mucha necesidad, vió no lejos del camino por donde iba una venta, que fue como si viera una estrella, que a los portales, si no a los alcázares de su redención, le encaminaba. Dióse priesa a caminar, y llegó a ella a tiempo que anochecía. Estaban acaso a la puerta dos mujeres mozas, de estas que llaman del partido, las cuales iban a Sevilla con unos arrieros, que en la venta aquella noche acertaron a hacer jornada; y como a nuestro aventurero todo cuanto pensaba, veía o imaginaba, le parecía ser hecho y pasar al modo de lo que había leído, luego que vió la venta se le representó que era un castillo con sus cuatro torres y chapiteles de luciente plata, sin faltarle su puente levadizo y honda cava, con todos aquellos adherentes que semejantes castillos se pintan.
Fuese llegando a la venta (que a él le parecía castillo), y a poco trecho de ella detuvo las riendas a Rocinante, esperando que algún enano se pusiese entre las almenas a dar señal con alguna trompeta de que llegaba caballero al castillo; pero como vió que se tardaban, y que Rocinante se daba priesa por llegar a la caballeriza, se llegó a la puerta de la venta, y vió a las dos distraídas mozas que allí estaban, que a él le parecieron dos hermosas doncellas, o dos graciosas damas, que delante de la puerta del castillo se estaban solazando. En esto sucedió acaso que un porquero, que andaba recogiendo de unos rastrojos una manada de puercos (que sin perdón así se llaman), tocó un cuerno, a cuya señal ellos se recogen, y al instante se le representó a D. Quijote lo que deseaba, que era que algún enano hacía señal de su venida, y así con extraño contento llegó a la venta y a las damas, las cuales, como vieron venir un hombre de aquella suerte armado, y con lanza y adarga, llenas de miedo se iban a entrar en la venta; pero Don Quijote, coligiendo por su huida su miedo, alzándose la visera de papelón y descubriendo su seco y polvoso rostro, con gentil talante y voz reposada les dijo: non fuyan las vuestras mercedes, nin teman desaguisado alguno, ca a la órden de caballería que profeso non toca ni atañe facerle a ninguno, cuanto más a tan altas doncellas, como vuestras presencias demuestran.', '');


INSERT INTO pagina (idpagina, pagina, foto, descripcion) VALUES (1, 'Qué es almidón?', '1207951790_almidon-logo-by-AtmaComunicaciones.png', 'Almidón es una plataforma de desarrollo y hospedaje web desarrollado por Guegue∞, cuyo componente más popular es un sistema de manejo de contenido (CMS) que permite un desarrollo sólido de un sitio web, una administración sencilla, rápida, y un sitio web con buen desempeño. Actualmente en su mayoría escrito para Linux usando PHP, Apache y Postgresql, pero siendo probado y usado en distintas plataformas. Publicado bajo la licencia open source GPL v3, puede obtenerse en http://trac.almidon.org/');
INSERT INTO pagina (idpagina, pagina, foto, descripcion) VALUES (2, 'Qué ondas con almidón?', '', 'La cosa es ver qué hacemos con el? seguimos con php? dejamos smarty? mejoramos el aspecto gráfico del administrador? quiénes quieren participar? Estrategias para el desarrollo open source de almidón.');
INSERT INTO pagina (idpagina, pagina, foto, descripcion) VALUES (3, 'Reunión sobre...', '', 'Después de algunas platicas con algunos de ustedes, decidimos hacer una reunion para platicar del tema, la asistencia es abierta. y aunque hay mas o menos una agenda, me gustaria mantenerla abierta.');
INSERT INTO pagina (idpagina, pagina, foto, descripcion) VALUES (4, 'Licencia GPL', '', 'A inicio de año sacamos almidon como GPL, recién en abril lo documentamos mejor y lo hicimos instalable (a un dificil, pero ya se puede), ahora incluiremos un demo, para que no haya que desarrollar todo un sistema solo para ver como funciona.');
INSERT INTO pagina (idpagina, pagina, foto, descripcion) VALUES (5, 'Más información...', '', 'Wiki: http://almidon.org/
Trac: http://trac.almidon.org/
Demo: http://demo.almidon.org/');

-- usuarios demo
INSERT INTO alm_user VALUES ('demo', 'read', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Demo', 'demo@example.com');
INSERT INTO alm_user VALUES ('alice', NULL, 'fe01ce2a7fbac8fafaed7c982a04e229', 'Alice', 'alice@example.com');

-- tablas a las cuales el acceso se puede personalizar
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('pagina', 'Paginas', 'idpagina', 'pagina', 1);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('doc', 'Documentos', 'iddoc', 'doc', 2);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('enlace', 'Enlaces', 'idenlace', 'enlace', 3);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('galeria', 'Galerias', 'idgaleria', 'galeria', 4);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('foto', 'Fotos', 'idfoto', 'foto', 5);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('agenda', 'Agenda', 'idagenda', 'agenda', 6);
INSERT INTO alm_table (idalm_table, alm_table, pkey, orden, rank) VALUES ('noticia', 'Noticias', 'idnoticia', 'fecha', 7);

-- 'control total' para 'alice' en 'pagina'
INSERT INTO alm_access VALUES ('full', 'alice', 'pagina');

-- campos para tables.class.php
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('foto', 'pagina', 'image', 0, false, '', 'Foto', NULL, NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('descripcion', 'pagina', 'text', 0, false, '', 'Descripcion', NULL, NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('fecha', 'galeria', 'date', 0, false, '', 'Fecha', NULL, NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idgaleria', 'foto', 'int', 0, false, 'galeria', 'Galeria', NULL, NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('fecha', 'agenda', 'date', 0, false, '', 'Fecha', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('lugar', 'agenda', 'varchar', 120, false, '', 'Lugar', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('texto', 'agenda', 'text', 0, false, '', 'Evento', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('organiza', 'agenda', 'varchar', 500, false, '', 'Organizado por', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('archivo', 'doc', 'file', 0, false, '', 'Archivo', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('portada', 'doc', 'image', 0, false, '', 'Imagen', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('descripcion', 'doc', 'xhtml', 0, false, '', 'Descripcion', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('fecha', 'noticia', 'datenull', 0, false, '', 'Fecha', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('texto', 'noticia', 'text', 0, false, '', 'Texto', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('foto', 'noticia', 'image', 0, false, '', 'Foto', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('url', 'enlace', 'varchar', 600, false, '', 'Direccion web', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('texto', 'enlace', 'text', 0, false, '', 'Texto', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('imagen', 'enlace', 'image', 0, false, '', 'Imagen', '', NULL);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idagenda', 'agenda', 'serial', 0, true, '', 'ID', '', 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('agenda', 'agenda', 'varchar', 500, false, '', 'Titulo', '', 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('iddoc', 'doc', 'serial', 0, true, '', 'ID', '', 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('doc', 'doc', 'varchar', 500, false, '', 'Titulo', '', 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idenlace', 'enlace', 'serial', 0, true, '', 'ID', '', 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('enlace', 'enlace', 'varchar', 500, false, '', 'Titulo', '', 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idfoto', 'foto', 'serial', 0, true, '', 'ID', NULL, 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('foto', 'foto', 'varchar', 500, false, '', 'Titulo', NULL, 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('imagen', 'foto', 'image', 0, false, '', 'Foto', '100,300x300', 3);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idgaleria', 'galeria', 'serial', 0, true, '', 'ID', NULL, 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('galeria', 'galeria', 'varchar', 500, false, '', 'Titulo', NULL, 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idnoticia', 'noticia', 'serial', 0, true, '', 'ID', '', 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('noticia', 'noticia', 'varchar', 500, false, '', 'Titulo', '', 2);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('idpagina', 'pagina', 'serial', 0, true, '', 'ID', NULL, 1);
INSERT INTO alm_column (idalm_column, idalm_table, type, size, pk, fk, alm_column, extra, rank) VALUES ('pagina', 'pagina', 'varchar', 500, false, '', 'Titulo', NULL, 2);
