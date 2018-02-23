<?php 
use common\components\Migration;

class m170803_184110_init extends Migration
{
    public function safeUp()
    {

        \Yii::$app->db->pdo->exec("
            BEGIN;
            SET statement_timeout = 0;
            SET lock_timeout = 0;
            SET client_encoding = 'UTF8';
            SET standard_conforming_strings = on;
            SET check_function_bodies = false;
            SET client_min_messages = warning;
            SET row_security = off;
            
            CREATE SCHEMA counters;
            ALTER SCHEMA counters OWNER TO ".\Yii::$app->params['db']['user'].";
            CREATE SCHEMA relations;
            ALTER SCHEMA relations OWNER TO ".\Yii::$app->params['db']['user'].";
            CREATE SCHEMA results;
            ALTER SCHEMA results OWNER TO ".\Yii::$app->params['db']['user'].";
            SET search_path = counters, pg_catalog;
            SET default_tablespace = '';
            SET default_with_oids = false;
            
            CREATE TABLE counters_template (
                id integer NOT NULL,
                user_id integer NOT NULL,
                record_id integer NOT NULL,
                \"table\" character varying NOT NULL,
                info jsonb,
                ts timestamptz NOT NULL DEFAULT NOW(),
                state smallint
            );            
            ALTER TABLE counters_template OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE counters_template_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            ALTER TABLE counters_template_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            ALTER SEQUENCE counters_template_id_seq OWNED BY counters_template.id;
                        
            CREATE TABLE downloads (
            )
            INHERITS (counters_template);
            ALTER TABLE downloads OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE VIEW downloads_count AS
             SELECT count(*) AS count,
                downloads.\"table\",
                downloads.record_id
               FROM downloads
              GROUP BY downloads.\"table\", downloads.record_id
              ORDER BY downloads.\"table\", downloads.record_id;            
            ALTER TABLE downloads_count OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE views (                
            )
            INHERITS (counters_template);
            ALTER TABLE views OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE VIEW views_count AS
             SELECT count(*) AS count,
                views.\"table\",
                views.record_id
               FROM views
              GROUP BY views.\"table\", views.record_id
              ORDER BY views.\"table\", views.record_id;
                        
            ALTER TABLE views_count OWNER TO ".\Yii::$app->params['db']['user'].";
            
            SET search_path = public, pg_catalog;
            
            CREATE TABLE answers (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(5000) NOT NULL,
                is_correct smallint DEFAULT 0 NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                question_id integer NOT NULL
            );
            
            ALTER TABLE answers OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE answers_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE answers_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE answers_id_seq OWNED BY answers.id;
                        
            CREATE TABLE course_lessons (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(1000) NOT NULL,
                content text NOT NULL,
                info jsonb,
                ts timestamptz NOT NULL DEFAULT NOW(),
                user_id integer NOT NULL,
                course_id integer NOT NULL,
                type smallint DEFAULT 1 NOT NULL
            );
            
            ALTER TABLE course_lessons OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE course_lessons_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE course_lessons_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE course_lessons_id_seq OWNED BY course_lessons.id;
            
            CREATE TABLE courses (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(300) NOT NULL,
                description character varying(2000),
                info jsonb,
                state smallint DEFAULT 0 NOT NULL,
                user_id integer NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW()
            );
            
            ALTER TABLE courses OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE courses_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE courses_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE courses_id_seq OWNED BY courses.id;
            
            CREATE TABLE dic_values (
                id integer NOT NULL,
                organization_id integer,
                organization_type integer,
                dic_id integer NOT NULL,
                name character varying(255) NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                parent_id integer,
                value character varying(255)
            );
            
            ALTER TABLE dic_values OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE dic_values_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE dic_values_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE dic_values_id_seq OWNED BY dic_values.id;
            
            CREATE TABLE dics (
                id integer NOT NULL,
                organization_id integer,
                organization_type integer,
                name character varying(255) NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                description text
            );
            
            ALTER TABLE dics OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE dics_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE dics_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE dics_id_seq OWNED BY dics.id;
            
            CREATE TABLE events (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying NOT NULL,
                user_id integer,
                state smallint,
                info jsonb,
                ts timestamptz NOT NULL DEFAULT NOW(),
                begin_ts timestamptz NOT NULL,
                end_ts timestamptz NOT NULL
            );
            
            ALTER TABLE events OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE events_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1
                CYCLE;
            
            ALTER TABLE events_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE events_id_seq OWNED BY events.id;
            
            CREATE TABLE mail_queue (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                state smallint DEFAULT 0,
                ts timestamptz NOT NULL DEFAULT NOW(),
                \"to\" character varying(255) NOT NULL,
                subject character varying(500),
                body text,
                alt_body text,
                tries smallint DEFAULT 0,
                sent smallint DEFAULT 0
            );
            
            ALTER TABLE mail_queue OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE mail_queue_id_seq
                START WITH 1876
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE mail_queue_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE mail_queue_id_seq OWNED BY mail_queue.id;
            
            CREATE TABLE materials (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(255) NOT NULL,
                type smallint NOT NULL,
                info jsonb,
                ts timestamptz NOT NULL DEFAULT NOW(),
                is_shared smallint NOT NULL DEFAULT 0,
                user_id integer,
                description character varying(2000),
                source character varying(255),
                state smallint DEFAULT 1
            );
            
            ALTER TABLE materials OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE materials_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE materials_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE materials_id_seq OWNED BY materials.id;
            
            CREATE TABLE news (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                author_id integer,
                title character varying NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                body character varying,
                lang smallint,
                img character varying,
                state smallint DEFAULT 1
            );
                        
            ALTER TABLE news OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE news_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE news_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE news_id_seq OWNED BY news.id;
            
            CREATE TABLE options (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(2000) NOT NULL,
                value character varying(2000) NOT NULL
            );
            
            ALTER TABLE options OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE options_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE options_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE options_id_seq OWNED BY options.id;
            
            CREATE TABLE questions (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(5000) NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                user_id integer,
                type smallint NOT NULL,
                theme_id integer,
                weight real DEFAULT 1
            );
            
            ALTER TABLE questions OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE questions_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE questions_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE questions_id_seq OWNED BY questions.id;
            
            CREATE TABLE tags (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(255) NOT NULL,
                record_id integer NOT NULL,
                \"table\" character varying(255) NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb
            );
            
            ALTER TABLE tags OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE tags_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE tags_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE tags_id_seq OWNED BY tags.id;
                        
            CREATE TABLE tasks (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(500) NOT NULL,
                content character varying(2000) NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb
            );
            
            ALTER TABLE tasks OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE tasks_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE tasks_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE tasks_id_seq OWNED BY tasks.id;
            
            CREATE TABLE tests (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                name character varying(1000) NOT NULL,
                user_id integer,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                qcount smallint,
                \"time\" integer,
                protected smallint DEFAULT 0,
                random smallint DEFAULT 1,
                type smallint DEFAULT 1,
                description character varying(5000)
            );
            
            ALTER TABLE tests OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE tests_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
                        
            ALTER TABLE tests_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE tests_id_seq OWNED BY tests.id;
            
            CREATE TABLE users (
                id integer NOT NULL,
                login character varying(32) NOT NULL,
                password character varying(64) NOT NULL,
                role character varying(32) NOT NULL,
                fio character varying(255),
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                auth_key text,
                sex_id smallint,
                rank_id integer,
                state smallint DEFAULT 1,
                logo character varying,
                birthday integer,
                last_updated_ts integer DEFAULT 0,
                sap_id integer,
                unhashed_password character varying(64),
                email character varying(128),
                iin character varying(12)
            );
                        
            ALTER TABLE users OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE users_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
                        
            ALTER TABLE users_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE users_id_seq OWNED BY users.id;
            
            SET search_path = relations, pg_catalog;
            
            CREATE TABLE relations_template (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                related_id integer NOT NULL,
                target_id integer NOT NULL,
                info jsonb,
                ts timestamptz NOT NULL DEFAULT NOW(),
                state smallint
            );
                        
            ALTER TABLE relations_template OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE relations_template_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE relations_template_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE relations_template_id_seq OWNED BY relations_template.id;
            
            CREATE TABLE event_course (
            )
            INHERITS (relations_template);
            
            ALTER TABLE event_course OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE event_material (
            )
            INHERITS (relations_template);
            
            ALTER TABLE event_material OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE event_test (
            )
            INHERITS (relations_template);
            
            ALTER TABLE event_test OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE event_user (
            )
            INHERITS (relations_template);
            
            ALTER TABLE event_user OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE lesson_material (
            )
            INHERITS (relations_template);
            
            ALTER TABLE lesson_material OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE lesson_task (
            )
            INHERITS (relations_template);
            
            ALTER TABLE lesson_task OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE lesson_test (
            )
            INHERITS (relations_template);
            
            ALTER TABLE lesson_test OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE test_question (
            )
            INHERITS (relations_template);
            
            ALTER TABLE test_question OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE TABLE test_theme (
                weight integer
            )
            INHERITS (relations_template);
            
            ALTER TABLE test_theme OWNER TO ".\Yii::$app->params['db']['user'].";
            
            SET search_path = results, pg_catalog;
            
            CREATE TABLE event_results (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                event_id integer NOT NULL,
                user_id integer NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb
            );
            
            ALTER TABLE event_results OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE event_results_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE event_results_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE event_results_id_seq OWNED BY event_results.id;
            
            CREATE TABLE material_results (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                material_id integer NOT NULL,
                action smallint NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                source_id integer,
                source_table character varying(255),
                user_id integer NOT NULL
            );
            
            ALTER TABLE material_results OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE materials_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE materials_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE materials_id_seq OWNED BY material_results.id;
            
            CREATE TABLE task_results (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                user_id integer NOT NULL,
                task_id integer NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                answer character varying(2000) NOT NULL,
                result character varying(2000),
                reviewer_id integer,
                review_ts integer,
                source_id integer,
                source_table character varying(255)
            );
            
            ALTER TABLE task_results OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE task_results_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE task_results_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE task_results_id_seq OWNED BY task_results.id;
            
            CREATE TABLE test_results (
                id integer NOT NULL,
                organization_id integer NOT NULL,
                user_id integer NOT NULL,
                ts timestamptz NOT NULL DEFAULT NOW(),
                info jsonb,
                test_id integer NOT NULL,
                finished timestamptz,
                result integer,
                correct_answers integer,
                source_id integer,
                source_table character varying(255)
            );
                        
            ALTER TABLE test_results OWNER TO ".\Yii::$app->params['db']['user'].";
            
            CREATE SEQUENCE test_results_id_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
            
            ALTER TABLE test_results_id_seq OWNER TO ".\Yii::$app->params['db']['user'].";
            
            ALTER SEQUENCE test_results_id_seq OWNED BY test_results.id;
            
            SET search_path = counters, pg_catalog;
            
            ALTER TABLE ONLY counters_template ALTER COLUMN id SET DEFAULT nextval('counters_template_id_seq'::regclass);
            
            ALTER TABLE ONLY downloads ALTER COLUMN id SET DEFAULT nextval('counters_template_id_seq'::regclass);
            
            SET search_path = public, pg_catalog;
            
            ALTER TABLE ONLY answers ALTER COLUMN id SET DEFAULT nextval('answers_id_seq'::regclass);
            
            ALTER TABLE ONLY course_lessons ALTER COLUMN id SET DEFAULT nextval('course_lessons_id_seq'::regclass);
            
            ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);
            
            ALTER TABLE ONLY dic_values ALTER COLUMN id SET DEFAULT nextval('dic_values_id_seq'::regclass);
            
            ALTER TABLE ONLY dics ALTER COLUMN id SET DEFAULT nextval('dics_id_seq'::regclass);
            
            ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);
            
