--
-- PostgreSQL database dump
--

\restrict koFxMVCwanpb0sEeBFT71TgHUi2xPHDya7gNrhWWSJs5q4YTiBhoBF7Ne8EjxeM

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: absences; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.absences (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id uuid NOT NULL,
    type character varying(50) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    duration integer NOT NULL,
    reason text,
    status character varying(20) DEFAULT 'en_attente'::character varying NOT NULL,
    justification character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT absence_status_check CHECK (((status)::text = ANY ((ARRAY['en_attente'::character varying, 'approuve'::character varying, 'refuse'::character varying])::text[])))
);



--
-- Name: admin_schedules; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_schedules (
    id integer NOT NULL,
    mon_start time without time zone,
    mon_end time without time zone,
    tue_start time without time zone,
    tue_end time without time zone,
    wed_start time without time zone,
    wed_end time without time zone,
    thu_start time without time zone,
    thu_end time without time zone,
    fri_start time without time zone,
    fri_end time without time zone
);



--
-- Name: admin_schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.admin_schedules_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: admin_schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.admin_schedules_id_seq OWNED BY public.admin_schedules.id;


--
-- Name: attendances; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.attendances (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id uuid,
    date date NOT NULL,
    check_in time without time zone,
    check_out time without time zone,
    status character varying(20),
    note text,
    CONSTRAINT attendance_status_check CHECK (((status)::text = ANY ((ARRAY['present'::character varying, 'retard'::character varying, 'absent'::character varying])::text[])))
);



--
-- Name: audit_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.audit_logs (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id uuid,
    action character varying(100),
    entity character varying(100),
    entity_id uuid,
    ip character varying(50),
    created_at timestamp without time zone DEFAULT now()
);



--
-- Name: cohort_schedules; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cohort_schedules (
    id integer NOT NULL,
    cohort_id integer NOT NULL,
    day character varying(20) NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL
);



--
-- Name: cohort_schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cohort_schedules_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: cohort_schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cohort_schedules_id_seq OWNED BY public.cohort_schedules.id;


--
-- Name: cohorts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cohorts (
    id integer NOT NULL,
    department_id integer NOT NULL,
    name character varying(50) NOT NULL,
    status character varying(20) DEFAULT 'active'::character varying
);



--
-- Name: cohorts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cohorts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: cohorts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cohorts_id_seq OWNED BY public.cohorts.id;


--
-- Name: company_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.company_settings (
    id integer NOT NULL,
    company_name character varying(150),
    company_email character varying(150)
);



--
-- Name: company_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.company_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: company_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.company_settings_id_seq OWNED BY public.company_settings.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.departments (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description text
);



--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.departments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: etudiants; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etudiants (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    matricule character varying(20) NOT NULL,
    firstname character varying(100) NOT NULL,
    lastname character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    phone character varying(30),
    department character varying(100),
    cohort character varying(20),
    status character varying(20) DEFAULT 'Actif'::character varying,
    photo text,
    created_at timestamp without time zone DEFAULT now(),
    CONSTRAINT cohort_check CHECK (((cohort)::text = ANY ((ARRAY['Cohorte 1'::character varying, 'Cohorte 2'::character varying, 'Cohorte 3'::character varying, 'Cohorte 4'::character varying])::text[]))),
    CONSTRAINT status_check CHECK (((status)::text = ANY ((ARRAY['Actif'::character varying, 'Inactif'::character varying])::text[])))
);



--
-- Name: public_holidays; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.public_holidays (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    holiday_name character varying(100) NOT NULL,
    holiday_date date NOT NULL,
    holiday_type character varying(50) NOT NULL,
    status character varying(20) DEFAULT 'avenir'::character varying,
    description text,
    CONSTRAINT holiday_status_check CHECK (((status)::text = ANY ((ARRAY['passe'::character varying, 'avenir'::character varying])::text[])))
);



--
-- Name: qr_codes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.qr_codes (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id uuid,
    token uuid NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);



