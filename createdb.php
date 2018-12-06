<?php
    // Для создания файлов нужен доступ(chmod 777 /path/to project)
    $db = new SQLite3('test.db');

    $db->exec('CREATE TABLE IF NOT EXISTS formData (
        id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        subject STRING NOT NULL,
        text STRING NOT NULL,
        priority INTEGER NOT NULL,
        email STRING NOT NULL,
        pin INTEGER NOT NULL)');
?>