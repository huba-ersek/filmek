<?php
namespace App\Controllers;

use App\Database\Database;
use App\Models\Film;
use App\Views\Display;
use Error;

class FilmController extends Controller {

    public function __construct()
    {
        $film = new Film();
        parent::__construct($film);
    }

    public function index(): void
    {
        $films = $this->model->all(['order_by' => ['title'],  // Changed order_by to title, as film_number doesn't exist
            'direction' => ['ASC']]);
        $this->render('films/index', 
            ['films' => $films]);
    }

    public function create(): void
    {
        $this->render('films/create');
    }
    
    public function edit(int $id): void
    {
        $film = $this->model->find($id);
        if (!$film) {
            // Handle invalid ID gracefully
            $_SESSION['warning_message'] = "Érvénytelen azonosító: $id.";
            $this->redirect('/films');
        }
        $this->render('films/edit', ['film' => $film]);
    }

    // Returns whether or not $data exists in the database
    function validate(array $data): bool
    {
        $films = $this->model->all();
        foreach ($films as $film)
        {
            if ($film->title == $data["title"]) // Using title instead of room_number
            {
                return false;
            }
        }
        return true;
    }

    public function save(array $data): void
    {
        if (!$this->validate($data))
        {
            $_SESSION['warning_message'] = "Már létezik ilyen film.";
            $this->redirect('/films');
        }
        if (empty($data['title']) /* || empty other required fields? */) {
            $_SESSION['warning_message'] = "Üres mező."; 
            $this->redirect('/films'); // Redirect if input is invalid
        }
        // Use the existing model instance
        assert($this->model instanceof Film);

        // Set all relevant film properties from $data
        $this->model->title = $data['title'];
        $this->model->release_year = $data['release_year'] ?? null;
        $this->model->duration_minutes = $data['duration_minutes'] ?? null; 
        $this->model->description = $data['description'] ?? null; 
        $this->model->language_id = $data['language_id'] ?? null; 
        $this->model->country_id = $data['country_id'] ?? null; 
        $this->model->genre_id = $data['genre_id'] ?? null; 
        $this->model->rating_avg = $data['rating_avg'] ?? 0.0; 
        $this->model->subtitles = $data['subtitles'] ?? false; 
        $this->model->studio_id = $data['studio_id'] ?? null; 
        $this->model->film_cover = $data['film_cover'] ?? null; 

        $this->model->create();
        $this->redirect('/films');
    }

    public function update(int $id, array $data): void
    {
        if (!$this->validate($data))
        {
            $_SESSION['warning_message'] = "Már létezik ilyen film.";
            $this->redirect('/films');
        }
        $film = $this->model->find($id);
        if (!$film || empty($data['title'])) {
            // Handle invalid ID or data
            $this->redirect('/films');
        }
        assert($film instanceof Film);

        // Update film properties
        $film->title = $data['title'];
        $film->release_year = $data['release_year'] ?? null; 
        $film->duration_minutes = $data['duration_minutes'] ?? null; 
        $film->description = $data['description'] ?? null; 
        $film->language_id = $data['language_id'] ?? null; 
        $film->country_id = $data['country_id'] ?? null; 
        $film->genre_id = $data['genre_id'] ?? null; 
        $film->rating_avg = $data['rating_avg'] ?? 0.0; 
        $film->subtitles = $data['subtitles'] ?? false; 
        $film->studio_id = $data['studio_id'] ?? null; 
        $film->film_cover = $data['film_cover'] ?? null; 

        $film->update();
        $this->redirect('/films');
    }

    function show(int $id): void
    {
        $film = $this->model->find($id);
        if (!$film) {
            $_SESSION['warning_message'] = "Érvénytelen azonosító: $id.";
            $this->redirect('/films'); // Handle invalid ID
        }
        $this->render('films/show', ['film' => $film]);
    }

    function delete(int $id): void
    {
        $film = $this->model->find($id);
        if ($film) {
            $result = $film->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/films'); // Redirect regardless of success
    }

}