<?php
declare(strict_types=1); // A strict_types deklaráció a fájl legelső sorában kell legyen

class Database {
    private static ?PDO $pdo = null;

    // Statikus metódus, hogy a PDO kapcsolatot egyedileg elérhessük
    public static function getConnection(): PDO {
        // Ha nincs már kapcsolat, akkor létrehozzuk
        if (self::$pdo === null) {
            try {
                // Adatbázis kapcsolat létrehozása
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=film_nyilvantarto', // adatbázis elérési út
                    'root', // felhasználónév
                    '', // jelszó (helyi gépen általában üres)
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // hibakezelés
                );
            } catch (PDOException $e) {
                // Ha nem sikerül a kapcsolat, akkor hibaüzenet
                die("Adatbázis kapcsolat hiba: " . $e->getMessage());
            }
        }

        // Visszaadjuk a meglévő PDO kapcsolatot
        return self::$pdo;
    }
}
