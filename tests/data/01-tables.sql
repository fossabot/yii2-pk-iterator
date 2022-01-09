DROP TABLE IF EXISTS test_normal;
DROP TABLE IF EXISTS test_no_pk;
DROP TABLE IF EXISTS test_string_pk;
DROP TABLE IF EXISTS test_composite_pk;

create table test_normal
(
    id_1  bigint unsigned auto_increment not null,
    title varchar(255)                   not null,
    data  mediumtext                     not null,
    primary key (id_1)
);

create table test_no_pk
(
    title varchar(255) not null,
    data  mediumtext   not null
);

create table test_string_pk
(
    id_1  varchar(255) not null,
    title varchar(255) not null,
    data  mediumtext   not null,
    primary key (id_1)
);

create table test_composite_pk
(
    id_1  bigint unsigned not null,
    id_2  bigint unsigned not null,
    title varchar(255)    not null,
    data  mediumtext      not null,
    primary key (id_1, id_2)
);
