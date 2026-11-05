<?php

header('Content-Type: application/json');

echo json_encode([
    'php_version' => PHP_VERSION,
    'php_extensions' => get_loaded_extensions(),
]);
