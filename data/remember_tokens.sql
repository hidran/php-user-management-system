create table remember_tokens
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned                    not null,
    token_hash char(64)                           not null,
    expires_at datetime                           not null,
    created_at datetime default CURRENT_TIMESTAMP not null,
    user_agent varchar(255)                       null,
    ip_address varbinary(16)                      not null,
    selector   char(18)                           not null,
    constraint uk_selector_remember_me
        unique (selector),
    constraint remember_tokens_users_id_fk
        foreign key (user_id) references users (id)
            on delete cascade
);

