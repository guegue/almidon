--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: almidondemo; Type: DATABASE; Schema: -; Owner: almidondemo
--

CREATE DATABASE almidondemo WITH TEMPLATE = template0 ENCODING = 'UTF8';
CREATE USER almidondemo WITH PASSWORD 'secreto1';
CREATE USER almidondemowww WITH PASSWORD 'secreto2';


ALTER DATABASE almidondemo OWNER TO almidondemo;

\connect almidondemo

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: agenda; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE agenda (
    idagenda integer NOT NULL,
    agenda character varying(500),
    fecha date,
    lugar character varying(120),
    texto text,
    organiza character varying(500)
);


ALTER TABLE public.agenda OWNER TO almidondemo;

--
-- Name: doc; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE doc (
    iddoc integer NOT NULL,
    doc character varying(500),
    portada character varying(500),
    descripcion text,
    archivo character varying(500)
);


ALTER TABLE public.doc OWNER TO almidondemo;

--
-- Name: donante; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE donante (
    iddonante integer NOT NULL,
    donante character varying(500)
);


ALTER TABLE public.donante OWNER TO almidondemo;

--
-- Name: donanteproyecto; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE donanteproyecto (
    iddonanteproyecto integer NOT NULL,
    iddonante integer,
    idproyecto integer
);


ALTER TABLE public.donanteproyecto OWNER TO almidondemo;

--
-- Name: enlace; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE enlace (
    idenlace integer NOT NULL,
    enlace character varying(500),
    url character varying(600),
    texto text,
    imagen character varying(500)
);


ALTER TABLE public.enlace OWNER TO almidondemo;

--
-- Name: foto; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE foto (
    idfoto integer NOT NULL,
    idgaleria integer,
    foto character varying(500),
    imagen character varying(500)
);


ALTER TABLE public.foto OWNER TO almidondemo;

--
-- Name: galeria; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE galeria (
    idgaleria integer NOT NULL,
    galeria character varying(500),
    fecha date
);


ALTER TABLE public.galeria OWNER TO almidondemo;

--
-- Name: noticia; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE noticia (
    idnoticia integer NOT NULL,
    noticia character varying(500),
    fecha date,
    texto text,
    foto character varying(500)
);


ALTER TABLE public.noticia OWNER TO almidondemo;

--
-- Name: pagina; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE pagina (
    idpagina integer NOT NULL,
    pagina character varying(500),
    foto character varying(500),
    descripcion text
);


ALTER TABLE public.pagina OWNER TO almidondemo;

--
-- Name: programa; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE programa (
    idprograma integer NOT NULL,
    programa character varying(500),
    foto character varying(500),
    descripcion text
);


ALTER TABLE public.programa OWNER TO almidondemo;

--
-- Name: proyecto; Type: TABLE; Schema: public; Owner: almidondemo; Tablespace: 
--

CREATE TABLE proyecto (
    idproyecto integer NOT NULL,
    proyecto character varying(500),
    monto numeric,
    beneficiarios text,
    descripcion text,
    territorio text,
    duracion character varying(30),
    idgaleria integer
);


ALTER TABLE public.proyecto OWNER TO almidondemo;

--
-- Name: agenda_idagenda_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE agenda_idagenda_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.agenda_idagenda_seq OWNER TO almidondemo;

--
-- Name: agenda_idagenda_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE agenda_idagenda_seq OWNED BY agenda.idagenda;


--
-- Name: agenda_idagenda_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('agenda_idagenda_seq', 2, true);


--
-- Name: doc_iddoc_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE doc_iddoc_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.doc_iddoc_seq OWNER TO almidondemo;

--
-- Name: doc_iddoc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE doc_iddoc_seq OWNED BY doc.iddoc;


--
-- Name: doc_iddoc_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('doc_iddoc_seq', 6, true);


--
-- Name: donante_iddonante_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE donante_iddonante_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.donante_iddonante_seq OWNER TO almidondemo;

--
-- Name: donante_iddonante_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE donante_iddonante_seq OWNED BY donante.iddonante;


--
-- Name: donante_iddonante_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('donante_iddonante_seq', 1, false);


--
-- Name: donanteproyecto_iddonanteproyecto_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE donanteproyecto_iddonanteproyecto_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.donanteproyecto_iddonanteproyecto_seq OWNER TO almidondemo;

