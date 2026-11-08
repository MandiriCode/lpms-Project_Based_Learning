<?php
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
function current_user(): array
{
    return $_SESSION['user'] ?? [];
}
function role(): string
{
    return current_user()['role'] ?? '';
}
function require_role(array $roles): void
{
    if (!in_array(role(), $roles, true)) {
        http_response_code(403);
        exit('Akses ditolak. Anda tidak memiliki hak akses.');
    }
}
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void
{
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        !hash_equals($_SESSION['csrf'] ?? '', $_POST['_token'] ?? '')
    ) {
        http_response_code(419);
        exit('Token formulir tidak valid. Muat ulang halaman.');
    }
}
function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [$message, $type];
}
function show_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        [$m, $t] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert ' . e($t) . '">' . e($m) . '</div>';
    }
}
function post(string $key, string $default = ''): string
{
    return trim((string)($_POST[$key] ?? $default));
}
function date_id(): string
{
    return date('Y-m-d H:i:s');
}
verify_csrf();
