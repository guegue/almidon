DROP DATABASE IF EXISTS almidondemo;
CREATE DATABASE almidondemo WITH TEMPLATE = template0 ENCODING = 'UTF8';
CREATE USER almidondemo WITH PASSWORD 'secreto1';
CREATE USER almidondemowww WITH PASSWORD 'secreto2';
ALTER DATABASE almidondemo OWNER TO almidondemo;