--
-- Name: donanteproyecto_iddonanteproyecto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE donanteproyecto_iddonanteproyecto_seq OWNED BY donanteproyecto.iddonanteproyecto;


--
-- Name: donanteproyecto_iddonanteproyecto_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('donanteproyecto_iddonanteproyecto_seq', 1, false);


--
-- Name: enlace_idenlace_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE enlace_idenlace_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.enlace_idenlace_seq OWNER TO almidondemo;

--
-- Name: enlace_idenlace_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE enlace_idenlace_seq OWNED BY enlace.idenlace;


--
-- Name: enlace_idenlace_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('enlace_idenlace_seq', 2, true);


--
-- Name: foto_idfoto_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE foto_idfoto_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.foto_idfoto_seq OWNER TO almidondemo;

--
-- Name: foto_idfoto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE foto_idfoto_seq OWNED BY foto.idfoto;


--
-- Name: foto_idfoto_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('foto_idfoto_seq', 101, true);


--
-- Name: galeria_idgaleria_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE galeria_idgaleria_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.galeria_idgaleria_seq OWNER TO almidondemo;

--
-- Name: galeria_idgaleria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE galeria_idgaleria_seq OWNED BY galeria.idgaleria;


--
-- Name: galeria_idgaleria_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('galeria_idgaleria_seq', 2, true);


--
-- Name: identidad_ididentidad_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE identidad_ididentidad_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.identidad_ididentidad_seq OWNER TO almidondemo;

--
-- Name: identidad_ididentidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE identidad_ididentidad_seq OWNED BY pagina.idpagina;


--
-- Name: identidad_ididentidad_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('identidad_ididentidad_seq', 5, true);


--
-- Name: noticia_idnoticia_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE noticia_idnoticia_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.noticia_idnoticia_seq OWNER TO almidondemo;

--
-- Name: noticia_idnoticia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE noticia_idnoticia_seq OWNED BY noticia.idnoticia;


--
-- Name: noticia_idnoticia_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('noticia_idnoticia_seq', 2, true);


--
-- Name: programa_idprograma_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE programa_idprograma_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.programa_idprograma_seq OWNER TO almidondemo;

--
-- Name: programa_idprograma_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE programa_idprograma_seq OWNED BY programa.idprograma;


--
-- Name: programa_idprograma_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('programa_idprograma_seq', 5, true);


--
-- Name: proyecto_idproyecto_seq; Type: SEQUENCE; Schema: public; Owner: almidondemo
--

CREATE SEQUENCE proyecto_idproyecto_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.proyecto_idproyecto_seq OWNER TO almidondemo;

--
-- Name: proyecto_idproyecto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: almidondemo
--

ALTER SEQUENCE proyecto_idproyecto_seq OWNED BY proyecto.idproyecto;


--
-- Name: proyecto_idproyecto_seq; Type: SEQUENCE SET; Schema: public; Owner: almidondemo
--

SELECT pg_catalog.setval('proyecto_idproyecto_seq', 15, true);


--
-- Name: idagenda; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE agenda ALTER COLUMN idagenda SET DEFAULT nextval('agenda_idagenda_seq'::regclass);


--
-- Name: iddoc; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE doc ALTER COLUMN iddoc SET DEFAULT nextval('doc_iddoc_seq'::regclass);


--
-- Name: iddonante; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE donante ALTER COLUMN iddonante SET DEFAULT nextval('donante_iddonante_seq'::regclass);


--
-- Name: iddonanteproyecto; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE donanteproyecto ALTER COLUMN iddonanteproyecto SET DEFAULT nextval('donanteproyecto_iddonanteproyecto_seq'::regclass);


--
-- Name: idenlace; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE enlace ALTER COLUMN idenlace SET DEFAULT nextval('enlace_idenlace_seq'::regclass);


--
-- Name: idfoto; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE foto ALTER COLUMN idfoto SET DEFAULT nextval('foto_idfoto_seq'::regclass);


--
-- Name: idgaleria; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE galeria ALTER COLUMN idgaleria SET DEFAULT nextval('galeria_idgaleria_seq'::regclass);


--
-- Name: idnoticia; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE noticia ALTER COLUMN idnoticia SET DEFAULT nextval('noticia_idnoticia_seq'::regclass);


