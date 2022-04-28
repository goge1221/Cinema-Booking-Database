

/* CREATE SEQUENCE */

CREATE SEQUENCE SEQ_KundeIn
START WITH 1
INCREMENT BY 1
CACHE 10;


/*CREATE TABLES*/

create table FILIALE
(
    FILIALEID    NUMBER generated by default on null as identity(start with 1)
        primary key,
    NAME         VARCHAR2(30) not null
        unique,
    ORT          VARCHAR2(20),
    STRASSENNAME VARCHAR2(20),
    STRASSENNR   NUMBER,
    PLZ          NUMBER
)
/

create table ANGESTELLTER
(
    SVNUMMER       NUMBER not null
        primary key,
    NAME           VARCHAR2(25),
    VORNAME        VARCHAR2(25),
    GEHALT         NUMBER,
    PLZ            NUMBER,
    ORT            VARCHAR2(20),
    STRASSE        VARCHAR2(30),
    STRASSENR      NUMBER,
    FILIALEID      NUMBER
        references FILIALE,
    FILIALLEITERID NUMBER
        references ANGESTELLTER
)
/

create table SAAL
(
    SAALNR              NUMBER not null,
    KAPAZITAET          NUMBER default 150,
    "Sonderausstattung" VARCHAR2(50),
    FILIALEID           NUMBER not null
        references FILIALE,
    primary key (SAALNR, FILIALEID)
)
/

CREATE TABLE Film
(
    Name            VARCHAR(30),
    Dauer           INT,
    Genre           VARCHAR(30),
    Ercheinungsjahr INT,
    PRIMARY KEY (Name),
    CHECK (Dauer > 0)
);
/

create table WIRD_ABGESPIELT
(
    FILIALE    NUMBER      not null,
    SAAL       NUMBER      not null,
    FILMNAME   VARCHAR2(30)
        references FILM
            on delete cascade,
    DATUM      DATE        not null,
    BEGINNZEIT VARCHAR2(7) not null,
    primary key (FILIALE, SAAL, DATUM, BEGINNZEIT),
    unique (FILMNAME, FILIALE, SAAL, DATUM, BEGINNZEIT),
    foreign key (SAAL, FILIALE) references SAAL
        on delete cascade
)
/

CREATE TABLE KundeIn
(
    KundenNr INT DEFAULT SEQ_KUNDEIN.nextval,
    Name     VARCHAR(20),
    KundenAlter    INT,
    PRIMARY KEY (KundenNr)
);
/

create table KAUFEN_TICKET
(
    FILM      VARCHAR2(30),
    FILIALEID NUMBER,
    SAAL      NUMBER,
    KUNDENNR  NUMBER      not null
        references KUNDEIN
            on delete cascade,
    DATUM     DATE        not null,
    ANFANG    VARCHAR2(7) not null,
    primary key (KUNDENNR, DATUM, ANFANG),
    foreign key (FILM, FILIALEID, SAAL, DATUM, ANFANG) references WIRD_ABGESPIELT (FILMNAME, FILIALE, SAAL, DATUM, BEGINNZEIT)
        on delete cascade
        deferrable
)
/

CREATE TABLE Kinder
(
    KundenNr  INT,
    EduCardNr INT,
    PRIMARY KEY (KundenNr, EduCardNr),
    FOREIGN KEY (KundenNr) REFERENCES KundeIn ON DELETE CASCADE
);
/

CREATE TABLE StudentIn
(
    KundenNr   INT,
    MatrikelNr INT,
    PRIMARY KEY (KundenNr, MatrikelNr),
    FOREIGN KEY (KundenNr) REFERENCES KundeIn ON DELETE CASCADE
);

CREATE TABLE Erwachsene
(
    KundenNr INT,
    SvNummer INT,
    PRIMARY KEY (KundenNr, SvNummer),
    FOREIGN KEY (KundenNr) REFERENCES KundeIn ON DELETE CASCADE
);


/* CREATE PROCEDURES */

create or replace PROCEDURE p_delete_kundein(
    p_person_id  IN  kundein.kundennr%TYPE,
    p_error_code OUT NUMBER
)
AS
BEGIN
    DELETE
    FROM kundein
    WHERE p_person_id = kundein.kundennr;

    p_error_code := SQL%ROWCOUNT;
    IF (p_error_code = 1)
    THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
EXCEPTION
    WHEN OTHERS
        THEN
            p_error_code := SQLCODE;
END p_delete_kundein;
/

