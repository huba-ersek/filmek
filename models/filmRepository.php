<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Film.php';

class FilmRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findAll(array $filters = []): array {
        $filterSql = "";
        $params = [];

        if (!empty($filters['actor'])) {
            $filterSql .= " AND p.name LIKE :actor";
            $params[':actor'] = '%' . $filters['actor'] . '%';
        }
        if (!empty($filters['studio'])) {
            $filterSql .= " AND s.studio LIKE :studio";
            $params[':studio'] = '%' . $filters['studio'] . '%';
        }
        if (!empty($filters['genre'])) {
            $filterSql .= " AND g.name LIKE :genre";
            $params[':genre'] = '%' . $filters['genre'] . '%';
        }
        if (!empty($filters['id'])) {
            $filterSql .= " AND f.film_id = :id";
            $params[':id'] = $filters['id'];
        }

        $sql = "
            SELECT
                f.film_id AS id, f.title, f.release_year, f.duration_minutes, f.description,
                f.rating_avg, f.subtitles, f.film_cover,
                l.language, g.name AS genre, s.studio, c.country,
                GROUP_CONCAT(DISTINCT CONCAT(p.name, IFNULL(CONCAT(' (', fp.character_name, ')'), '')) SEPARATOR ', ') AS actors,
                GROUP_CONCAT(DISTINCT d.name SEPARATOR ', ') AS director
            FROM films f
            LEFT JOIN languages l ON f.language_id = l.lang_id
            LEFT JOIN genres g ON f.genre_id = g.genre_id
            LEFT JOIN countries c ON f.country_id = c.country_id
            LEFT JOIN studios s ON f.studio_id = s.studio_id
            LEFT JOIN film_people fp ON f.film_id = fp.film_id
            LEFT JOIN people p ON fp.person_id = p.person_id AND fp.role_id = 2
            LEFT JOIN film_directors fd ON f.film_id = fd.film_id
            LEFT JOIN directors d ON fd.director_id = d.director_id
            WHERE 1=1 $filterSql
            GROUP BY f.film_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM films WHERE film_id = ?");
        $stmt->execute([$id]);
        $film = $stmt->fetch();
        return $film ?: null;
    }

    public function create(Film $film): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO films (title, release_year, duration_minutes, description, language_id, country_id, genre_id, rating_avg, subtitles, studio_id, film_cover)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $film->title,
            $film->release_year,
            $film->duration_minutes,
            $film->description,
            $film->language_id,
            $film->country_id,
            $film->genre_id,
            $film->rating_avg,
            $film->subtitles ? 1 : 0,
            $film->studio_id,
            $film->film_cover
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, Film $film): bool {
        $stmt = $this->pdo->prepare("
            UPDATE films SET
                title=?, release_year=?, duration_minutes=?, description=?,
                language_id=?, country_id=?, genre_id=?, rating_avg=?, subtitles=?, studio_id=?, film_cover=?
            WHERE film_id=?
        ");
        return $stmt->execute([
            $film->title,
            $film->release_year,
            $film->duration_minutes,
            $film->description,
            $film->language_id,
            $film->country_id,
            $film->genre_id,
            $film->rating_avg,
            $film->subtitles ? 1 : 0,
            $film->studio_id,
            $film->film_cover,
            $id
        ]);
    }

    public function delete(int $id): bool {
        // Törlés előtt töröld a kapcsolódó film_people és film_directors rekordokat, hogy ne legyen FK hiba
        $stmt1 = $this->pdo->prepare("DELETE FROM film_people WHERE film_id = ?");
        $stmt1->execute([$id]);
        $stmt2 = $this->pdo->prepare("DELETE FROM film_directors WHERE film_id = ?");
        $stmt2->execute([$id]);

        $stmt3 = $this->pdo->prepare("DELETE FROM films WHERE film_id = ?");
        return $stmt3->execute([$id]);
    }

    public function getDropdownData(): array {
        return [
            'languages' => $this->pdo->query("SELECT * FROM languages")->fetchAll(),
            'countries' => $this->pdo->query("SELECT * FROM countries")->fetchAll(),
            'genres' => $this->pdo->query("SELECT * FROM genres")->fetchAll(),
            'studios' => $this->pdo->query("SELECT * FROM studios")->fetchAll(),
            'people' => $this->getPeople(),
            'directors' => $this->getDirectors()
        ];
    }

    public function getPeople(): array {
        return $this->pdo->query("SELECT person_id, name FROM people ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmPeople(int $filmId, int $roleId): array {
        $stmt = $this->pdo->prepare("SELECT person_id FROM film_people WHERE film_id = ? AND role_id = ?");
        $stmt->execute([$filmId, $roleId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateFilmPeople(int $filmId, int $roleId, array $personIds): void {
        // Delete existing
        $stmt = $this->pdo->prepare("DELETE FROM film_people WHERE film_id = ? AND role_id = ?");
        $stmt->execute([$filmId, $roleId]);

        // Insert new
        if (!empty($personIds)) {
            $stmt = $this->pdo->prepare("INSERT INTO film_people (film_id, person_id, role_id) VALUES (?, ?, ?)");
            foreach ($personIds as $personId) {
                $stmt->execute([$filmId, $personId, $roleId]);
            }
        }
    }

    public function createPerson(string $name): int {
        $stmt = $this->pdo->prepare("INSERT INTO people (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->pdo->lastInsertId();
    }

    public function getDirectors(): array {
        return $this->pdo->query("SELECT director_id, name FROM directors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmDirectors(int $filmId): array {
        $stmt = $this->pdo->prepare("SELECT director_id FROM film_directors WHERE film_id = ?");
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateFilmDirectors(int $filmId, array $directorIds): void {
        // Delete existing
        $stmt = $this->pdo->prepare("DELETE FROM film_directors WHERE film_id = ?");
        $stmt->execute([$filmId]);

        // Insert new
        if (!empty($directorIds)) {
            $stmt = $this->pdo->prepare("INSERT INTO film_directors (film_id, director_id) VALUES (?, ?)");
            foreach ($directorIds as $directorId) {
                $stmt->execute([$filmId, $directorId]);
            }
        }
    }

    public function createDirector(string $name): int {
        $stmt = $this->pdo->prepare("INSERT INTO directors (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->pdo->lastInsertId();
    }
}
