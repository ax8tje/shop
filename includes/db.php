<?php
require_once __DIR__ . '/autoload.php';
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'db' => 'shop',
    'user' => 'root',
    'pass' => 'password',
    'charset' => 'utf8mb4',
];

foreach ([
             'host' => 'DB_HOST',
             'port' => 'DB_PORT',
             'db' => 'DB_NAME',
             'user' => 'DB_USER',
             'pass' => 'DB_PASS',
             'charset' => 'DB_CHARSET',
         ] as $key => $env) {
    if (isset($_ENV[$env]) && $_ENV[$env] !== '') {
        $config[$key] = $_ENV[$env];
    } elseif (getenv($env)) {
        $config[$key] = getenv($env);
    }
}

$configFile = dirname(__DIR__) . '/config.php';
if (file_exists($configFile)) {
    $fileConfig = include $configFile;
    if (is_array($fileConfig)) {
        $config = array_merge($config, $fileConfig);
    }
}

$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db']};charset={$config['charset']}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
} catch (PDOException $e) {
    die('Błąd połączenia z bazą danych: ' . $e->getMessage());
}


function clearProductImages(PDO $pdo): void {
    $pdo->exec('TRUNCATE TABLE product_images');
}
