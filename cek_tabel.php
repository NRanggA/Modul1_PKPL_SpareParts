<?php
// File: cek_tabel.php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek semua tabel
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll(PDO::FETCH_COLUMN);

    echo "Daftar tabel di database.sqlite:\n";
    foreach ($tables as $table) {
        echo "- " . $table . "\n";
    }

    if (in_array('sessions', $tables)) {
        echo "\n✅ Tabel 'sessions' DITEMUKAN.\n";
    } else {
        echo "\n❌ Tabel 'sessions' TIDAK DITEMUKAN!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
