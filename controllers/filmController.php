<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/FilmRepository.php';
require_once __DIR__ . '/../models/Film.php';

class FilmController {
    private FilmRepository $repo;

    public function __construct() {
        $this->repo = new FilmRepository();
    }

    public function index(): void {
        $filters = [
            'actor' => $_GET['actor'] ?? '',
            'studio' => $_GET['studio'] ?? '',
            'genre' => $_GET['genre'] ?? '',
        ];
        $films = $this->repo->findAll($filters);
        require __DIR__ . '/../views/films/list.php';
    }

    public function create(): void {
        $data = $this->repo->getDropdownData();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = new Film($_POST);
            $film->subtitles = isset($_POST['subtitles']);
            if (isset($_FILES['film_cover']) && $_FILES['film_cover']['error'] === UPLOAD_ERR_OK) {
                $film->film_cover = file_get_contents($_FILES['film_cover']['tmp_name']);
            }
            $filmId = $this->repo->create($film);
            $directorIds = $_POST['director_ids'] ?? [];
            $actorIds = $_POST['actor_ids'] ?? [];
            if (isset($_POST['new_director_names'])) {
                foreach ($_POST['new_director_names'] as $name) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $newId = $this->repo->createDirector($name);
                        $directorIds[] = $newId;
                    }
                }
            }
            if (isset($_POST['new_actor_names'])) {
                foreach ($_POST['new_actor_names'] as $name) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $newId = $this->repo->createPerson($name);
                        $actorIds[] = $newId;
                    }
                }
            }
            $this->repo->updateFilmDirectors($filmId, $directorIds);
            $this->repo->updateFilmPeople($filmId, 2, $actorIds);
            header("Location: index.php");
            exit;
        }

        require __DIR__ . '/../views/films/create.php';
    }

    public function edit(int $id): void {
        $filmData = $this->repo->findById($id);
        if (!$filmData) {
            die("Film nem található.");
        }
        $data = $this->repo->getDropdownData();

        $currentDirectorIds = $this->repo->getFilmDirectors($id);
        $currentActorIds = $this->repo->getFilmPeople($id, 2);
        $filmData['current_director_ids'] = $currentDirectorIds;
        $filmData['current_actor_ids'] = $currentActorIds;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = new Film($_POST);
            $film->subtitles = isset($_POST['subtitles']);
            if (isset($_FILES['film_cover']) && $_FILES['film_cover']['error'] === UPLOAD_ERR_OK) {
                $film->film_cover = file_get_contents($_FILES['film_cover']['tmp_name']);
            } else {
                // Keep existing cover if no new file uploaded
                $existing = $this->repo->findById($id);
                $film->film_cover = $existing['film_cover'];
            }
            $this->repo->update($id, $film);

            $directorIds = $_POST['director_ids'] ?? [];
            $actorIds = $_POST['actor_ids'] ?? [];
            if (isset($_POST['new_director_names'])) {
                foreach ($_POST['new_director_names'] as $name) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $newId = $this->repo->createDirector($name);
                        $directorIds[] = $newId;
                    }
                }
            }
            if (isset($_POST['new_actor_names'])) {
                foreach ($_POST['new_actor_names'] as $name) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $newId = $this->repo->createPerson($name);
                        $actorIds[] = $newId;
                    }
                }
            }
            $this->repo->updateFilmDirectors($id, $directorIds);
            $this->repo->updateFilmPeople($id, 2, $actorIds);

            header("Location: index.php");
            exit;
        }

        require __DIR__ . '/../views/films/edit.php';
    }

    public function delete(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->repo->delete($id);
            header("Location: index.php");
            exit;
        }

        $film = $this->repo->findAll(['id' => $id])[0] ?? null;
        if (!$film) {
            die("Film nem található.");
        }
        require __DIR__ . '/../views/films/delete_confirm.php';
    }
}