--
-- Name: school_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.school_settings (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    school_lat double precision,
    school_lng double precision,
    radius integer DEFAULT 100,
    gps_enabled boolean DEFAULT true
);



--
-- Name: settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.settings (
    id integer NOT NULL,
    school_lat double precision,
    school_lng double precision,
    radius integer,
    gps_enabled boolean DEFAULT true
);



--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    firstname character varying(100) NOT NULL,
    lastname character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    password_hash text,
    role character varying(20) NOT NULL,
    "position" character varying(100),
    phone character varying(30),
    photo text,
    is_active boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT now(),
    activation_token character varying(255),
    is_verified boolean DEFAULT false,
    cohort_id integer,
    department_id integer,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'etudiant'::character varying])::text[])))
);



--
-- Name: admin_schedules id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_schedules ALTER COLUMN id SET DEFAULT nextval('public.admin_schedules_id_seq'::regclass);


--
-- Name: cohort_schedules id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohort_schedules ALTER COLUMN id SET DEFAULT nextval('public.cohort_schedules_id_seq'::regclass);


--
-- Name: cohorts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohorts ALTER COLUMN id SET DEFAULT nextval('public.cohorts_id_seq'::regclass);


--
-- Name: company_settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.company_settings ALTER COLUMN id SET DEFAULT nextval('public.company_settings_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);


--
-- Data for Name: absences; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.absences (id, user_id, type, start_date, end_date, duration, reason, status, justification, created_at, updated_at) FROM stdin;
dec2934d-56a2-4d28-94f5-f424bfbfe248	88d08773-ffec-4fe3-8b3f-6529106008f7	Congé maternité	1995-10-30	1973-07-14	56	Quis consequatur ip	en_attente	\N	2026-07-04 20:44:27.517657	2026-07-04 20:44:27.517657
a42ed82f-a888-460d-9cf3-7c91f0bb3cf0	88d08773-ffec-4fe3-8b3f-6529106008f7	Congé maladie	2026-07-05	2026-07-07	2	Rendez-vous hôpital 	approuve	\N	2026-07-05 14:41:59.692438	2026-07-05 14:42:18.916132
e3a1f877-b272-41ea-a957-35faa68e823f	88d08773-ffec-4fe3-8b3f-6529106008f7	Congé maladie	2026-07-13	2026-07-17	4	rendez-vous à l'hôpital 	refuse	\N	2026-07-11 21:42:19.962253	2026-07-11 21:44:23.344906
\.


--
-- Data for Name: admin_schedules; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.admin_schedules (id, mon_start, mon_end, tue_start, tue_end, wed_start, wed_end, thu_start, thu_end, fri_start, fri_end) FROM stdin;
1	08:00:00	17:00:00	08:00:00	17:00:00	08:00:00	17:00:00	08:00:00	17:00:00	08:00:00	17:00:00
\.


--
-- Data for Name: attendances; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.attendances (id, user_id, date, check_in, check_out, status, note) FROM stdin;
6dca0410-bad6-44f1-9fd0-9c2070f81fb0	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-06-29	14:04:23	\N	present	\N
e92d4162-347a-4abf-89f2-357ef89742c5	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-06-30	14:45:09	\N	present	\N
42388435-11a9-45fb-9864-84f286ce7460	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-07-01	15:44:07	\N	present	\N
8c34d846-3aaa-4bfd-9eaf-8867286e1bbd	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-07-02	13:56:24	14:25:43	present	\N
01d98532-0d52-4998-8ee6-e88b24ddc551	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-07-08	11:38:37	12:06:24	retard	\N
29a2467c-0a7a-4e09-bcc9-57ccc410c6b2	df8d6bed-5379-4306-9fe5-51660dffe55c	2026-07-08	15:44:07	\N	retard	\N
ba6df766-9608-4619-a634-821edf1d4d5f	df8d6bed-5379-4306-9fe5-51660dffe55c	2026-07-09	16:23:12	\N	present	\N
822587db-d202-429c-8bd0-36f5ac2fd4a6	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-07-09	16:26:15	\N	present	\N
0902ad36-ebba-4a56-94e1-3a53ef16a156	9d2825db-6c75-4e34-b9bf-5b585ddb876a	2026-07-13	12:21:33	\N	retard	\N
730a25fd-e542-428d-bbb0-6499017d83aa	88d08773-ffec-4fe3-8b3f-6529106008f7	2026-07-13	10:52:07	\N	present	\N
1c0ec9ba-ead8-467d-bd20-1e473c4a1be2	17c5faa1-ee4f-4194-9e23-3487485483ef	2026-07-13	14:16:14	\N	retard	\N
\.


--
-- Data for Name: audit_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.audit_logs (id, user_id, action, entity, entity_id, ip, created_at) FROM stdin;
7fc198a6-7253-4115-a1e5-0cc7b37e2d2f	d9346d78-471a-44b4-9868-5cbe27982d07	CREATE	users	b1b090a4-3c22-46f7-9c28-3b53feead54a	127.0.0.1	2026-07-08 12:26:18.986111
a4dcd273-b13e-4ef5-9754-1c2012de8ac0	d9346d78-471a-44b4-9868-5cbe27982d07	CREATE	users	f56c433a-a002-467d-8565-d827730a5b75	127.0.0.1	2026-07-08 13:36:44.917134
da790f50-5789-4fbf-a60a-0f933d9c01fb	d9346d78-471a-44b4-9868-5cbe27982d07	UPDATE	users	f56c433a-a002-467d-8565-d827730a5b75	127.0.0.1	2026-07-08 14:34:49.323964
2cdf7897-2638-4d05-b250-631b7527e4d9	f56c433a-a002-467d-8565-d827730a5b75	DELETE	users	0fa552cc-21e1-4ec0-9664-d5e239497a55	127.0.0.1	2026-07-08 17:05:33.718631
854c7082-59c8-45d9-a7da-1f577be8123f	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	9d2825db-6c75-4e34-b9bf-5b585ddb876a	127.0.0.1	2026-07-08 17:06:06.807646
0c29e773-621f-41cf-9234-5538deb8fe1b	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	9d2825db-6c75-4e34-b9bf-5b585ddb876a	127.0.0.1	2026-07-09 11:39:41.703895
a6249c06-1d91-4f9f-9725-bb52384d827d	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	d63061d0-f3ae-4f63-8f60-88ceda3219e8	127.0.0.1	2026-07-09 12:08:59.448898
138d6de3-3f36-4f12-9b0e-039b006f485d	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	9d2825db-6c75-4e34-b9bf-5b585ddb876a	127.0.0.1	2026-07-09 12:42:10.857989
26d130d2-c406-4420-aa56-3da645371f49	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	9d2825db-6c75-4e34-b9bf-5b585ddb876a	127.0.0.1	2026-07-09 14:39:00.478157
330bd869-8c6f-4b0e-866a-ac2acc307982	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	17c5faa1-ee4f-4194-9e23-3487485483ef	127.0.0.1	2026-07-09 18:59:41.619307
52319e60-50eb-4734-8ecd-a3cc0d8d6989	88d08773-ffec-4fe3-8b3f-6529106008f7	CREATE	absences	e3a1f877-b272-41ea-a957-35faa68e823f	127.0.0.1	2026-07-11 21:42:19.992964
004bf985-fd92-475b-b442-7846ce0ece2d	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	absences	e3a1f877-b272-41ea-a957-35faa68e823f	127.0.0.1	2026-07-11 21:44:23.355238
539b452c-7ad0-49ec-8045-6fdef4331ba5	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	d9346d78-471a-44b4-9868-5cbe27982d07	127.0.0.1	2026-07-12 21:01:42.894547
8abce9e8-3d0c-4a20-839a-894f34d8979e	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	4c30b6a9-8b99-4b26-9616-30ddea5154c6	127.0.0.1	2026-07-13 13:26:24.89787
b8f747c3-53e1-43c7-996f-f9c9efff12b2	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	4c30b6a9-8b99-4b26-9616-30ddea5154c6	127.0.0.1	2026-07-13 13:27:16.401459
6118377d-0cc3-4a22-9792-1e80063bb6d9	f56c433a-a002-467d-8565-d827730a5b75	UPDATE	users	4c30b6a9-8b99-4b26-9616-30ddea5154c6	127.0.0.1	2026-07-13 13:31:36.560476
f05c13f4-f40e-4320-b86b-b200e0c0aaec	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	668c63ba-1cb1-4230-8469-49a1b3882158	127.0.0.1	2026-07-13 13:33:25.601397
d3a92dd9-360d-4f29-bd0a-64e869ce8a5e	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	0ae25464-e4c5-4986-abc5-f6a331f6ec04	127.0.0.1	2026-07-13 14:17:39.444052
b414648c-845d-4f45-bd09-8fd8e5d63901	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	e415ee4a-416d-416e-ab59-529b98f3b5eb	127.0.0.1	2026-07-13 14:17:39.547361
0deea820-c76a-4187-b82a-89eda7d2dd87	f56c433a-a002-467d-8565-d827730a5b75	CREATE	users	484c7ede-c4f1-47c2-a049-3477b3300bfd	127.0.0.1	2026-07-13 14:17:42.618055
\.


--
-- Data for Name: cohort_schedules; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cohort_schedules (id, cohort_id, day, start_time, end_time) FROM stdin;
7	15	Lundi	09:00:00	13:00:00
8	15	Mercredi	09:00:00	13:00:00
9	15	Jeudi	13:00:00	17:00:00
10	16	Mardi	09:00:00	13:00:00
11	16	Vendredi	09:00:00	14:00:00
12	16	Samedi	12:00:00	15:00:00
16	18	Lundi	09:00:00	13:00:00
17	18	Mardi	11:00:00	14:00:00
18	18	Vendredi	09:00:00	13:00:00
19	19	Lundi	13:00:00	17:00:00
20	19	Mercredi	13:00:00	16:00:00
21	19	Samedi	11:00:00	17:00:00
22	20	Lundi	09:00:00	13:00:00
23	20	Mardi	09:00:00	11:00:00
24	20	Mercredi	10:00:00	15:00:00
25	20	Jeudi	13:00:00	18:00:00
26	21	Mardi	13:00:00	18:00:00
27	21	Vendredi	11:00:00	15:00:00
28	21	Samedi	09:00:00	12:00:00
29	22	Lundi	09:00:00	17:00:00
30	22	Mardi	09:00:00	16:00:00
31	22	Jeudi	11:00:00	18:00:00
32	22	Samedi	09:00:00	18:00:00
41	17	Lundi	10:00:00	13:30:00
42	17	Mardi	09:00:00	17:00:00
43	17	Samedi	10:30:00	15:00:00
\.


--
-- Data for Name: cohorts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cohorts (id, department_id, name, status) FROM stdin;
15	1	Cohorte 1	active
16	1	Cohorte 2	active
18	4	Cohorte 1	active
19	4	Cohorte 2	active
20	5	Cohorte 1	active
21	5	Cohorte 2	active
22	2	Cohorte 1	active
17	6	Cohorte 1	active
\.


--
-- Data for Name: company_settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.company_settings (id, company_name, company_email) FROM stdin;
1	Telly Tech	contact@telly.sn
\.


--
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.departments (id, name, description) FROM stdin;
1	Développement Web	
2	Marketing Digital	
4	Bureautique	
5	Cyber Sécurité	
6	Anglais	
7	Genie Logiciel	
11	AI	
\.


--
-- Data for Name: etudiants; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.etudiants (id, matricule, firstname, lastname, email, phone, department, cohort, status, photo, created_at) FROM stdin;
\.


--
-- Data for Name: public_holidays; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.public_holidays (id, holiday_name, holiday_date, holiday_type, status, description) FROM stdin;
d602ee6a-f1ed-4719-9c3e-52c7418fdfbb	Nouvel An	2026-01-01	International	passe	\N
ccf82fcb-c57c-474b-8a53-c6d626d2c027	Ralph Huffman	2017-04-11	International	passe	Enim est expedita ea
3d634380-da17-42f9-9db8-248f0f2d5b6d	Rhea Pearson	2005-11-16	Religieux	passe	Nobis sed optio vel
09bb95fa-a8b4-4579-8775-745cefcf6af6	Aïd el-Adha(Tabaski)	2026-05-26	International	passe	
151011e0-627e-4f65-a875-e8ead70e288e	Tamkharite	2026-06-25	Religieux	passe	
b64b2cde-483e-4dba-b93d-834f405469d1	Magal Touba	2026-08-02	Religieux	avenir	
\.


--
-- Data for Name: qr_codes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.qr_codes (id, user_id, token, created_at) FROM stdin;
d974d02e-4c40-4d9b-8063-798077652dfd	d9346d78-471a-44b4-9868-5cbe27982d07	0e1730c3-c6ce-4bf5-88cf-bf93239614be	2026-07-06 17:23:53.206869
\.


--
-- Data for Name: school_settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.school_settings (id, school_lat, school_lng, radius, gps_enabled) FROM stdin;
\.


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.settings (id, school_lat, school_lng, radius, gps_enabled) FROM stdin;
1	14.721725593495934	-17.463747100271004	500	f
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, firstname, lastname, email, password_hash, role, "position", phone, photo, is_active, created_at, activation_token, is_verified, cohort_id, department_id) FROM stdin;
d9346d78-471a-44b4-9868-5cbe27982d07	Ndeye	Diop	diop@gmail.com	$2y$10$1HHhKDBDffeTnFU4cRyzve4Zorp1at8thrhc4NnHvJ0NSdXjPRGQm	admin	Administration	768210353	uploads/6a3d2dfddd032.webp	f	2026-06-25 13:32:45.919459	ae4faa324fc8b6c18385585aba3f88f4e9efe1c2678d7c73ac8c8d9e7e45b31b	t	15	1
9d2825db-6c75-4e34-b9bf-5b585ddb876a	Ndeye Mame	Diop	diopndeyemame1@gmail.com	$2y$10$FVrFF4i0skTY/yLshdsrwejpSn0wGX8/42qxlCKoAleEaADitW9ea	etudiant	\N	768210353	uploads/6a4e837ebf7bd.jpeg	t	2026-07-08 17:06:06.788962	78771a7748e39e1fec62f21445b77ac975a2d3f339ece315eb4adc97350078ae	t	17	6
17c5faa1-ee4f-4194-9e23-3487485483ef	ndeya	Diop	nd385667@gmail.com	$2y$10$eCAoygLvveLuzq/eSp/LMeK/6d8e3vf6ONEvw/Q5kzrNdZgVWPJ/e	etudiant	\N	768210353	uploads/6a4fef9d77ae6.jpg	t	2026-07-09 18:59:41.507873	\N	t	17	4
88d08773-ffec-4fe3-8b3f-6529106008f7	Rokhaya	Mbodji	kya@gmail.com	$2y$10$qghTzaE7qCwlAym1PLR0WO3dyHng0LVMZEGAyuGmhlBmghTvFYSf6	etudiant	\N	771111111	uploads/swe.jpeg	t	2026-06-24 23:13:58.286971	\N	t	15	5
668c63ba-1cb1-4230-8469-49a1b3882158	Mame	Diop	diopndeya808@gmail.com	$2y$10$Ef7zNjnI32ejqLasPqrrzO6QZORBN0fr6OP0IbTtOYSpJ4EWlvd2W	etudiant	\N	768210353	uploads/6a54e9258bdb3.jpeg	t	2026-07-13 13:33:25.584923	\N	t	21	1
62e400d4-7a83-4a23-8c6f-725261a4c1cc	Sidy	Diop 	sididiop53@gmail.com	\N	etudiant	\N	768210353	uploads/6a4c32ac46691.jpeg	f	2026-07-06 22:56:44.302391	bc511f0b30b9e7b3dc5fb3cf181b48898a886501fabaa416022e998132fb9d92	f	15	5
f56c433a-a002-467d-8565-d827730a5b75	Sweet	Angel	diopmamendey@gmail.com	$2y$10$myuTV/88LrGX.ucZFjHeX.UK62BLQq9639TL5U3RoU9agc1pR4fUq	admin	Administration	783112322	uploads/6a4e526cd3cf0.webp	t	2026-07-08 13:36:44.874017	\N	t	17	1
\.


