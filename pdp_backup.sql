--
-- PostgreSQL database dump
--

\restrict sEVV6nJLHMt5NaCqJY4XH30eZWYmUVGVEWWV048dzhCsMQgY4iubCPbMk2yVc6G

-- Dumped from database version 18.0
-- Dumped by pg_dump version 18.0

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: activity_log; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.activity_log (
    id bigint NOT NULL,
    log_name character varying(255),
    description text NOT NULL,
    subject_type character varying(255),
    subject_id bigint,
    causer_type character varying(255),
    causer_id bigint,
    properties json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event character varying(255),
    batch_uuid uuid
);


ALTER TABLE public.activity_log OWNER TO sail;

--
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.activity_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_log_id_seq OWNER TO sail;

--
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO sail;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO sail;

--
-- Name: chassis; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.chassis (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    license_plate character varying(10) NOT NULL,
    status character varying(255) DEFAULT '1'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    vehicle_type character varying(255),
    axle_count integer,
    has_bonus boolean DEFAULT false NOT NULL,
    tare numeric(8,2),
    safe_weight numeric(8,2),
    height numeric(8,2),
    length numeric(8,2),
    width numeric(8,2),
    is_insulated boolean DEFAULT false NOT NULL,
    material character varying(255),
    accepts_20ft boolean DEFAULT false NOT NULL,
    accepts_40ft boolean DEFAULT false NOT NULL,
    appeal_token character varying(255),
    appeal_token_expires_at timestamp(0) without time zone
);


ALTER TABLE public.chassis OWNER TO sail;

--
-- Name: COLUMN chassis.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.chassis.status IS '1: Inactivo, 2: Activo, 3: Necesita Actualización';


--
-- Name: chassis_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.chassis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.chassis_id_seq OWNER TO sail;

--
-- Name: chassis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.chassis_id_seq OWNED BY public.chassis.id;


