--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acl_permissions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE acl_permissions (
    permission_id integer NOT NULL,
    permission_key text,
    permission_value text,
    permission_role text
);


ALTER TABLE acl_permissions OWNER TO postgres;

--
-- Name: acl_permissions_permission_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE acl_permissions_permission_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acl_permissions_permission_id_seq OWNER TO postgres;

--
-- Name: acl_permissions_permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE acl_permissions_permission_id_seq OWNED BY acl_permissions.permission_id;


--
-- Name: acl_roles; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE acl_roles (
    role_id integer NOT NULL,
    role_key text,
    role_name text
);


ALTER TABLE acl_roles OWNER TO postgres;

--
-- Name: acl_roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE acl_roles_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acl_roles_role_id_seq OWNER TO postgres;

--
-- Name: acl_roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE acl_roles_role_id_seq OWNED BY acl_roles.role_id;


--
-- Name: members; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE members (
    id integer NOT NULL,
    username text,
    password text,
    group_id integer,
    role text,
    joined integer,
    email text,
    token text,
    ip_address text
);


ALTER TABLE members OWNER TO postgres;

--
-- Name: members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE members_id_seq OWNER TO postgres;

--
-- Name: members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE members_id_seq OWNED BY members.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE sessions (
    sess_id character varying(128) NOT NULL,
    sess_data bytea NOT NULL,
    sess_lifetime integer NOT NULL,
    sess_time integer NOT NULL
);


ALTER TABLE sessions OWNER TO postgres;

--
-- Name: permission_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acl_permissions ALTER COLUMN permission_id SET DEFAULT nextval('acl_permissions_permission_id_seq'::regclass);


--
-- Name: role_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acl_roles ALTER COLUMN role_id SET DEFAULT nextval('acl_roles_role_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY members ALTER COLUMN id SET DEFAULT nextval('members_id_seq'::regclass);


--
-- Data for Name: acl_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY acl_permissions (permission_id, permission_key, permission_value, permission_role) FROM stdin;
21	admincp_access	0	ROLE_MOD
22	admincp_access	0	ROLE_GUEST
23	admincp_access	0	ROLE_CRAWLER
24	admincp_access	1	ROLE_ADMIN
25	can_see_index	1	ROLE_MOD
26	can_see_index	1	ROLE_GUEST
27	can_see_index	1	ROLE_CRAWLER
28	can_see_index	1	ROLE_ADMIN
29	admincp_access	0	ROLE_USER
30	can_see_index	1	ROLE_USER
\.


--
-- Name: acl_permissions_permission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('acl_permissions_permission_id_seq', 30, true);


--
-- Data for Name: acl_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY acl_roles (role_id, role_key, role_name) FROM stdin;
1	ROLE_ADMIN	Administrator
2	ROLE_MOD	Moderator
3	ROLE_GUEST	Guest
4	ROLE_CRAWLER	Crawler
5	ROLE_USER	User
\.


--
-- Name: acl_roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('acl_roles_role_id_seq', 5, true);


--
-- Data for Name: members; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY members (id, username, password, group_id, role, joined, email, token, ip_address) FROM stdin;
1	TestAdmin	$2y$10$DLXsrtUD4Inmu4u9tQ6VdOfrZg0h0BMlPr.u6oUlrn67BNZ16TzEG	1	ROLE_ADMIN	1436140730	testing@example.com	$2y$10$tAZ0tMb2wSsSZESVu2jK2.10Av1XyLVFHTi.pD4AhgMdlqnK5I9.y	0.0.0.0
2	TestMember	$2y$10$dGlZGiXwE.xS.qkTiuR0n.JupTpwWQEtGvrgmzzQYsIn2MbzIdr6e	1	ROLE_USER	1436391454	ro@s.com	$2y$10$w.MT5wT6cusGCB4igkHCouvcZiOgyp0mboOUO4DblXBs8xqCg1bAi	::1
\.


--
-- Name: members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('members_id_seq', 2, true);


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sessions (sess_id, sess_data, sess_lifetime, sess_time) FROM stdin;
kpbdhhbjcasfq0rit377kg80b3	\\x5f7366325f617474726962757465737c613a313a7b733a393a226d656d6265725f6964223b693a313b7d5f7366325f666c61736865737c613a303a7b7d5f7366325f6d6574617c613a333a7b733a313a2275223b693a313433363839383830373b733a313a2263223b693a313433363839383531363b733a313a226c223b733a313a2230223b7d5f637372667c613a323a7b733a31333a22617574685f66726f6e74656e64223b733a34333a226332706d595f7548426c7130587275326467414f496a5a315f464c416c36552d2d73685462764c6a595451223b733a363a226c6f676f7574223b733a34333a22334651465846324754736b504367684c5f7151582d3558587a44397335344c6e6b73514575613978576749223b7d	1440	1436898808
\.


--
-- Name: members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);


--
-- Name: sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (sess_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