--
-- Name: admin_schedules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.admin_schedules_id_seq', 1, false);


--
-- Name: cohort_schedules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cohort_schedules_id_seq', 45, true);


--
-- Name: cohorts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cohorts_id_seq', 24, true);


--
-- Name: company_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.company_settings_id_seq', 1, false);


--
-- Name: departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.departments_id_seq', 11, true);


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.settings_id_seq', 1, false);


--
-- Name: absences absences_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absences
    ADD CONSTRAINT absences_pkey PRIMARY KEY (id);


--
-- Name: admin_schedules admin_schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_schedules
    ADD CONSTRAINT admin_schedules_pkey PRIMARY KEY (id);


--
-- Name: attendances attendances_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.attendances
    ADD CONSTRAINT attendances_pkey PRIMARY KEY (id);


--
-- Name: attendances attendances_user_id_date_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.attendances
    ADD CONSTRAINT attendances_user_id_date_key UNIQUE (user_id, date);


--
-- Name: audit_logs audit_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_pkey PRIMARY KEY (id);


--
-- Name: cohort_schedules cohort_schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohort_schedules
    ADD CONSTRAINT cohort_schedules_pkey PRIMARY KEY (id);


--
-- Name: cohorts cohorts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohorts
    ADD CONSTRAINT cohorts_pkey PRIMARY KEY (id);


