<?php
// config.php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'PMS45609588';

const CRS_BASE_URL = 'http://localhost:3000/'; // el teu webservice Node

function db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        die("Error connectant BD: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function crs_call(array $params): array {
    $url = CRS_BASE_URL . '?' . http_build_query($params);
    $raw = @file_get_contents($url);
    if ($raw === false) return ["ok" => false, "error" => "No es pot contactar amb el CRS", "url" => $url];

    $json = json_decode($raw, true);
    if (!is_array($json)) return ["ok" => false, "error" => "Resposta CRS no és JSON", "raw" => $raw, "url" => $url];

    return ["ok" => true, "data" => $json, "url" => $url];
}

function ensure_auth_table(): void {
    $sql = "
        CREATE TABLE IF NOT EXISTS WEB_USER (
            idWebUser INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            codiClient INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_webuser_client
                FOREIGN KEY (codiClient) REFERENCES CLIENT(codiClient)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    db()->query($sql);
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}