--
-- Name: idpagina; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE pagina ALTER COLUMN idpagina SET DEFAULT nextval('identidad_ididentidad_seq'::regclass);


--
-- Name: idprograma; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE programa ALTER COLUMN idprograma SET DEFAULT nextval('programa_idprograma_seq'::regclass);


--
-- Name: idproyecto; Type: DEFAULT; Schema: public; Owner: almidondemo
--

ALTER TABLE proyecto ALTER COLUMN idproyecto SET DEFAULT nextval('proyecto_idproyecto_seq'::regclass);


--
-- Data for Name: agenda; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY agenda (idagenda, agenda, fecha, lugar, texto, organiza) FROM stdin;
1	Quijote de la Mancha	2007-10-13	En un lugar de la Mancha	Es, pues, de saber, que este sobredicho hidalgo, los ratos que estaba ocioso (que eran los más del año) se daba a leer libros de caballerías con tanta afición y gusto, que olvidó casi de todo punto el ejercicio de la caza.	UCA IAS
2	Que trata de la primera salida	2007-10-13	Puerto Lapice	Estos pensamientos le hicieron titubear en su propósito; mas pudiendo más su locura que otra razón alguna, propuso de hacerse armar caballero del primero que topase, a imitación de otros muchos que así lo hicieron, según él había leído en los libros que tal le tenían. En lo de las armas blancas pensaba limpiarlas de manera, en teniendo lugar, que lo fuesen más que un armiño: y con esto se quietó y prosiguió su camino, sin llevar otro que el que su caballo quería, creyendo que en aquello consistía la fuerza de las aventuras. Yendo, pues, caminando nuestro flamante aventurero, iba hablando consigo mismo, y diciendo.	Wkipedia
\.


--
-- Data for Name: doc; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY doc (iddoc, doc, portada, descripcion, archivo) FROM stdin;
2	Memorias 2005			\N
3	Portada Memorias 2005			\N
4	Memorias 2002			\N
5	Memorias 2002			\N
6	Memorias 2002			\N
\.


--
-- Data for Name: donante; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY donante (iddonante, donante) FROM stdin;
\.


--
-- Data for Name: donanteproyecto; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY donanteproyecto (iddonanteproyecto, iddonante, idproyecto) FROM stdin;
\.


--
-- Data for Name: enlace; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY enlace (idenlace, enlace, url, texto, imagen) FROM stdin;
2	Google	http://www.google.com/		1207951914_logo.gif
1	Yahoo!	http://www.yahoo.com/		1207951982_y3.gif
\.


--
-- Data for Name: foto; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY foto (idfoto, idgaleria, foto, imagen) FROM stdin;
61	2	Ballons San Diego	balloons_2_bg_060504.jpg
62	2	Playa Ocaso	beach_3_bg_010503.jpg
63	2	Mar Big Sur	bigsur_28_bg_101203.jpg
64	2	Lago de Canda	canada_40_bg_061904.jpg
65	2	Zoom de chips de computadora	chips_3_bg_102602.jpg
66	2	Ciudad en Irlanda	ireland_37_bg_070504.jpg
67	2	Ciudad desconocida	roadtrip_23_bg_021604.jpg
68	2	Frutas en fondo oscuro	fruit_2_bg_020203.jpg
69	2	Granos de café	coffee_01_bg_031106.jpg
70	2	Treboles de Irlanda	ireland_102_bg_061602.jpg
71	1	Señalando el 'datagrid' con el dedo	1208475133_IMG_1813.JPG
72	1	Entendiendo Safari?	1208475451_IMG_1798.JPG
77	1	Leandro, Marconi, otros...	1208475793_IMG_1808.JPG
80	1	Javier, Melvin y Maribel	1208476743_IMG_1814.JPG
79	1	Christian y Mac	1208477323_IMG_1817.JPG
81	1	Donald, Alfredo, Leandro, Marconi...	1208477427_IMG_1816.JPG
98	\N		1212293501_01fsnsaberuh3.jpg
99	\N		1212293662_01fsnsaberuh3.jpg
100	\N		1212597370_4 junio 2008.gif
101	\N		1212768542_fire.jpg
\.


--
-- Data for Name: galeria; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY galeria (idgaleria, galeria, fecha) FROM stdin;
2	Galeria de ejemplos	\N
1	Primera reunión de almidón	2008-04-12
\.


