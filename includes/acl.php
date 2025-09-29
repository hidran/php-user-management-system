<?php

const ROLE_USER = 'user';
const ROLE_ADMIN = 'admin';
const ROLE_EDITOR = 'editor';

function is_user_logged_in(): bool
{
    return !empty($_SESSION['user_logged_in']);
}

function get_user_login_data(): array
{
    return $_SESSION['user_data'] ?? [];
}

function get_user_role(): string
{
    return get_user_login_data()['role_type'] ?? 'user';
}

function user_can_update(): bool
{
    return in_array(get_user_role(), [ROLE_ADMIN, ROLE_EDITOR]);
}

function user_can_delete(): bool
{
    return get_user_role() === ROLE_ADMIN;
}
