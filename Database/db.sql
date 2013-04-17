DROP SCHEMA IF EXISTS TimeBank CASCADE;

CREATE SCHEMA TimeBank;

SET SEARCH_PATH TO TimeBank;

CREATE TABLE categoria (
id_categoria CHAR(3) PRIMARY KEY,
nome_cat VARCHAR (50)
);

CREATE TABLE comune (
codice_istat CHAR(6) PRIMARY KEY,
nome VARCHAR(50),
provincia CHAR(2),
regione CHAR(3),
prefisso VARCHAR(6),
cap CHAR(5),
cod_fisc CHAR(4),
abitanti INTEGER
);

COPY comune FROM 'C:/Users/Simone/Documents/GitHub/TimeBank/Database/listacomuni.txt';

CREATE TABLE cap AS SELECT DISTINCT cap AS cap_list FROM comune ORDER BY cap_list ASC;

ALTER TABLE cap ADD UNIQUE(cap_list);


CREATE TABLE province AS SELECT DISTINCT provincia AS provincia_list FROM comune ORDER BY provincia_list ASC;

ALTER TABLE province ADD UNIQUE(provincia_list);

CREATE TABLE utente (
username VARCHAR (50) PRIMARY KEY,
password VARCHAR(200),
email VARCHAR(100),
ore_disponibilita INTEGER,
ore_lavorate INTEGER,
ore_richieste INTEGER,
ore_ricevute INTEGER,
indirizzo VARCHAR(100),
cap CHAR(5) REFERENCES cap(cap_list),
citta CHAR(6) REFERENCES comune(codice_istat),
provincia CHAR(2) REFERENCES province(provincia_list)
);

SET DATESTYLE TO European;

CREATE TABLE annuncio (
id_annuncio SERIAL PRIMARY KEY,
data DATE,
richiesto BOOLEAN DEFAULT FALSE,
descrizione TEXT,
richiedente VARCHAR(50) REFERENCES utente(username) DEFAULT NULL,
creatore VARCHAR(50) REFERENCES utente(username), /*In Java controllare che il richiedente non sia anche il cratore */
categoria CHAR(3) REFERENCES categoria(id_categoria)
);
