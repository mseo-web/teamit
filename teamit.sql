--
-- PostgreSQL database dump
--

-- Dumped from database version 15.2 (Debian 15.2-1.pgdg110+1)
-- Dumped by pg_dump version 15.13

-- Started on 2025-07-24 06:21:23 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 214 (class 1259 OID 16389)
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: teamit_user
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO teamit_user;

--
-- TOC entry 216 (class 1259 OID 16396)
-- Name: task; Type: TABLE; Schema: public; Owner: teamit_user
--

CREATE TABLE public.task (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    status character varying(255) NOT NULL
);


ALTER TABLE public.task OWNER TO teamit_user;

--
-- TOC entry 215 (class 1259 OID 16395)
-- Name: task_id_seq; Type: SEQUENCE; Schema: public; Owner: teamit_user
--

CREATE SEQUENCE public.task_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.task_id_seq OWNER TO teamit_user;

--
-- TOC entry 3327 (class 0 OID 16389)
-- Dependencies: 214
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: teamit_user
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20250723131028	2025-07-24 05:45:32	80
\.


--
-- TOC entry 3329 (class 0 OID 16396)
-- Dependencies: 216
-- Data for Name: task; Type: TABLE DATA; Schema: public; Owner: teamit_user
--

COPY public.task (id, name, description, status) FROM stdin;
1	Ratione excepturi vel.	Architecto ut officiis possimus velit et voluptas. Ipsum optio officiis quia quia dolore commodi.	новая
2	Et ex vel aut hic.	Perferendis nihil occaecati voluptatem eveniet consequatur. Earum quia fugiat sunt ut labore aliquid illo. Sit sed quia aliquam architecto quis velit.	новая
3	Consequatur ipsam modi explicabo.	Minima veritatis ipsa et eum animi quis. Error quam et consequatur animi dolor voluptate.	завершена
4	Non suscipit doloremque.	Deleniti nesciunt beatae dignissimos dolorum. Accusamus aut atque quidem magni ipsum ea. Deserunt vel optio necessitatibus laborum at ipsum molestiae.	в процессе
5	Vel optio voluptatum ut.	Et est sed et. Quisquam voluptatibus vero harum incidunt minus accusantium illo.	завершена
6	Est ratione placeat illum.	Repellendus quam laudantium dolore aspernatur accusamus quod recusandae mollitia. Et eligendi sint aut voluptatem voluptates placeat.	отложена
7	Minima qui repudiandae.	Sed soluta numquam ea. Consequatur eos enim perferendis corrupti magni cum modi dolore. Non quo maxime dolor omnis ut eos rerum.	отложена
8	Ea qui ea.	Non est suscipit et perferendis aperiam earum reiciendis. Culpa culpa id est magni quo.	новая
9	Saepe eligendi dignissimos eveniet.	Est soluta nisi sit deleniti et. Maxime cumque illum impedit unde. Maiores distinctio earum quae rerum natus facilis.	новая
10	Temporibus nisi sed.	Vel provident ducimus corporis quis ut. Autem ipsum aut ut et aut quo et.	завершена
11	Molestiae eos consequuntur consectetur.	Omnis consequatur dolorem accusantium voluptatem expedita. Rem architecto dicta dolor magni incidunt sint perspiciatis.	в процессе
12	Voluptas ea.	Modi qui nulla commodi quidem. Repellendus odit velit vel enim sed. Velit impedit corporis possimus modi rerum.	в процессе
13	Corrupti est neque cumque.	Aut est voluptatem aliquid accusamus sint. Autem vitae qui odit.	отложена
14	Maxime est labore.	Repellendus officiis sunt autem aspernatur sed. Eos harum eum sed sed.	новая
15	Omnis dolor aut molestiae.	Omnis voluptatibus aspernatur non quia qui. Quidem perspiciatis tempore nihil odit aut quod magni. Blanditiis distinctio nemo necessitatibus magnam qui minima.	завершена
16	Omnis laboriosam veritatis.	Omnis ipsa qui nam numquam inventore. Qui enim quidem cupiditate et architecto qui culpa.	завершена
17	Illo cumque provident nobis.	Repellendus possimus sint similique est maiores consequatur. Cupiditate et tempora ullam.	отложена
18	Possimus cum commodi.	Qui officiis dolores mollitia eos sit ullam. Aspernatur vero alias quis ad. Error voluptates quia soluta dolor.	завершена
19	Et ipsa nihil fugit.	Ea voluptatibus aut sit tempore asperiores earum. Esse ut odit eaque officia quia aut.	в процессе
20	Et repellendus architecto.	Nam deleniti nisi fuga sed. Voluptatibus enim quos aspernatur voluptatem reprehenderit.	отложена
\.


--
-- TOC entry 3335 (class 0 OID 0)
-- Dependencies: 215
-- Name: task_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teamit_user
--

SELECT pg_catalog.setval('public.task_id_seq', 20, true);


--
-- TOC entry 3182 (class 2606 OID 16394)
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: teamit_user
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- TOC entry 3184 (class 2606 OID 16402)
-- Name: task task_pkey; Type: CONSTRAINT; Schema: public; Owner: teamit_user
--

ALTER TABLE ONLY public.task
    ADD CONSTRAINT task_pkey PRIMARY KEY (id);


-- Completed on 2025-07-24 06:21:23 UTC

--
-- PostgreSQL database dump complete
--

