<?php

function csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">' . "\n";
}

function csrf_validate(string $token): bool
{
    return hash_equals($token, csrf_token());
}
