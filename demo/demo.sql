CREATE DATABASE almidondemo WITH TEMPLATE = template0 ENCODING = 'UTF8';
CREATE USER almidondemo WITH PASSWORD 'secreto1';
CREATE USER almidondemowww WITH PASSWORD 'secreto2';
ALTER DATABASE almidondemo OWNER TO almidondemo;

\connect almidondemo

CREATE TABLE agenda (idagenda serial PRIMARY KEY, agenda varchar(500), fecha date, lugar varchar(120), texto text, organiza varchar(500));
ALTER TABLE public.agenda OWNER TO almidondemo;

CREATE TABLE doc (iddoc serial PRIMARY KEY, doc varchar(500), portada varchar(500), descripcion text, archivo varchar(500));
ALTER TABLE public.doc OWNER TO almidondemo;

CREATE TABLE enlace (idenlace serial PRIMARY KEY, enlace varchar(500), url varchar(600), texto text, imagen varchar(500));
ALTER TABLE public.enlace OWNER TO almidondemo;

CREATE TABLE galeria (idgaleria serial PRIMARY KEY, galeria varchar(500), fecha date);
ALTER TABLE public.galeria OWNER TO almidondemo;

CREATE TABLE foto (idfoto serial PRIMARY KEY, idgaleria integer REFERENCES galeria, foto varchar(500), imagen varchar(500));
ALTER TABLE public.foto OWNER TO almidondemo;

CREATE TABLE noticia (idnoticia serial PRIMARY KEY, noticia varchar(500), fecha date, texto text, foto varchar(500));
ALTER TABLE public.noticia OWNER TO almidondemo;

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

INSERT INTO galeria (galeria, fecha) VALUES ('Primera reunión de almidón', '2008-04-12');
INSERT INTO galeria (galeria, fecha) VALUES ('Galeria de ejemplos', NULL);

INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Ballons San Diego', 'balloons_2_bg_060504.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Playa Ocaso', 'beach_3_bg_010503.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Mar Big Sur', 'bigsur_28_bg_101203.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Lago de Canda', 'canada_40_bg_061904.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Zoom de chips de computadora', 'chips_3_bg_102602.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Ciudad en Irlanda', 'ireland_37_bg_070504.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Ciudad desconocida', 'roadtrip_23_bg_021604.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Frutas en fondo oscuro', 'fruit_2_bg_020203.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Granos de café', 'coffee_01_bg_031106.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (2, 'Treboles de Irlanda', 'ireland_102_bg_061602.jpg');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Señalando el ''datagrid'' con el dedo', '1208475133_IMG_1813.JPG');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Entendiendo Safari?', '1208475451_IMG_1798.JPG');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Leandro, Marconi, otros...', '1208475793_IMG_1808.JPG');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Javier, Melvin y Maribel', '1208476743_IMG_1814.JPG');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Christian y Mac', '1208477323_IMG_1817.JPG');
INSERT INTO foto (idgaleria, foto, imagen) VALUES (1, 'Donald, Alfredo, Leandro, Marconi...', '1208477427_IMG_1816.JPG');

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
