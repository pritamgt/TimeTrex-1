CREATE TABLE user_identification (
    id serial NOT NULL,
    user_id integer NOT NULL,
    type_id integer NOT NULL,
    number integer NOT NULL,
    value text,
    extra_value text,
    created_date integer,
    created_by integer,
    updated_date integer,
    updated_by integer,
    deleted_date integer,
    deleted_by integer,
    deleted integer DEFAULT 0 NOT NULL
);
CREATE INDEX user_identification_id ON user_identification USING btree (id);
CREATE INDEX user_identification_user_id ON user_identification USING btree (user_id);
