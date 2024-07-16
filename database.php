<?php
class Database {
    private $db;

    public function __construct($filename) {
        $this->db = new SQLite3($filename);
        $this->createTable();
    }

    private function createTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS urls (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            original_url TEXT NOT NULL,
            short_code TEXT NOT NULL UNIQUE
        )");
    }

    public function insertUrl($original_url, $short_code) {
        $stmt = $this->db->prepare("INSERT INTO urls (original_url, short_code) VALUES (:original_url, :short_code)");
        $stmt->bindValue(':original_url', $original_url, SQLITE3_TEXT);
        $stmt->bindValue(':short_code', $short_code, SQLITE3_TEXT);
        return $stmt->execute();
    }

    public function getUrlByShortCode($short_code) {
        $stmt = $this->db->prepare("SELECT original_url FROM urls WHERE short_code = :short_code");
        $stmt->bindValue(':short_code', $short_code, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function getShortCodeByUrl($original_url) {
        $stmt = $this->db->prepare("SELECT short_code FROM urls WHERE original_url = :original_url");
        $stmt->bindValue(':original_url', $original_url, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
}
?>