--
-- Data for Name: noticia; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY noticia (idnoticia, noticia, fecha, texto, foto) FROM stdin;
1	Don Quijote Primera Parte Capítulo Dos	2007-10-13	Hechas, pues, estas prevenciones, no quiso aguardar más tiempo a poner en efecto su pensamiento, apretándole a ello la falta que él pensaba que hacía en el mundo su tardanza, según eran los agravios que pensaba deshacer, tuertos que enderezar, sinrazones que enmendar, y abusos que mejorar, y deudas que satisfacer; y así, sin dar parte a persona alguna de su intención, y sin que nadie le viese, una mañana, antes del día (que era uno de los calurosos del mes de Julio), se armó de todas sus armas, subió sobre Rocinante, puesta su mal compuesta celada, embrazó su adarga, tomó su lanza, y por la puerta falsa de un corral, salió al campo con grandísimo contento y alborozo de ver con cuánta facilidad había dado principio a su buen deseo. Mas apenas se vió en el campo, cuando le asaltó un pensamiento terrible, y tal, que por poco le hiciera dejar la comenzada empresa: y fue que le vino a la memoria que no era armado caballero, y que, conforme a la ley de caballería, ni podía ni debía tomar armas con ningún caballero; y puesto qeu lo fuera, había de llevar armas blancas, como novel caballero, sin empresa en el escudo, hasta que por su esfuerzo la ganase.	
2	Casi todo aquel día caminó sin acontecerle	2007-10-13	Autores hay que dicen que la primera aventura que le avino fue la de PuertoLápice; otros dicen que la de los molinos de viento; pero lo que yo he podido averiguar en este caso, y lo que he hallado escrito en los anales de la Mancha, es que él anduvo todo aquel día, y al anochecer, su rocín y él se hallaron cansados y muertos de hambre; y que mirando a todas partes, por ver si descubriría algún castillo o alguna majada de pastores donde recogerse, y adonde pudiese remediar su mucha necesidad, vió no lejos del camino por donde iba una venta, que fue como si viera una estrella, que a los portales, si no a los alcázares de su redención, le encaminaba. Dióse priesa a caminar, y llegó a ella a tiempo que anochecía. Estaban acaso a la puerta dos mujeres mozas, de estas que llaman del partido, las cuales iban a Sevilla con unos arrieros, que en la venta aquella noche acertaron a hacer jornada; y como a nuestro aventurero todo cuanto pensaba, veía o imaginaba, le parecía ser hecho y pasar al modo de lo que había leído, luego que vió la venta se le representó que era un castillo con sus cuatro torres y chapiteles de luciente plata, sin faltarle su puente levadizo y honda cava, con todos aquellos adherentes que semejantes castillos se pintan.\r\nFuese llegando a la venta (que a él le parecía castillo), y a poco trecho de ella detuvo las riendas a Rocinante, esperando que algún enano se pusiese entre las almenas a dar señal con alguna trompeta de que llegaba caballero al castillo; pero como vió que se tardaban, y que Rocinante se daba priesa por llegar a la caballeriza, se llegó a la puerta de la venta, y vió a las dos distraídas mozas que allí estaban, que a él le parecieron dos hermosas doncellas, o dos graciosas damas, que delante de la puerta del castillo se estaban solazando. En esto sucedió acaso que un porquero, que andaba recogiendo de unos rastrojos una manada de puercos (que sin perdón así se llaman), tocó un cuerno, a cuya señal ellos se recogen, y al instante se le representó a D. Quijote lo que deseaba, que era que algún enano hacía señal de su venida, y así con extraño contento llegó a la venta y a las damas, las cuales, como vieron venir un hombre de aquella suerte armado, y con lanza y adarga, llenas de miedo se iban a entrar en la venta; pero Don Quijote, coligiendo por su huida su miedo, alzándose la visera de papelón y descubriendo su seco y polvoso rostro, con gentil talante y voz reposada les dijo: non fuyan las vuestras mercedes, nin teman desaguisado alguno, ca a la órden de caballería que profeso non toca ni atañe facerle a ninguno, cuanto más a tan altas doncellas, como vuestras presencias demuestran.	
\.


