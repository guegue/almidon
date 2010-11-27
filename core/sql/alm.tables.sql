--
-- Codigo SQL fundamental de almidon, si no existen las tablas, deben crearse
-- Ejecutarse como el propietario de la BD
--

DROP TABLE IF EXISTS alm_role CASCADE;
CREATE TABLE alm_role (idalm_role varchar(8) PRIMARY KEY, alm_role varchar(100));

DROP TABLE IF EXISTS alm_user CASCADE;
CREATE TABLE alm_user (idalm_user varchar(16) PRIMARY KEY, idalm_role varchar(8) REFERENCES alm_role, password varchar(200) NOT NULL, alm_user varchar(200) NOT NULL, email varchar(200));

DROP TABLE IF EXISTS alm_table CASCADE;
CREATE TABLE alm_table (idalm_table varchar(48) PRIMARY KEY, alm_table varchar(100), pkey varchar(50), orden varchar (100), rank int, hidden bool, parent varchar(32), child varchar(200), restrictby varchar(50));

DROP TABLE IF EXISTS alm_column CASCADE;
CREATE TABLE alm_column (idalm_column varchar (50), idalm_table varchar (48) REFERENCES alm_table, type varchar (16), size int, pk bool, fk varchar(48), alm_column varchar(250), extra text, rank int, idalm_role varchar(8) REFERENCES alm_role, label_bool varchar(100), display varchar(200), help text, search bool, cdn bool, list_values varchar(500), PRIMARY KEY (idalm_column, idalm_table));

DROP TABLE IF EXISTS alm_access CASCADE;
CREATE TABLE alm_access (idalm_role varchar(8) REFERENCES alm_role NULL, idalm_user varchar(16) REFERENCES alm_user , idalm_table varchar(48) REFERENCES alm_table, idalm_access serial PRIMARY KEY);

-- especificamos id porque puede usarse 'hard-coded' en algun lado
INSERT INTO alm_role VALUES ('full', 'Control Total');
INSERT INTO alm_role VALUES ('edit', 'Edicion');
INSERT INTO alm_role VALUES ('delete', 'Correccion');
INSERT INTO alm_role VALUES ('read', 'Lectura');
INSERT INTO alm_role VALUES ('deny', 'Sin Accesso');

-- usuario admin - cambiar password!
INSERT INTO alm_user VALUES ('admin', 'full', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'admin@example.com');