            ALTER TABLE ONLY mail_queue ALTER COLUMN id SET DEFAULT nextval('mail_queue_id_seq'::regclass);
            
            ALTER TABLE ONLY materials ALTER COLUMN id SET DEFAULT nextval('materials_id_seq'::regclass);
            
            ALTER TABLE ONLY news ALTER COLUMN id SET DEFAULT nextval('news_id_seq'::regclass);
            
            ALTER TABLE ONLY options ALTER COLUMN id SET DEFAULT nextval('options_id_seq'::regclass);
            
            ALTER TABLE ONLY questions ALTER COLUMN id SET DEFAULT nextval('questions_id_seq'::regclass);
            
            ALTER TABLE ONLY tags ALTER COLUMN id SET DEFAULT nextval('tags_id_seq'::regclass);
            
            ALTER TABLE ONLY tasks ALTER COLUMN id SET DEFAULT nextval('tasks_id_seq'::regclass);
            
            ALTER TABLE ONLY tests ALTER COLUMN id SET DEFAULT nextval('tests_id_seq'::regclass);
            
            ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);
            
            SET search_path = relations, pg_catalog;
            
            ALTER TABLE ONLY event_course ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY event_material ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
                        
            ALTER TABLE ONLY event_test ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY event_user ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY lesson_material ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY lesson_test ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY relations_template ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY test_question ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            ALTER TABLE ONLY test_theme ALTER COLUMN id SET DEFAULT nextval('relations_template_id_seq'::regclass);
            
            SET search_path = results, pg_catalog;
            
            ALTER TABLE ONLY event_results ALTER COLUMN id SET DEFAULT nextval('event_results_id_seq'::regclass);
            
            ALTER TABLE ONLY material_results ALTER COLUMN id SET DEFAULT nextval('materials_id_seq'::regclass);
            
            ALTER TABLE ONLY task_results ALTER COLUMN id SET DEFAULT nextval('task_results_id_seq'::regclass);
            
            ALTER TABLE ONLY test_results ALTER COLUMN id SET DEFAULT nextval('test_results_id_seq'::regclass);
            
            SET search_path = counters, pg_catalog;
            
            ALTER TABLE ONLY counters_template
                ADD CONSTRAINT counters_template_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY views
                ADD CONSTRAINT view_pkey PRIMARY KEY (id);
                        
            SET search_path = public, pg_catalog;
            
            ALTER TABLE ONLY answers
                ADD CONSTRAINT answers_pkey PRIMARY KEY (id, ts);
                        
            ALTER TABLE ONLY course_lessons
                ADD CONSTRAINT course_lessons_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY courses
                ADD CONSTRAINT courses_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY dic_values
                ADD CONSTRAINT dic_values_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY dics
                ADD CONSTRAINT dics_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY events
                ADD CONSTRAINT events_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY mail_queue
                ADD CONSTRAINT mail_queue_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY materials
                ADD CONSTRAINT materials_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY options
                ADD CONSTRAINT name UNIQUE (name);
            
            ALTER TABLE ONLY news
                ADD CONSTRAINT news_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY options
                ADD CONSTRAINT options_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY questions
                ADD CONSTRAINT questions_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY tags
                ADD CONSTRAINT tags_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY tasks
                ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY tests
                ADD CONSTRAINT tests_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY users
                ADD CONSTRAINT users_pkey PRIMARY KEY (id);
            
            SET search_path = relations, pg_catalog;
            
            ALTER TABLE ONLY relations_template
                ADD CONSTRAINT relations_template_pkey PRIMARY KEY (id);
            
            SET search_path = results, pg_catalog;
            
            ALTER TABLE ONLY event_results
                ADD CONSTRAINT event_results_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY material_results
                ADD CONSTRAINT materials_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY task_results
                ADD CONSTRAINT task_results_pkey PRIMARY KEY (id);
            
            ALTER TABLE ONLY test_results
                ADD CONSTRAINT test_results_pkey PRIMARY KEY (id);
            
            SET search_path = public, pg_catalog;
                        
            CREATE INDEX by_ts_state ON mail_queue USING btree (state DESC NULLS LAST, ts);
            
            CREATE INDEX search_index ON questions USING gin (to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text), to_tsvector('russian'::regconfig, (name)::text));
                        
            SET search_path = public, pg_catalog;
            COMMIT;
        ");

    }

    public function safeDown()
    {
        echo "m170803_184110_init cannot be reverted.\n";
        return false;
    }
}