--
-- Data for Name: pagina; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY pagina (idpagina, pagina, foto, descripcion) FROM stdin;
1	Qué es almidón?	1207951790_almidon-logo-by-AtmaComunicaciones.png	Almidón es una plataforma de desarrollo y hospedaje web desarrollado por Guegue∞, cuyo componente más popular es un sistema de manejo de contenido (CMS) que permite un desarrollo sólido de un sitio web, una administración sencilla, rápida, y un sitio web con buen desempeño. Actualmente en su mayoría escrito para Linux usando PHP, Apache y Postgresql, pero siendo probado y usado en distintas plataformas. Publicado bajo la licencia open source GPL v3, puede obtenerse en http://trac.almidon.org/
2	Qué ondas con almidón?		La cosa es ver qué hacemos con el? seguimos con php? dejamos smarty? mejoramos el aspecto gráfico del administrador? quiénes quieren participar? Estrategias para el desarrollo open source de almidón.
3	Reunión sobre...		Después de algunas platicas con algunos de ustedes, decidimos hacer una reunion para platicar del tema, la asistencia es abierta. y aunque hay mas o menos una agenda, me gustaria mantenerla abierta.
4	Licencia GPL		A inicio de año sacamos almidon como GPL, recién en abril lo documentamos mejor y lo hicimos instalable (a un dificil, pero ya se puede), ahora incluiremos un demo, para que no haya que desarrollar todo un sistema solo para ver como funciona.
5	Más información...		Wiki: http://almidon.org/\r\nTrac: http://trac.almidon.org/\r\nDemo: http://demo.almidon.org/
\.


--
-- Data for Name: programa; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY programa (idprograma, programa, foto, descripcion) FROM stdin;
4	Programa de organización, participación e incidencia		Su finalidad es facilitar procesos de desarrollo de los conocimientos, capacidades, valores individuales y colectivos en las comunidades y sectores sociales con los que se trabaja, para su participación organizada e incidencia en el ejercicio de la justicia social y el desarrollo humano en equidad. 
1	Programa de Salud		Objetivo general:\r\n\r\n“Establecer Redes de salud permanentes y sostenibles, social y económicamente, que promuevan y garanticen un sistema de salud eficiente para superar las condiciones de vulnerabilidad a la que está expuesta gran parte de la población en el país.” \r\n\r\nComponentes:\r\n•\tFomento de la Salud Comunitaria.\r\n•\tSuministro de Medicamentos Esenciales a través de Ventas Sociales de Medicamentos (VSM)\r\n•\tCapacitación a dispensadoras, personal médico, auxiliares de salud y responsables de centros que integran las redes de salud.\r\n\r\n•\tFomento y Organización de Redes Sociales de Salud a nivel comunitario, municipal y nacional.\r\n
2	Programa de Educación		Objetivo general:\r\n\r\n“Organizar y capacitar Núcleos Comunitarios y Redes Municipales que promuevan, coordinen, gestionen y administren todo lo referente a la educación, con la finalidad de elevar el nivel educativo de la población y contribuir a la reducción del analfabetismo para fomentar el desarrollo personal, familiar y comunitario.”\r\n\r\nComponentes:\r\n•\tFomento de la educación primaria formal.\r\n•\tEducación de Adultos (EDA).\r\n•\tEducación de Secundaria a Distancia.\r\n•\tCapacitación a Educadores.\r\n•\tOrganización de Redes de Educación\r\n
3	Programa de Vivienda e Infraestructura Social		Objetivo general:\r\n\r\n“Contribuir al acceso de vivienda digna por parte de la población de bajos ingresos, reduciendo la vulnerabilidad física y social, el hacinamiento familiar y generando  procesos de mejoramiento del hábitat.”\r\n\r\nComponentes:\r\n•\tConstrucción de viviendas en lotes urbanizados.\r\n•\tConstrucción de viviendas en comunidades rurales.\r\n•\tServicio de agua y saneamiento.\r\n•\tElectricidad y caminos.\r\n
5	Programa de Prevención, emergencia y reconstrucción (PREVER)		Objetivo general:\r\n\r\n“Contribuir a la reducción de la vulnerabilidad de la población fortaleciendo sus capacidades técnicas y organizativas para la prevención, atención a emergencias y la reconstrucción”.\r\n\r\nComponentes:\r\n\r\n•\tOrganización y desarrollo de capacidades de las estructuras locales del Sistema de Prevención, Mitigación y Atención de Desastres (SINAPRED): Municipios (COMUPRED) y comunidades rurales (COLOPRED)\r\n•\tEstablecimiento de Alianzas con otros actores locales y nacionales para la incidencia en la Gestión Local del Riesgo y atención a Emergencias (SINAPRED, diagnósticos de vulnerabilidades, establecimiento de estrategias conjuntas, complementariedad de recursos)\r\n•\tElaboración e implementación de Planes de Respuesta Locales a situaciones de emergencias (organización y equipamiento de brigadas locales de respuesta, sistemas de alerta temprana)\r\n•\tGestión de recursos para la atención a emergencias y rehabilitación de territorios afectados (Fondos Locales de Emergencias, Red de donantes privados, Redes Sociales de Solidaridad) \r\n•\tGestión y ejecución de proyectos de reconstrucción en los territorios afectados (construcción de viviendas, rehabilitación del sistema productivo, reactivación de servicios comunales)\r\n
\.


