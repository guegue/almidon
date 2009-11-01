\connect almidondemo

CREATE TABLE almform (idalmform serial PRIMARY KEY, almform varchar(100));
ALTER TABLE public.almform OWNER TO almidondemo;

CREATE TABLE almrole (idalmrole serial PRIMARY KEY, almrole varchar(100));
ALTER TABLE public.almrole OWNER TO almidondemo;

CREATE TABLE almuser (idalmuser serial PRIMARY KEY,idalmrole int REFERENCES almrole, almuser varchar(100), password varchar(200) NOT NULL, name varchar(200) NOT NULL, email varchar(200));
ALTER TABLE public.almuser OWNER TO almidondemo;

CREATE TABLE almaccess (idalmrole int REFERENCES almrole NULL, idalmuser int REFERENCES almuser , idalmform int REFERENCES almform, idalmaccess serial PRIMARY KEY);
ALTER TABLE public.almaccess OWNER TO almidondemo;


INSERT INTO almrole VALUES (1, 'Control Total');
INSERT INTO almrole VALUES (2, 'Edicion');
INSERT INTO almrole VALUES (3, 'Correccion');
INSERT INTO almrole VALUES (4, 'Lectura');
INSERT INTO almrole VALUES (5, 'Sin Accesso');
INSERT INTO almuser VALUES (1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'admin@example.com');
INSERT INTO almuser VALUES (2, 4, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Demo', 'demo@example.com');
INSERT INTO almuser VALUES (3, NULL, 'alice', 'fe01ce2a7fbac8fafaed7c982a04e229', 'alice', 'alice@example.com');
INSERT INTO almform VALUES (1, 'almaccess');
INSERT INTO almform VALUES (2, 'almform');
INSERT INTO almform VALUES (3, 'almrole');
INSERT INTO almform VALUES (4, 'almuser');
INSERT INTO almform VALUES (5, 'pagina');
INSERT INTO almform VALUES (6, 'agenda');
INSERT INTO almform VALUES (7, 'doc');
INSERT INTO almform VALUES (8, 'enlace');
INSERT INTO almform VALUES (9, 'foto');
INSERT INTO almform VALUES (10, 'galeria');
INSERT INTO almform VALUES (11, 'noticia');
INSERT INTO almaccess VALUES (1, 3, 5, 1);


SELECT pg_catalog.setval('almuser_idalmuser_seq', 3, true);
SELECT pg_catalog.setval('almrole_idalmrole_seq', 5, true);
SELECT pg_catalog.setval('almform_idalmform_seq', 11, true);
SELECT pg_catalog.setval('almaccess_idalmaccess_seq', 1, true);
