<?php
declare(strict_types=1);

const CH_HOST = '127.0.0.1';
const CH_PORT = 3306;
const CH_USER = 'root';
const CH_PASS = '';
const CH_DB   = 'CHANNEL45609588';

const PMS_HOST = '127.0.0.1';
const PMS_PORT = 3306;
const PMS_USER = 'root';
const PMS_PASS = '';
const PMS_DB   = 'PMS45609588';

const CRS_BASE_URL = 'http://localhost:3000/';

function ch_db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;
    $conn = new mysqli(CH_HOST, CH_USER, CH_PASS, CH_DB, CH_PORT);
    if ($conn->connect_error) die("Error BD Channel: " . $conn->connect_error);
    $conn->set_charset('utf8mb4');
    return $conn;
}

function pms_db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;
    $conn = new mysqli(PMS_HOST, PMS_USER, PMS_PASS, PMS_DB, PMS_PORT);
    if ($conn->connect_error) die("Error BD PMS: " . $conn->connect_error);
    $conn->set_charset('utf8mb4');
    return $conn;
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function crs_call(array $params): array {
    $url = CRS_BASE_URL . '?' . http_build_query($params);
    $raw = @file_get_contents($url);
    if ($raw === false) return ["ok" => false, "error" => "No es pot contactar amb el CRS", "url" => $url];

    $json = json_decode($raw, true);
    if (!is_array($json)) return ["ok" => false, "error" => "Resposta CRS no és JSON", "raw" => $raw, "url" => $url];

    return ["ok" => true, "data" => $json, "url" => $url];
}

function ch_disponibilitat_rang(int $codiHotel, string $codiTipus, string $entrada, string $sortida): ?array {
    $conn = ch_db();

    $nits = (int)floor((strtotime($sortida) - strtotime($entrada)) / 86400);
    if ($nits <= 0) return null;

    $sql = "
      SELECT
        COUNT(*) AS dies,
        MIN(CASE WHEN actiu=1 THEN (cupo - reservesChannel) ELSE -999999 END) AS minDisp,
        MIN(actiu) AS minActiu,
        SUM(CASE WHEN actiu=1 THEN preu ELSE 0 END) AS preuTotal
      FROM DISPONIBILITAT
      WHERE codiHotel = ?
        AND codiTipusHabitacio = ?
        AND data >= ?
        AND data < ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $codiHotel, $codiTipus, $entrada, $sortida);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) return null;
    if ((int)$row['dies'] !== $nits) return null;
    if ((int)$row['minActiu'] !== 1) return null;

    $minDisp = (int)$row['minDisp'];
    if ($minDisp < 0) $minDisp = 0;

    return ["nits" => $nits, "disponibles" => $minDisp, "preuTotal" => (float)$row['preuTotal']];
}

function ch_incrementa_reserva(int $codiHotel, string $codiTipus, string $entrada, string $sortida): bool {
    $conn = ch_db();
    $nits = (int)floor((strtotime($sortida) - strtotime($entrada)) / 86400);
    if ($nits <= 0) return false;

    $conn->begin_transaction();
    try {
        $sql = "
          UPDATE DISPONIBILITAT
          SET reservesChannel = reservesChannel + 1
          WHERE codiHotel = ?
            AND codiTipusHabitacio = ?
            AND data >= ?
            AND data < ?
            AND actiu = 1
            AND (cupo - reservesChannel) > 0
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $codiHotel, $codiTipus, $entrada, $sortida);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        if ($affected !== $nits) {
            $conn->rollback();
            return false;
        }
        $conn->commit();
        return true;
    } catch (Throwable $e) {
        $conn->rollback();
        return false;
    }
}

function ensure_web_user_table(): void {
    $conn = pms_db();
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
    $conn->query($sql);
}