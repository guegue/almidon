DROP TABLE IF EXISTS alm_table CASCADE;
CREATE TABLE alm_table (idalm_table varchar(16) PRIMARY KEY, alm_table varchar(100), pkey varchar(32), orden varchar (100), rank int);
ALTER TABLE alm_table OWNER TO almidondemo;

DROP TABLE IF EXISTS alm_column CASCADE;
CREATE TABLE alm_column (idalm_column varchar (32), idalm_table varchar (32) REFERENCES alm_table, type varchar (16), size int, pk bool, fk varchar(16), alm_column varchar(100), extra varchar(500), rank int, PRIMARY KEY (idalm_column, idalm_table));
ALTER TABLE alm_column OWNER TO almidondemo;