create or replace PROCEDURE p_update_wird_abgespielt(
    p_filiale IN WIRD_ABGESPIELT.FILIALE%TYPE,
    p_saal IN WIRD_ABGESPIELT.SAAL%TYPE,
    p_filmname IN WIRD_ABGESPIELT.FILMNAME%TYPE,
    p_datum IN WIRD_ABGESPIELT.DATUM%TYPE,
    p_uhrzeit IN WIRD_ABGESPIELT.BEGINNZEIT%TYPE,
    p_neues_datum IN WIRD_ABGESPIELT.DATUM%TYPE,
    p_error_code OUT NUMBER
)
AS
BEGIN

    UPDATE WIRD_ABGESPIELT
    SET DATUM = p_neues_datum
    WHERE WIRD_ABGESPIELT.FILIALE = p_filiale
      AND
            WIRD_ABGESPIELT.SAAL = p_saal
      AND
            WIRD_ABGESPIELT.FILMNAME = p_filmname
      AND
            WIRD_ABGESPIELT.DATUM = p_datum
      AND
            WIRD_ABGESPIELT.BEGINNZEIT = p_uhrzeit;
    p_error_code := SQL%ROWCOUNT;
    UPDATE KAUFEN_TICKET
    SET DATUM = p_neues_datum
    WHERE KAUFEN_TICKET.FILIALEID = p_filiale
      AND
            KAUFEN_TICKET.SAAL = p_saal
      AND
            KAUFEN_TICKET.FILM = p_filmname
      AND
            KAUFEN_TICKET.DATUM = p_datum
      AND
            KAUFEN_TICKET.ANFANG = p_uhrzeit;

    IF (p_error_code = 1)
    THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
EXCEPTION
    WHEN OTHERS
        THEN
            p_error_code := SQLCODE;
END p_update_wird_abgespielt;
/




/* STANDARD INSERTS */

INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('UCI Kinowelt', 'Voesendorf', 'ScsStrasse', 10);
INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('Wien Kinowelt', 'Wien', 'Franz Strasse', 23);
INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('Cineplexx Vienna', 'Wien', 'Donau Strasse', 20);
INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('Cineplexx Graz', 'Graz', 'Mur Strasse', 124);
INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('Cineplexx Innsbruck', 'Innsbruck', 'Joseph Strasse', 56);
INSERT INTO FILIALE(NAME, ORT, STRASSENNAME, STRASSENNR) VALUES('Kino Linz', 'Linz', 'Linzer Strasse', 65);

INSERT INTO AngestellteR VALUES(1221522,'Johnny', 'Sins', 2500.35, 1180, 'Wien', 'Franz Wallner Strasse', 20, 1, 1221522);
INSERT INTO AngestellteR VALUES(1202524,'Marin', 'Kasper', 1511.95, 1010, 'Wien', 'Reumannplatz', 30, 1, 1221522);
INSERT INTO AngestellteR VALUES(2225142,'John', 'Inter', 1700.35, 1180, 'Wien', 'Wallnuss Strasse', 11, 1, 1221522);
INSERT INTO AngestellteR VALUES(5842185,'Paul', 'Sinister', 1900.00, 2154, 'Voesendorf', 'Johanness', 33, 2, 5842185);
INSERT INTO AngestellteR VALUES(1215235,'Joseph', 'Mark', 1950.39, 2234, 'Voesendorf', 'Frank', 20, 2, 5842185);
INSERT INTO AngestellteR VALUES(5545998,'Catrin', 'Miller', 1990.20, 2682, 'Voesendorf', 'Interraktiv', 97, 2, 5842185);
INSERT INTO AngestellteR VALUES(2125234,'Fischer', 'Joel', 1000.35, 8020, 'Graz', 'Bahn-strasse', 88, 3, 2125234);
INSERT INTO AngestellteR VALUES(2125472,'Mane', 'Sane', 1201.85, 8021, 'Graz', 'Geh-strasse', 94, 3, 2125234);
INSERT INTO AngestellteR VALUES(2564686,'Jack', 'Karl', 1800.99, 8023, 'Graz', 'Jon-strasse', 99, 3, 2125234);
INSERT INTO AngestellteR VALUES(2156658,'Maria', 'Helene', 1220.78, 4058, 'Innsbruck', 'Walt-strasse', 525, 4, 2156658);
INSERT INTO AngestellteR VALUES(1252356,'Johnny', 'Muller', 1200.90, 4059, 'Innsbruck', 'Josc-strasse', 929, 4, 2156658);
INSERT INTO AngestellteR VALUES(5451256,'Kurt', 'Cant', 1255.90, 4051, 'Innsbruck', 'Besc-strasse', 100, 4, 2156658);
INSERT INTO AngestellteR VALUES(1215322,'Marta', 'Okns', 2510.35, 1180, 'Wien', 'Franz Kepler Strasse', 30, 2, 5842185);
INSERT INTO AngestellteR VALUES(1235468,'Marin', 'Masper', 1911.95, 1010, 'Wien', 'Praterstraße', 55, 2, 5842185);
INSERT INTO AngestellteR VALUES(1235432,'Carl', 'Jean', 2354.85, 8021, 'Graz', 'Martin Luther Gasse', 10, 3, 2125234);
INSERT INTO AngestellteR VALUES(3215432,'Harlow', 'Andrew', 1254.99, 8023, 'Graz', 'Elf Strasse', 40, 3, 2125234);
INSERT INTO AngestellteR VALUES(2315231,'Maria', 'Popovici', 1700.78, 4058, 'Innsbruck', 'Man-strasse', 120, 4, 2156658);
INSERT INTO AngestellteR VALUES(3215114,'Martina', 'Muller', 1900.90, 4059, 'Innsbruck', 'Pos-strasse', 33, 4, 2156658);
INSERT INTO AngestellteR VALUES(2231123,'Franc', 'Sta', 2500.35, 5068, 'Innsbruck', 'Wallner Strasse', 20, 5, 2231123);
INSERT INTO AngestellteR VALUES(2131254,'Maria', 'Mary', 1511.95, 5586, 'Innsbruck', 'Marktplatz', 30, 5, 2231123);
INSERT INTO AngestellteR VALUES(3215664,'Mart', 'Inter', 1700.35, 5462, 'Innsbruck', 'Nuss Strasse', 11, 5, 2231123);
INSERT INTO AngestellteR VALUES(1658921,'Jensen', 'Karl', 2500.35, 5123, 'Innsbruck', 'Dorf Strasse', 20, 5, 2231123);
INSERT INTO AngestellteR VALUES(9842134,'Marin', 'Dude', 1511.95, 5321, 'Innsbruck', 'Casr Strasse', 30, 5, 2231123);
INSERT INTO AngestellteR VALUES(1232523,'Karts', 'And', 2500.35, 6642, 'Linz', 'Wallner Strasse', 20, 6, 1232523);
INSERT INTO AngestellteR VALUES(2132512,'Joseph', 'Karl', 1511.95, 6642, 'Linz', 'Marktplatz', 30, 6, 1232523);
INSERT INTO AngestellteR VALUES(3455561,'Marta', 'Marti', 1700.35, 6612, 'Linz', 'Nuss Strasse', 11, 6, 1232523);
INSERT INTO AngestellteR VALUES(1125435,'Jensen', 'Ackles', 2500.35, 6523, 'Linz', 'Dorf Strasse', 20, 6, 1232523);
INSERT INTO AngestellteR VALUES(6664213,'Jeniffer', 'Green', 1511.95, 6142, 'Linz', 'Casr Strasse', 30, 6, 1232523);