--
-- Data for Name: proyecto; Type: TABLE DATA; Schema: public; Owner: almidondemo
--

COPY proyecto (idproyecto, proyecto, monto, beneficiarios, descripcion, territorio, duracion, idgaleria) FROM stdin;
2	Proyecto de Desarrollo Humano Fase II (2006 - 2007)	706		Proyecto de Desarrollo Humano Fase II (2006 - 2007)		2006-2007	\N
3	Programa de Desarrollo Humano (Construcción de 17 viviendas en el Dulce Nombre de Jesús) (Ref.: NIC-045/7/S)	42				Junio de 2006 a Abril de 2007	\N
4	Mejora de la Calidad de Vida de la Población de 3 Comunidades Rurales del Municipio de Ciudad Darío (El Cristal, El Guineo y El Rincón de Sta. Teresita)	303				Junio de 2006  a Mayo 2008	\N
5	Fortalecimiento de la Gestión Local y Acceso a Servicios Básicos para el Desarrollo en Comunidades de Tipitapa y Ciudad Darío (FORGES)	38				Noviembre 2006 a Octubre 2007	\N
6	Sistemas de Agua en El Tempisque y Ojo de Agua	34				Septiembre a Noviembre de 2006	\N
7	Proyecto de Prevención, Emergencia y Reconstrucción (PREVER)	100				Julio de 2006 a Diciembre de 2	\N
8	Mejora de la Salud Comunitaria en 15 Comunidades Rurales de Nicaragua / Fase II (Ref.: NIC-54743)	131				Julio de 2006 - Diciembre de 2	\N
9	Pastoral Social de la Salud 	159				Abril de 2005 a Marzo de 2008	\N
10	Mejoramiento de los Niveles Educativos de la Población en 15 Comunidades ubicadas en las Comunidades de Villa El Carmen, Ciudad Darío y Tipitapa (Ref.: 06/NI/118) 	46				2007	\N
11	Ayuda para Talleres de Capacitación Pastoral impartidas a líderes comunitarios"	5				Mayo de 2006 a Abril del 2008	\N
12	Proyecto "SERVICIOS EDUCATIVOS EN RED" (SER)	156				Julio 2007 - Diciembre 2008	\N
13	Salud Comunitaria y Ampliación de las VSM (CONSALUD) (2007 - 2008)	74				Sept 2007 a Junio de 2008	\N
14	Construcción de 150 viviendas en Tipitapa 	392				Septiembre 2007 - Agosto 2008	\N
15	Economía de la Mujer y Desarrollo Socio-organizativo	69				2007	\N
\.


--
-- Name: agenda_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY agenda
    ADD CONSTRAINT agenda_pkey PRIMARY KEY (idagenda);


--
-- Name: doc_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY doc
    ADD CONSTRAINT doc_pkey PRIMARY KEY (iddoc);


--
-- Name: donante_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY donante
    ADD CONSTRAINT donante_pkey PRIMARY KEY (iddonante);


--
-- Name: donanteproyecto_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY donanteproyecto
    ADD CONSTRAINT donanteproyecto_pkey PRIMARY KEY (iddonanteproyecto);


--
-- Name: enlace_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY enlace
    ADD CONSTRAINT enlace_pkey PRIMARY KEY (idenlace);