--
-- Name: companies; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.companies (
    id bigint NOT NULL,
    type integer DEFAULT 1 NOT NULL,
    ruc character varying(11) NOT NULL,
    business_name character varying(255) NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    appeal_token character varying(255),
    appeal_token_expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.companies OWNER TO sail;

--
-- Name: COLUMN companies.type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.companies.type IS '1: Natural, 2: Jurídica';


--
-- Name: COLUMN companies.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.companies.status IS '1: Pendiente, 2: Aprobado, 3: Rechazado';


--
-- Name: companies_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.companies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.companies_id_seq OWNER TO sail;

--
-- Name: companies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.companies_id_seq OWNED BY public.companies.id;


--
-- Name: company_documents; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.company_documents (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    rejection_reason text,
    submitted_date date,
    validated_by bigint,
    validated_date date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.company_documents OWNER TO sail;

--
-- Name: COLUMN company_documents.type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.company_documents.type IS '1: Ficha RUC, 2: DNI Representante, 3: Ficha SUNARP, 4: Vigencia de Poder';


--
-- Name: COLUMN company_documents.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.company_documents.status IS '1: Pendiente, 2: Aprobado, 3: Rechazado';


--
-- Name: company_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.company_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.company_documents_id_seq OWNER TO sail;

--
-- Name: company_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.company_documents_id_seq OWNED BY public.company_documents.id;


--
-- Name: documents; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.documents (
    id bigint NOT NULL,
    documentable_type character varying(255) NOT NULL,
    documentable_id integer NOT NULL,
    type character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    submitted_date date NOT NULL,
    expiration_date date NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    rejection_reason text,
    validated_by bigint,
    validated_date timestamp(0) without time zone
);


ALTER TABLE public.documents OWNER TO sail;

--
-- Name: COLUMN documents.documentable_type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.documents.documentable_type IS 'Driver, Truck, Chassis';


--
-- Name: COLUMN documents.submitted_date; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.documents.submitted_date IS 'Fecha de subida del documento';


--
-- Name: COLUMN documents.expiration_date; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.documents.expiration_date IS 'Fecha de vencimiento del documento';


--
-- Name: COLUMN documents.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.documents.status IS 'pending, approved, rejected';


--
-- Name: documents_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.documents_id_seq OWNER TO sail;

--
-- Name: documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.documents_id_seq OWNED BY public.documents.id;


--
-- Name: drivers; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.drivers (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    document_type integer NOT NULL,
    document_number character varying(20) NOT NULL,
    name character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    email character varying(255),
    phone character varying(255),
    appeal_token character varying(64),
    appeal_token_expires_at timestamp(0) without time zone,
    license_number character varying(50)
);


ALTER TABLE public.drivers OWNER TO sail;

--
-- Name: COLUMN drivers.document_type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.drivers.document_type IS '1: DNI, 2: Carné de Extranjería';


--
-- Name: COLUMN drivers.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.drivers.status IS '1: Inactivo, 2: Activo, 3: Necesita Actualización, 4: Espera de aprobación, 5: Revisión Documentos, 6: Documentos Infectados';


--
-- Name: drivers_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.drivers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.drivers_id_seq OWNER TO sail;

--
-- Name: drivers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.drivers_id_seq OWNED BY public.drivers.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO sail;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO sail;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO sail;

--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO sail;

--
-- Name: permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO sail;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO sail;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: request_documents; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.request_documents (
    id bigint NOT NULL,
    request_item_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    submitted_date date NOT NULL,
    expiration_date date NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    rejection_reason text,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.request_documents OWNER TO sail;

--
-- Name: COLUMN request_documents.type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_documents.type IS 'Tipo de documento según el itemable_type';


--
-- Name: COLUMN request_documents.submitted_date; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_documents.submitted_date IS 'Fecha de subida del documento';


--
-- Name: COLUMN request_documents.expiration_date; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_documents.expiration_date IS 'Fecha de vencimiento del documento';


--
-- Name: COLUMN request_documents.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_documents.status IS '1: Pendiente, 2: Aprobado, 3: Rechazado';


--
-- Name: request_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.request_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.request_documents_id_seq OWNER TO sail;

--
-- Name: request_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.request_documents_id_seq OWNED BY public.request_documents.id;


--
-- Name: request_items; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.request_items (
    id bigint NOT NULL,
    request_id bigint NOT NULL,
    itemable_type character varying(255) NOT NULL,
    itemable_id bigint NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    rejection_reason text,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.request_items OWNER TO sail;

--
-- Name: COLUMN request_items.itemable_type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_items.itemable_type IS 'Driver, Truck, Chassis';


--
-- Name: COLUMN request_items.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.request_items.status IS '1: Pendiente, 2: Aprobado, 3: Rechazado';


--
-- Name: request_items_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.request_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.request_items_id_seq OWNER TO sail;

--
-- Name: request_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.request_items_id_seq OWNED BY public.request_items.id;


--
-- Name: requests; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.requests (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    status integer DEFAULT 1 NOT NULL,
    submitted_by bigint,
    submitted_at timestamp(0) without time zone,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    rejection_reason text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.requests OWNER TO sail;

--
-- Name: COLUMN requests.title; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.requests.title IS 'Título de la solicitud';


--
-- Name: COLUMN requests.description; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.requests.description IS 'Descripción de la solicitud';


--
-- Name: COLUMN requests.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.requests.status IS '1: Borrador, 2: Enviado, 3: En Revisión, 4: Aprobado, 5: Rechazado';


--
-- Name: requests_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.requests_id_seq OWNER TO sail;

--
-- Name: requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.requests_id_seq OWNED BY public.requests.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO sail;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO sail;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO sail;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO sail;

--
-- Name: trucks; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.trucks (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    license_plate character varying(10) NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    nationality character varying(255),
    is_internal boolean DEFAULT false NOT NULL,
    truck_type character varying(255),
    has_bonus boolean DEFAULT false NOT NULL,
    tare numeric(10,2),
    appeal_token character varying(255),
    appeal_token_expires_at timestamp(0) without time zone
);


ALTER TABLE public.trucks OWNER TO sail;

--
-- Name: COLUMN trucks.status; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.status IS '1: Inactivo, 2: Activo, 3: Necesita Actualización';


--
-- Name: COLUMN trucks.nationality; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.nationality IS 'Nacionalidad del vehículo';


--
-- Name: COLUMN trucks.is_internal; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.is_internal IS 'Si es interno';


--
-- Name: COLUMN trucks.truck_type; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.truck_type IS 'Tipo: T3, T-Especial, etc.';


--
-- Name: COLUMN trucks.has_bonus; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.has_bonus IS 'Si tiene bonificación';


--
-- Name: COLUMN trucks.tare; Type: COMMENT; Schema: public; Owner: sail
--

COMMENT ON COLUMN public.trucks.tare IS 'Tara del vehículo en toneladas';


--
-- Name: trucks_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.trucks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.trucks_id_seq OWNER TO sail;

--
-- Name: trucks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.trucks_id_seq OWNED BY public.trucks.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    dni character varying(8),
    name character varying(255) NOT NULL,
    last_name character varying(255),
    company_id bigint,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    is_company_representative boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- Name: chassis id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.chassis ALTER COLUMN id SET DEFAULT nextval('public.chassis_id_seq'::regclass);


--
-- Name: companies id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.companies ALTER COLUMN id SET DEFAULT nextval('public.companies_id_seq'::regclass);


--
-- Name: company_documents id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.company_documents ALTER COLUMN id SET DEFAULT nextval('public.company_documents_id_seq'::regclass);


--
-- Name: documents id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.documents ALTER COLUMN id SET DEFAULT nextval('public.documents_id_seq'::regclass);


--
-- Name: drivers id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.drivers ALTER COLUMN id SET DEFAULT nextval('public.drivers_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: request_documents id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_documents ALTER COLUMN id SET DEFAULT nextval('public.request_documents_id_seq'::regclass);


--
-- Name: request_items id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_items ALTER COLUMN id SET DEFAULT nextval('public.request_items_id_seq'::regclass);


--
-- Name: requests id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.requests ALTER COLUMN id SET DEFAULT nextval('public.requests_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: trucks id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.trucks ALTER COLUMN id SET DEFAULT nextval('public.trucks_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: activity_log; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.activity_log (id, log_name, description, subject_type, subject_id, causer_type, causer_id, properties, created_at, updated_at, event, batch_uuid) FROM stdin;
1	Resource	User Created	App\\Models\\User	1	\N	\N	{"name":"Admn","email":"admin@admin.com","updated_at":"2025-11-07 22:09:26","created_at":"2025-11-07 22:09:26","id":1}	2025-11-07 22:09:26	2025-11-07 22:09:26	Created	\N
2	Resource	Role Created	Spatie\\Permission\\Models\\Role	1	\N	\N	{"guard_name":"web","name":"super_admin","updated_at":"2025-11-07 22:10:15","created_at":"2025-11-07 22:10:15","id":1}	2025-11-07 22:10:15	2025-11-07 22:10:15	Created	\N
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: chassis; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.chassis (id, company_id, license_plate, status, created_at, updated_at, deleted_at, vehicle_type, axle_count, has_bonus, tare, safe_weight, height, length, width, is_insulated, material, accepts_20ft, accepts_40ft, appeal_token, appeal_token_expires_at) FROM stdin;
\.


--
-- Data for Name: companies; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.companies (id, type, ruc, business_name, status, is_active, appeal_token, appeal_token_expires_at, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: company_documents; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.company_documents (id, company_id, type, path, status, rejection_reason, submitted_date, validated_by, validated_date, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: documents; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.documents (id, documentable_type, documentable_id, type, path, submitted_date, expiration_date, status, created_at, updated_at, rejection_reason, validated_by, validated_date) FROM stdin;
\.


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.drivers (id, company_id, document_type, document_number, name, lastname, status, created_at, updated_at, deleted_at, email, phone, appeal_token, appeal_token_expires_at, license_number) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_companies_table	1
2	0001_01_01_000001_create_users_table	1
3	0001_01_01_000002_create_cache_table	1
4	0001_01_01_000003_create_jobs_table	1
5	2025_03_13_210016_create_permission_tables	1
6	2025_03_13_212439_create_activity_log_table	1
7	2025_03_13_212440_add_event_column_to_activity_log_table	1
8	2025_03_13_212441_add_batch_uuid_column_to_activity_log_table	1
9	2025_10_30_125140_create_company_documents_table	1
10	2025_11_01_063453_create_drivers_table	1
11	2025_11_01_063502_create_trucks_table	1
12	2025_11_01_063515_create_chassis_table	1
13	2025_11_01_063525_create_documents_table	1
14	2025_11_01_123823_create_requests_table	1
15	2025_11_01_123924_create_request_items_table	1
16	2025_11_01_124000_create_request_documents_table	1
17	2025_11_06_091413_add_validation_fields_to_documents_table	1
18	2025_11_06_091445_add_appeal_fields_to_drivers_table	1
19	2025_11_06_101041_add_license_number_to_drivers_table	1
20	2025_11_07_115302_add_additional_fields_to_trucks_table	1
21	2025_11_07_121700_add_appeal_token_to_trucks_table	1
22	2025_11_07_162832_add_detailed_fields_to_chassis_table	1
23	2025_11_07_165900_add_appeal_token_to_chassis_table	1
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	view_any_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
2	view_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
3	create_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
4	update_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
5	delete_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
6	restore_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
7	force_delete_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
8	force_delete_any_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
9	restore_any_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
10	replicate_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
11	reorder_chassis	web	2025-11-07 22:10:52	2025-11-07 22:10:52
12	view_any_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
13	view_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
14	create_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
15	update_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
16	delete_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
17	restore_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
18	force_delete_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
19	force_delete_any_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
20	restore_any_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
21	replicate_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
22	reorder_company	web	2025-11-07 22:10:52	2025-11-07 22:10:52
23	view_any_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
24	view_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
25	create_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
26	update_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
27	delete_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
28	restore_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
29	force_delete_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
30	force_delete_any_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
31	restore_any_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
32	replicate_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
33	reorder_driver	web	2025-11-07 22:10:52	2025-11-07 22:10:52
34	view_any_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
35	view_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
36	create_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
37	update_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
38	delete_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
39	restore_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
40	force_delete_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
41	force_delete_any_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
42	restore_any_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
43	replicate_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
44	reorder_truck	web	2025-11-07 22:10:52	2025-11-07 22:10:52
45	view_any_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
46	view_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
47	create_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
48	update_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
49	delete_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
50	restore_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
51	force_delete_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
52	force_delete_any_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
53	restore_any_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
54	replicate_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
55	reorder_user	web	2025-11-07 22:10:52	2025-11-07 22:10:52
56	view_any_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
57	view_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
58	create_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
59	update_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
60	delete_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
61	restore_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
62	force_delete_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
63	force_delete_any_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
64	restore_any_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
65	replicate_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
66	reorder_role	web	2025-11-07 22:10:52	2025-11-07 22:10:52
67	view_any_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
68	view_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
69	create_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
70	update_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
71	delete_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
72	restore_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
73	force_delete_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
74	force_delete_any_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
75	restore_any_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
76	replicate_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
77	reorder_activity	web	2025-11-07 22:10:52	2025-11-07 22:10:52
78	view_my_profile_page	web	2025-11-07 22:10:52	2025-11-07 22:10:52
79	view_company_stats_overview	web	2025-11-07 22:10:52	2025-11-07 22:10:52
\.


--
-- Data for Name: request_documents; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.request_documents (id, request_item_id, type, path, submitted_date, expiration_date, status, rejection_reason, reviewed_by, reviewed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: request_items; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.request_items (id, request_id, itemable_type, itemable_id, status, rejection_reason, reviewed_by, reviewed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: requests; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.requests (id, company_id, title, description, status, submitted_by, submitted_at, reviewed_by, reviewed_at, rejection_reason, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
6	1
7	1
8	1
9	1
10	1
11	1
12	1
13	1
14	1
15	1
16	1
17	1
18	1
19	1
20	1
21	1
22	1
23	1
24	1
25	1
26	1
27	1
28	1
29	1
30	1
31	1
32	1
33	1
34	1
35	1
36	1
37	1
38	1
39	1
40	1
41	1
42	1
43	1
44	1
45	1
46	1
47	1
48	1
49	1
50	1
51	1
52	1
53	1
54	1
55	1
56	1
57	1
58	1
59	1
60	1
61	1
62	1
63	1
64	1
65	1
66	1
67	1
68	1
69	1
70	1
71	1
72	1
73	1
74	1
75	1
76	1
77	1
78	1
79	1
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	super_admin	web	2025-11-07 22:10:15	2025-11-07 22:10:15
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: trucks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.trucks (id, company_id, license_plate, status, created_at, updated_at, deleted_at, nationality, is_internal, truck_type, has_bonus, tare, appeal_token, appeal_token_expires_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.users (id, dni, name, last_name, company_id, email, email_verified_at, password, is_company_representative, is_active, remember_token, created_at, updated_at, deleted_at) FROM stdin;
1	\N	Admn	\N	\N	admin@admin.com	\N	$2y$12$xqDxySYGUEB1YU7sQRmDIOuTZe39h56u97RVUGXRD09dnj8d3OSC.	f	t	\N	2025-11-07 22:09:26	2025-11-07 22:09:26	\N
\.


--
-- Name: activity_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.activity_log_id_seq', 2, true);


--
-- Name: chassis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.chassis_id_seq', 1, false);


--
-- Name: companies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.companies_id_seq', 1, false);


--
-- Name: company_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.company_documents_id_seq', 1, false);


--
-- Name: documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.documents_id_seq', 1, false);


--
-- Name: drivers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.drivers_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.migrations_id_seq', 23, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.permissions_id_seq', 79, true);


--
-- Name: request_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.request_documents_id_seq', 1, false);


--
-- Name: request_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.request_items_id_seq', 1, false);


--
-- Name: requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.requests_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.roles_id_seq', 1, true);


--
-- Name: trucks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.trucks_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: chassis chassis_company_id_license_plate_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.chassis
    ADD CONSTRAINT chassis_company_id_license_plate_unique UNIQUE (company_id, license_plate);


--
-- Name: chassis chassis_license_plate_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.chassis
    ADD CONSTRAINT chassis_license_plate_unique UNIQUE (license_plate);


--
-- Name: chassis chassis_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.chassis
    ADD CONSTRAINT chassis_pkey PRIMARY KEY (id);


--
-- Name: companies companies_appeal_token_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_appeal_token_unique UNIQUE (appeal_token);


--
-- Name: companies companies_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_pkey PRIMARY KEY (id);


--
-- Name: companies companies_ruc_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_ruc_unique UNIQUE (ruc);


--
-- Name: company_documents company_documents_company_id_type_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.company_documents
    ADD CONSTRAINT company_documents_company_id_type_unique UNIQUE (company_id, type);


--
-- Name: company_documents company_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.company_documents
    ADD CONSTRAINT company_documents_pkey PRIMARY KEY (id);


--
-- Name: documents documents_documentable_type_documentable_id_type_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_documentable_type_documentable_id_type_unique UNIQUE (documentable_type, documentable_id, type);


--
-- Name: documents documents_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- Name: drivers drivers_appeal_token_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_appeal_token_unique UNIQUE (appeal_token);


--
-- Name: drivers drivers_company_id_document_number_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_company_id_document_number_unique UNIQUE (company_id, document_number);


--
-- Name: drivers drivers_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: request_documents request_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_documents
    ADD CONSTRAINT request_documents_pkey PRIMARY KEY (id);


--
-- Name: request_documents request_documents_request_item_id_type_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_documents
    ADD CONSTRAINT request_documents_request_item_id_type_unique UNIQUE (request_item_id, type);


--
-- Name: request_items request_items_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_items
    ADD CONSTRAINT request_items_pkey PRIMARY KEY (id);


--
-- Name: request_items request_items_request_id_itemable_type_itemable_id_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_items
    ADD CONSTRAINT request_items_request_id_itemable_type_itemable_id_unique UNIQUE (request_id, itemable_type, itemable_id);


--
-- Name: requests requests_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.requests
    ADD CONSTRAINT requests_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: trucks trucks_company_id_license_plate_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.trucks
    ADD CONSTRAINT trucks_company_id_license_plate_unique UNIQUE (company_id, license_plate);


--
-- Name: trucks trucks_license_plate_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.trucks
    ADD CONSTRAINT trucks_license_plate_unique UNIQUE (license_plate);


--
-- Name: trucks trucks_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.trucks
    ADD CONSTRAINT trucks_pkey PRIMARY KEY (id);


--
-- Name: users users_dni_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_dni_unique UNIQUE (dni);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: activity_log_log_name_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX activity_log_log_name_index ON public.activity_log USING btree (log_name);


--
-- Name: causer; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX causer ON public.activity_log USING btree (causer_type, causer_id);


--
-- Name: documents_documentable_type_documentable_id_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX documents_documentable_type_documentable_id_index ON public.documents USING btree (documentable_type, documentable_id);


--
-- Name: documents_expiration_date_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX documents_expiration_date_index ON public.documents USING btree (expiration_date);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: request_documents_expiration_date_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX request_documents_expiration_date_index ON public.request_documents USING btree (expiration_date);


--
-- Name: request_documents_request_item_id_status_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX request_documents_request_item_id_status_index ON public.request_documents USING btree (request_item_id, status);


--
-- Name: request_items_itemable_type_itemable_id_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX request_items_itemable_type_itemable_id_index ON public.request_items USING btree (itemable_type, itemable_id);


--
-- Name: request_items_request_id_status_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX request_items_request_id_status_index ON public.request_items USING btree (request_id, status);


--
-- Name: requests_company_id_status_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX requests_company_id_status_index ON public.requests USING btree (company_id, status);


--
-- Name: requests_submitted_at_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX requests_submitted_at_index ON public.requests USING btree (submitted_at);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: subject; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX subject ON public.activity_log USING btree (subject_type, subject_id);


--
-- Name: chassis chassis_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.chassis
    ADD CONSTRAINT chassis_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- Name: company_documents company_documents_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.company_documents
    ADD CONSTRAINT company_documents_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- Name: company_documents company_documents_validated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.company_documents
    ADD CONSTRAINT company_documents_validated_by_foreign FOREIGN KEY (validated_by) REFERENCES public.users(id) ON DELETE RESTRICT;


--
-- Name: documents documents_validated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_validated_by_foreign FOREIGN KEY (validated_by) REFERENCES public.users(id);


--
-- Name: drivers drivers_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: request_documents request_documents_request_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_documents
    ADD CONSTRAINT request_documents_request_item_id_foreign FOREIGN KEY (request_item_id) REFERENCES public.request_items(id) ON DELETE CASCADE;


--
-- Name: request_documents request_documents_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_documents
    ADD CONSTRAINT request_documents_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id) ON DELETE RESTRICT;


--
-- Name: request_items request_items_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_items
    ADD CONSTRAINT request_items_request_id_foreign FOREIGN KEY (request_id) REFERENCES public.requests(id) ON DELETE CASCADE;


--
-- Name: request_items request_items_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.request_items
    ADD CONSTRAINT request_items_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id) ON DELETE RESTRICT;


--
-- Name: requests requests_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.requests
    ADD CONSTRAINT requests_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- Name: requests requests_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.requests
    ADD CONSTRAINT requests_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id) ON DELETE RESTRICT;


--
-- Name: requests requests_submitted_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.requests
    ADD CONSTRAINT requests_submitted_by_foreign FOREIGN KEY (submitted_by) REFERENCES public.users(id) ON DELETE RESTRICT;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: trucks trucks_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.trucks
    ADD CONSTRAINT trucks_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- Name: users users_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict sEVV6nJLHMt5NaCqJY4XH30eZWYmUVGVEWWV048dzhCsMQgY4iubCPbMk2yVc6G

