DROP SCHEMA IF EXISTS TimeBank CASCADE;

CREATE SCHEMA TimeBank;

SET SEARCH_PATH TO TimeBank;

CREATE TABLE categoria (
id_categoria SERIAL PRIMARY KEY,
nome_cat VARCHAR (50)
);
INSERT INTO categoria VALUES ( 100,'Senza Categoria');
COPY categoria(nome_cat) FROM 'C:/categorieLavoro.txt';


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

COPY comune FROM 'C:/listacomuni.txt';

CREATE TABLE cap AS SELECT DISTINCT cap AS cap_list FROM comune ORDER BY cap_list ASC;

ALTER TABLE cap ADD UNIQUE(cap_list);


CREATE TABLE province AS SELECT DISTINCT provincia AS provincia_list FROM comune ORDER BY provincia_list ASC;

ALTER TABLE province ADD UNIQUE(provincia_list);

CREATE TABLE utente (
username VARCHAR (50) PRIMARY KEY,
password VARCHAR(200),
email VARCHAR(100),
ore_disponibilita INTEGER DEFAULT 0,
ore_lavorate INTEGER DEFAULT 0,
ore_richieste INTEGER DEFAULT 0,
ore_ricevute INTEGER DEFAULT 0,
indirizzo VARCHAR(100),
cap CHAR(5) REFERENCES cap(cap_list),
citta CHAR(6) REFERENCES comune(codice_istat), /*Riferisce il codice istat, non il nome del comune*/
provincia CHAR(2) REFERENCES province(provincia_list),
admin BOOLEAN DEFAULT FALSE
);

COPY utente FROM 'C:/utenti.txt';/*Solo per testing*/

SET DATESTYLE TO European;

CREATE TABLE annuncio (
id_annuncio SERIAL PRIMARY KEY,
data_inserimento VARCHAR(20),
data_annuncio VARCHAR(20),
richiesto BOOLEAN DEFAULT FALSE,
descrizione TEXT,
richiedente VARCHAR(50) default NULL REFERENCES utente(username) on DELETE SET DEFAULT ON UPDATE SET DEFAULT,
creatore VARCHAR(50) REFERENCES utente(username) ON DELETE CASCADE ON UPDATE CASCADE, /*In Java controllare che il richiedente non sia anche il cratore */
categoria INTEGER default 100 REFERENCES categoria(id_categoria)  ON DELETE SET default /*100 è l'id della categoria "Senza Categoria", serve quando una categoria viene eliminata*/
);

COPY annuncio FROM 'C:/annunci.txt';/*Solo per testing*/

/*Questo trigger risolve il problema che si creava quando si cancella un utente che ha richiesto almeno un annuncio. In particolare il 
corrispondente campo "richiedente" su annuncio viene correttametne impostato a NULL grazie alla clausola ON DELETE SET DEFAULT 
ma il campo "richiesto" rimane sempre a TRUE.

Ora invece ogni volta che il "richiedente" viene impostato a NULL viene anche impostato a false il campo "richiesto"*/
CREATE OR REPLACE FUNCTION nonRichiesto() RETURNS TRIGGER AS
	$BODY$
	DECLARE 
		BEGIN		
		IF NEW.richiedente IS NULL THEN
			NEW.richiesto:=false;
		END IF;
		RETURN NEW;
	END;
	$BODY$
	LANGUAGE PLPGSQL;

	CREATE TRIGGER nonRichiesto
	BEFORE UPDATE
	ON annuncio
	FOR EACH ROW
	EXECUTE PROCEDURE nonRichiesto();