--
-- Name: foto_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY foto
    ADD CONSTRAINT foto_pkey PRIMARY KEY (idfoto);


--
-- Name: galeria_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY galeria
    ADD CONSTRAINT galeria_pkey PRIMARY KEY (idgaleria);


--
-- Name: identidad_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY pagina
    ADD CONSTRAINT identidad_pkey PRIMARY KEY (idpagina);


--
-- Name: noticia_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY noticia
    ADD CONSTRAINT noticia_pkey PRIMARY KEY (idnoticia);


--
-- Name: programa_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY programa
    ADD CONSTRAINT programa_pkey PRIMARY KEY (idprograma);


--
-- Name: proyecto_pkey; Type: CONSTRAINT; Schema: public; Owner: almidondemo; Tablespace: 
--

ALTER TABLE ONLY proyecto
    ADD CONSTRAINT proyecto_pkey PRIMARY KEY (idproyecto);


--
-- Name: donanteproyecto_iddonante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: almidondemo
--

ALTER TABLE ONLY donanteproyecto
    ADD CONSTRAINT donanteproyecto_iddonante_fkey FOREIGN KEY (iddonante) REFERENCES donante(iddonante);


--
-- Name: donanteproyecto_idproyecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: almidondemo
--

ALTER TABLE ONLY donanteproyecto
    ADD CONSTRAINT donanteproyecto_idproyecto_fkey FOREIGN KEY (idproyecto) REFERENCES proyecto(idproyecto);


--
-- Name: foto_idgaleria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: almidondemo
--

ALTER TABLE ONLY foto
    ADD CONSTRAINT foto_idgaleria_fkey FOREIGN KEY (idgaleria) REFERENCES galeria(idgaleria);


--
-- Name: proyecto_idgaleria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: almidondemo
--

ALTER TABLE ONLY proyecto
    ADD CONSTRAINT proyecto_idgaleria_fkey FOREIGN KEY (idgaleria) REFERENCES galeria(idgaleria);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: agenda; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE agenda FROM PUBLIC;
REVOKE ALL ON TABLE agenda FROM almidondemo;
GRANT ALL ON TABLE agenda TO almidondemo;
GRANT SELECT ON TABLE agenda TO almidondemowww;


--
-- Name: doc; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE doc FROM PUBLIC;
REVOKE ALL ON TABLE doc FROM almidondemo;
GRANT ALL ON TABLE doc TO almidondemo;
GRANT SELECT ON TABLE doc TO almidondemowww;


--
-- Name: donante; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE donante FROM PUBLIC;
REVOKE ALL ON TABLE donante FROM almidondemo;
GRANT ALL ON TABLE donante TO almidondemo;
GRANT SELECT ON TABLE donante TO almidondemowww;


--
-- Name: donanteproyecto; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE donanteproyecto FROM PUBLIC;
REVOKE ALL ON TABLE donanteproyecto FROM almidondemo;
GRANT ALL ON TABLE donanteproyecto TO almidondemo;
GRANT SELECT ON TABLE donanteproyecto TO almidondemowww;


--
-- Name: enlace; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE enlace FROM PUBLIC;
REVOKE ALL ON TABLE enlace FROM almidondemo;
GRANT ALL ON TABLE enlace TO almidondemo;
GRANT SELECT ON TABLE enlace TO almidondemowww;


--
-- Name: foto; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE foto FROM PUBLIC;
REVOKE ALL ON TABLE foto FROM almidondemo;
GRANT ALL ON TABLE foto TO almidondemo;
GRANT SELECT ON TABLE foto TO almidondemowww;


--
-- Name: galeria; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE galeria FROM PUBLIC;
REVOKE ALL ON TABLE galeria FROM almidondemo;
GRANT ALL ON TABLE galeria TO almidondemo;
GRANT SELECT ON TABLE galeria TO almidondemowww;


--
-- Name: noticia; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE noticia FROM PUBLIC;
REVOKE ALL ON TABLE noticia FROM almidondemo;
GRANT ALL ON TABLE noticia TO almidondemo;
GRANT SELECT ON TABLE noticia TO almidondemowww;


--
-- Name: pagina; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE pagina FROM PUBLIC;
REVOKE ALL ON TABLE pagina FROM almidondemo;
GRANT ALL ON TABLE pagina TO almidondemo;
GRANT SELECT ON TABLE pagina TO almidondemowww;