INSERT INTO SAAL VALUES(1, 300, 'IMAX', 1);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 1);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 1);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 1);
INSERT INTO SAAL VALUES(1, 300, 'IMAX', 2);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 2);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 2);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 2);
INSERT INTO SAAL VALUES(1, 300, 'IMAX', 3);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 3);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 3);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 3);
INSERT INTO SAAL VALUES(1, 300, 'IMAX', 4);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 4);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 4);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 4);
INSERT INTO SAAL VALUES(1, 300, 'IMAX', 5);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 5);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 5);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 5);
INSERT INTO SAAL VALUES(1, 300, 'IMAX', 6);
INSERT INTO SAAL VALUES(2, 200,'Laser Projection', 6);
INSERT INTO SAAL VALUES(3, 200, 'Surround Sound', 6);
INSERT INTO SAAL VALUES(4, 300, 'Real 3D', 6);


INSERT INTO FILM VALUES('Indiana Jones 1', 145, 'Adventure', 1990);
INSERT INTO FILM VALUES('Ant Man', 120, 'Hero', 2016);
INSERT INTO FILM VALUES('Matrix 1', 160, 'Thriller', 2001);
INSERT INTO FILM VALUES('Avengers: Endgame', 140, 'Action', 2018);
INSERT INTO FILM VALUES('Avengers: Infinity War', 162, 'Action', 2016);
INSERT INTO FILM VALUES('Black Widow', 122, 'Thriller', 2021);
INSERT INTO FILM VALUES('The Beach', 132, 'Horror', 2021);
INSERT INTO FILM VALUES('Spider Man: No way Home', 144, 'Hero', 2021);
INSERT INTO FILM VALUES('Iron man', 143, 'Hero', 2010);
INSERT INTO FILM VALUES('Men in black 1', 110, 'Comedy', 2005);
INSERT INTO FILM VALUES('The Godfather', 170, 'Mafia', 1976);
INSERT INTO FILM VALUES('Uncharted', 130, 'Adventure', 2022);
INSERT INTO FILM VALUES('Scream', 140, 'Horror', 2022);
INSERT INTO FILM VALUES('Knives Out', 122, 'Thriller', 2018);
INSERT INTO FILM VALUES('Captain America', 121, 'War', 2013);
INSERT INTO FILM VALUES('Dune', 155, 'Exploration', 2021);
INSERT INTO FILM VALUES('Venom', 110, 'Antihero', 2021);
INSERT INTO FILM VALUES('Free guy', 130, 'Comedy', 2021);
INSERT INTO FILM VALUES('Junge criuse', 144, 'Exploration', 2021);
INSERT INTO FILM VALUES('Matrix: Resurections', 2021, 'Thriller', 2022);


COMMIT;

select count(*) from KUNDEIN;
select count(*) from KAUFEN_TICKET;
select count(*) from WIRD_ABGESPIELT;