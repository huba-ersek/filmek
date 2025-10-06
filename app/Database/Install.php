<?php

namespace App\Database;

use App\Views\Display;
use Exception;

use PDO;
use PDOException;

class Install
{
    protected const DEFAULT_CONFIG = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => null,
        'database' => 'film_nyilvantarto',
    ];

    private PDO $pdo;

    public function __construct(array $config = [])
    {
        $host = $config['host'] ?? self::DEFAULT_CONFIG['host'];
        $user = $config['user'] ?? self::DEFAULT_CONFIG['user'];
        $password = $config['password'] ?? self::DEFAULT_CONFIG['password'];

        try {
            $dsn = "mysql:host=$host;dbname=;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exception mode
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch as associative array
                PDO::ATTR_EMULATE_PREPARES => false,                  // Use real prepared statements
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \RuntimeException("Database connection error.");
        }
    }

    public function execSql(string $sql, array $params = []): bool|int|array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            // Handle INSERT (return last insert ID)
            if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
                return (int) $this->pdo->lastInsertId();
            }

            // Handle SELECT (return results)
            if (str_starts_with(strtoupper(trim($sql)), 'SELECT')) {
                return $stmt->fetchAll() ?: [];
            }

            // Handle UPDATE / DELETE
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            error_log($e->getMessage());
            return false;
        }
    }

    public function dbExists(): bool
    {
        try {

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", self::DEFAULT_CONFIG['database']);
            $result = $this->execSql($query);

            if ($result === false) {
                throw new Exception('Lekérdezési hiba: ' . $_SESSION['error_message']);
            }
            return count($result) > 0;
        }
        catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            error_log($e->getMessage());

            return false;
        }

    }

    public function install(): void
    {
        if (!$this->createDatabase() || !$this->populateDatabase())
            Display::message("Creating and populating database failed.", "error");
    }

    private function createDatabase(): bool
    {
        if ($this->dbExists()) return true;
        $file = fopen("..\\..\\film_nyilvantarto_cr.sql");
        $data = fread($file, filesize("..\\..\\film_nyilvantarto_cr.sql"));
        fclose($file);
        if ($data !== false)
        {
            if ($this->execSql($data) === false)
            {
                Display::message("Error while creating database.", 'error');
                return false;
            }
        }
        return true;
    }

    private function populateDatabase(): bool
    {
        if (
            !$this->populateCountries()
            || !$this->populateLanguages()
            || !$this->populateGenres()
            || !$this->populateStudios()
            || !$this->populatePeople()
            || !$this->populateRoles()
            || !$this->populateFilms()
            || !$this->populateFilmPeople()
        ) return false;
        return true;
    }

    private function populateCountries(): bool
    {
        // Countries
        $countries = [
            'USA',
            'UK',
            'Germany',
            'France',
            'Japan',
            'Italy',
            'Spain'
        ];

        foreach ($countries as $country) {
            $sql = "INSERT INTO countries (country) VALUES (:country)";
            $result = $this->execSql($sql, ['country' => $country]);
            if ($result === false) {
                Display::message("Error inserting country: $country", "error");
                return false;
            }
        }
        return true;
    }

    private function populateLanguages(): bool
    {
        // Languages
        $languages = [
            'English',
            'German',
            'French',
            'Spanish',
            'Japanese',
            'Italian'
        ];

        foreach ($languages as $language) {
            $sql = "INSERT INTO languages (language) VALUES (:language)";
            $result = $this->execSql($sql, ['language' => $language]);
            if ($result === false) {
                Display::message("Error inserting language: $language", 'error');
                return false;
            }
        }
        return true;
    }

    private function populateGenres(): bool
    {
        // Genres
        $genres = [
            'Action',
            'Comedy',
            'Drama',
            'Thriller',
            'Horror',
            'Sci-Fi'
        ];

        foreach ($genres as $genre) {
            $sql = "INSERT INTO genres (name) VALUES (:name)";
            $result = $this->execSql($sql, ['name' => $genre]);
            if ($result === false) {
                Display::message("Error inserting genre: $genre", "error");
                return false;
            }
        }
        return true;
    }

    private function populateStudios(): bool
    {
        // Studios
        $studios = [
            'Warner Bros',
            'Universal Pictures',
            'Paramount Pictures',
            '20th Century Fox',
            'Sony Pictures',
            'Disney'
        ];

        foreach ($studios as $studio) {
            $sql = "INSERT INTO studios (studio) VALUES (:studio)";
            $result = $this->execSql($sql, ['studio' => $studio]);
            if ($result === false) {
                Display::message("Error inserting studio: $studio", 'error');
                return false;
            }
        }
        return true;
    }

    private function populatePeople(): bool
    {
        // People (Actors)
        $people = [
            ['name' => 'Leonardo DiCaprio', 'birth_date' => '1974-11-11', 'nationality' => 'USA'],
            ['name' => 'Matt Damon', 'birth_date' => '1970-10-08', 'nationality' => 'USA'],
            ['name' => 'Morgan Freeman', 'birth_date' => '1937-06-01', 'nationality' => 'USA'],
            ['name' => 'Scarlett Johansson', 'birth_date' => '1984-11-22', 'nationality' => 'USA']
        ];

        foreach ($people as $person) {
            $sql = "INSERT INTO people (name, birth_date, nationality) VALUES (:name, :birth_date, :nationality)";
            $result = $this->execSql($sql, [
                'name' => $person['name'],
                'birth_date' => $person['birth_date'],
                'nationality' => $person['nationality']
            ]);
            if ($result === false) {
                Display::message("Error inserting person: {$person['name']}", 'error');
                return false;
            }
        }
        return true;
    }

    private function populateRoles(): bool
    {
        // Roles
        $roles = [
            'Actor',
            'Director',
            'Producer',
            'Writer'
        ];

        foreach ($roles as $role) {
            $sql = "INSERT INTO roles (role_name) VALUES (:role_name)";
            $result = $this->execSql($sql, ['role_name' => $role]);
            if ($result === false) {
                Display::message("Error inserting role: $role", 'error');
                return false;
            }
        }
        return true;
    }

    private function populateFilms(): bool
    {
        // Films
        $films = [
            ['title' => 'Inception', 'release_year' => 2010, 'duration_minutes' => 148, 'rating_avg' => 8.8, 'subtitles' => 1, 'studio_id' => 1, 'language_id' => 1, 'country_id' => 1, 'genre_id' => 1],
            ['title' => 'The Dark Knight', 'release_year' => 2008, 'duration_minutes' => 152, 'rating_avg' => 9.0, 'subtitles' => 1, 'studio_id' => 2, 'language_id' => 1, 'country_id' => 1, 'genre_id' => 1],
            ['title' => 'Forrest Gump', 'release_year' => 1994, 'duration_minutes' => 142, 'rating_avg' => 8.8, 'subtitles' => 1, 'studio_id' => 3, 'language_id' => 1, 'country_id' => 1, 'genre_id' => 2]
        ];

        foreach ($films as $film) {
            $sql = "INSERT INTO films (title, release_year, duration_minutes, rating_avg, subtitles, studio_id, language_id, country_id, genre_id)
                    VALUES (:title, :release_year, :duration_minutes, :rating_avg, :subtitles, :studio_id, :language_id, :country_id, :genre_id)";
            $result = $this->execSql($sql, [
                'title' => $film['title'],
                'release_year' => $film['release_year'],
                'duration_minutes' => $film['duration_minutes'],
                'rating_avg' => $film['rating_avg'],
                'subtitles' => $film['subtitles'],
                'studio_id' => $film['studio_id'],
                'language_id' => $film['language_id'],
                'country_id' => $film['country_id'],
                'genre_id' => $film['genre_id']
            ]);
            if ($result === false) {
                Display::message("Error inserting film: {$film['title']}", 'error');
                return false;
            }
        }
        return true;
    }

    private function populateFilmPeople(): bool
    {
        // Film_people
        $film_people = [
            ['film_id' => 1, 'person_id' => 1, 'role_id' => 1, 'character_name' => 'Dom Cobb'],
            ['film_id' => 2, 'person_id' => 2, 'role_id' => 1, 'character_name' => 'Bruce Wayne'],
            ['film_id' => 3, 'person_id' => 3, 'role_id' => 1, 'character_name' => 'Forrest Gump']
        ];

        foreach ($film_people as $fp) {
            $sql = "INSERT INTO film_people (film_id, person_id, role_id, character_name)
                    VALUES (:film_id, :person_id, :role_id, :character_name)";
            $result = $this->execSql($sql, [
                'film_id' => $fp['film_id'],
                'person_id' => $fp['person_id'],
                'role_id' => $fp['role_id'],
                'character_name' => $fp['character_name']
            ]);
            if ($result === false) {
                Display::message("Error inserting film_people for film_id {$fp['film_id']} and person_id {$fp['person_id']}", 'error');
                return false;
            }
        }
        return true;
    }
}