--
-- Name: programa; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE programa FROM PUBLIC;
REVOKE ALL ON TABLE programa FROM almidondemo;
GRANT ALL ON TABLE programa TO almidondemo;
GRANT SELECT ON TABLE programa TO almidondemowww;


--
-- Name: proyecto; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON TABLE proyecto FROM PUBLIC;
REVOKE ALL ON TABLE proyecto FROM almidondemo;
GRANT ALL ON TABLE proyecto TO almidondemo;
GRANT SELECT ON TABLE proyecto TO almidondemowww;


--
-- Name: agenda_idagenda_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE agenda_idagenda_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE agenda_idagenda_seq FROM almidondemo;
GRANT ALL ON SEQUENCE agenda_idagenda_seq TO almidondemo;
GRANT SELECT ON SEQUENCE agenda_idagenda_seq TO almidondemowww;


--
-- Name: doc_iddoc_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE doc_iddoc_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE doc_iddoc_seq FROM almidondemo;
GRANT ALL ON SEQUENCE doc_iddoc_seq TO almidondemo;
GRANT SELECT ON SEQUENCE doc_iddoc_seq TO almidondemowww;


--
-- Name: donante_iddonante_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE donante_iddonante_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE donante_iddonante_seq FROM almidondemo;
GRANT ALL ON SEQUENCE donante_iddonante_seq TO almidondemo;
GRANT SELECT ON SEQUENCE donante_iddonante_seq TO almidondemowww;


--
-- Name: donanteproyecto_iddonanteproyecto_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE donanteproyecto_iddonanteproyecto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE donanteproyecto_iddonanteproyecto_seq FROM almidondemo;
GRANT ALL ON SEQUENCE donanteproyecto_iddonanteproyecto_seq TO almidondemo;
GRANT SELECT ON SEQUENCE donanteproyecto_iddonanteproyecto_seq TO almidondemowww;


--
-- Name: enlace_idenlace_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE enlace_idenlace_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE enlace_idenlace_seq FROM almidondemo;
GRANT ALL ON SEQUENCE enlace_idenlace_seq TO almidondemo;
GRANT SELECT ON SEQUENCE enlace_idenlace_seq TO almidondemowww;


--
-- Name: foto_idfoto_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE foto_idfoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE foto_idfoto_seq FROM almidondemo;
GRANT ALL ON SEQUENCE foto_idfoto_seq TO almidondemo;
GRANT SELECT ON SEQUENCE foto_idfoto_seq TO almidondemowww;


--
-- Name: galeria_idgaleria_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE galeria_idgaleria_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE galeria_idgaleria_seq FROM almidondemo;
GRANT ALL ON SEQUENCE galeria_idgaleria_seq TO almidondemo;
GRANT SELECT ON SEQUENCE galeria_idgaleria_seq TO almidondemowww;


--
-- Name: identidad_ididentidad_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE identidad_ididentidad_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE identidad_ididentidad_seq FROM almidondemo;
GRANT ALL ON SEQUENCE identidad_ididentidad_seq TO almidondemo;
GRANT SELECT ON SEQUENCE identidad_ididentidad_seq TO almidondemowww;


--
-- Name: noticia_idnoticia_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE noticia_idnoticia_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE noticia_idnoticia_seq FROM almidondemo;
GRANT ALL ON SEQUENCE noticia_idnoticia_seq TO almidondemo;
GRANT SELECT ON SEQUENCE noticia_idnoticia_seq TO almidondemowww;


--
-- Name: programa_idprograma_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE programa_idprograma_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE programa_idprograma_seq FROM almidondemo;
GRANT ALL ON SEQUENCE programa_idprograma_seq TO almidondemo;
GRANT SELECT ON SEQUENCE programa_idprograma_seq TO almidondemowww;


--
-- Name: proyecto_idproyecto_seq; Type: ACL; Schema: public; Owner: almidondemo
--

REVOKE ALL ON SEQUENCE proyecto_idproyecto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE proyecto_idproyecto_seq FROM almidondemo;
GRANT ALL ON SEQUENCE proyecto_idproyecto_seq TO almidondemo;
GRANT SELECT ON SEQUENCE proyecto_idproyecto_seq TO almidondemowww;


--
-- PostgreSQL database dump complete
--

