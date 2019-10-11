CREATE DATABASE cinema
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Russian_Russia.1251'
    LC_CTYPE = 'Russian_Russia.1251'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;


CREATE TABLE public.clients
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    name character(10) COLLATE pg_catalog."default",
    CONSTRAINT clients_pkey PRIMARY KEY (id)
)
CREATE TABLE public.days
(
    id integer NOT NULL GENERATED BY DEFAULT AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    date date,
    CONSTRAINT days_pkey PRIMARY KEY (id)
)
CREATE TABLE public.film
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    name character(10) COLLATE pg_catalog."default",
    CONSTRAINT film_pkey PRIMARY KEY (id)
)
CREATE TABLE public.hall
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    name character(10) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT hall_pkey PRIMARY KEY (id)
)

CREATE TABLE public.price
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 1111111111 CACHE 1 ),
    price numeric(8,0) NOT NULL,
    CONSTRAINT price_pkey PRIMARY KEY (id)
)
CREATE TABLE public.seance
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    "time" time(6) without time zone NOT NULL,
    CONSTRAINT seance_pkey PRIMARY KEY (id)
)

CREATE TABLE public.tickets
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    id_seance integer,
    id_hall integer,
    id_films integer,
    id_price integer,
    id_day integer,
    CONSTRAINT tickets_pkey PRIMARY KEY (id),
    CONSTRAINT "Foregn key_4" FOREIGN KEY (id_price)
        REFERENCES public.price (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT "Foregn key_5" FOREIGN KEY (id_day)
        REFERENCES public.days (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT "Foreign key_1" FOREIGN KEY (id_seance)
        REFERENCES public.seance (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE
        NOT VALID,
    CONSTRAINT "Foreign key_2" FOREIGN KEY (id_hall)
        REFERENCES public.hall (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE
        NOT VALID,
    CONSTRAINT "Foreign key_3" FOREIGN KEY (id_films)
        REFERENCES public.film (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE
        NOT VALID
)