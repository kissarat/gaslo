-- create database gaslo;
-- create extension pgcrypto;

drop table if exists note;
drop table if exists subject;

create table subject (
  name      char(16) not null primary key,
  password  char(32) not null,
  salt      char(32)     null,
  expire    int          null default 30 * 24 * 60 * 60,
  alive     smallint not null default 10 * 60,
  verified  boolean  not null default false,
  active    boolean  not null default false,
  admin     boolean  not null default false,
  moder     boolean  not null default false,
  editor    boolean  not null default false,
  writer    boolean  not null default true,
  warning   text         null,
  error     text         null
);

insert into subject values ('guest', md5('1'), encode(gen_random_bytes(16), 'hex'),
                            default, default, true, true, true, true, true, true, null, null);

insert into subject values ('admin', md5('1'), encode(gen_random_bytes(16), 'hex'),
                            default, default, true, true, true, true, true, true, null, null);
insert into subject values ('moder', md5('1'), encode(gen_random_bytes(16), 'hex'),
                            default, default, true, true, false, true, true, true, null, null);

create table note (
  id bigserial not null primary key,
  parent bigint references note(id),
  author char(16) not null references subject(name),
  title varchar(70),
  content text not null
);