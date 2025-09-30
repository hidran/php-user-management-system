create table users
(
    id         bigint unsigned auto_increment
        primary key,
    username   varchar(64)                                                not null,
    email      varchar(64)                                                not null,
    fiscalcode char(16)                                                   not null,
    age        smallint unsigned                                          not null,
    avatar     varchar(255)                                               not null,
    password   varchar(255)                                               not null,
    role_type  enum ('user', 'editor', 'admin') default 'user'            not null,
    created_at datetime                         default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    updated_at datetime                         default CURRENT_TIMESTAMP null,
    deleted_at datetime                                                   null,
    constraint u_fiscalcode
        unique (fiscalcode)
)
    collate = utf8mb3_unicode_ci;

create index i_email
    on users (email);

create index i_username
    on users (username);