--
-- Name: company_settings company_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.company_settings
    ADD CONSTRAINT company_settings_pkey PRIMARY KEY (id);


--
-- Name: departments departments_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_name_key UNIQUE (name);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: etudiants etudiants_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etudiants
    ADD CONSTRAINT etudiants_email_key UNIQUE (email);


--
-- Name: etudiants etudiants_matricule_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etudiants
    ADD CONSTRAINT etudiants_matricule_key UNIQUE (matricule);


--
-- Name: etudiants etudiants_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etudiants
    ADD CONSTRAINT etudiants_pkey PRIMARY KEY (id);


--
-- Name: public_holidays public_holidays_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.public_holidays
    ADD CONSTRAINT public_holidays_pkey PRIMARY KEY (id);


--
-- Name: qr_codes qr_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.qr_codes
    ADD CONSTRAINT qr_codes_pkey PRIMARY KEY (id);


--
-- Name: qr_codes qr_codes_token_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.qr_codes
    ADD CONSTRAINT qr_codes_token_key UNIQUE (token);


--
-- Name: school_settings school_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.school_settings
    ADD CONSTRAINT school_settings_pkey PRIMARY KEY (id);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: cohort_schedules cohort_schedules_cohort_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohort_schedules
    ADD CONSTRAINT cohort_schedules_cohort_id_fkey FOREIGN KEY (cohort_id) REFERENCES public.cohorts(id) ON DELETE CASCADE;


--
-- Name: absences fk_absence_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absences
    ADD CONSTRAINT fk_absence_user FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: cohorts fk_department; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cohorts
    ADD CONSTRAINT fk_department FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: users fk_department; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_department FOREIGN KEY (department_id) REFERENCES public.departments(id);


--
-- Name: users fk_users_cohort; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_users_cohort FOREIGN KEY (cohort_id) REFERENCES public.cohorts(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict koFxMVCwanpb0sEeBFT71TgHUi2xPHDya7gNrhWWSJs5q4YTiBhoBF7Ne8EjxeM

