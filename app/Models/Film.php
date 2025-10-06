<?php

namespace App\Models;

class Film extends Model
{
    public string $title;
    public int|null $release_year = null;
    public int|null $duration_minutes = null;
    public string|null $description = null;
    public int|null $language_id = null;
    public int|null $country_id = null;
    public int|null $genre_id = null;
    public float $rating_avg = 0.00;
    public bool $subtitles = false;
    public int|null $studio_id = null;
    public string|null $film_cover = null; // Assuming storing as base64 or a path string, not blob directly

    protected static $table = "films";

    public function __construct(
        string $title = "",
        ?int $release_year = null,
        ?int $duration_minutes = null,
        ?string $description = null,
        ?int $language_id = null,
        ?int $country_id = null,
        ?int $genre_id = null,
        float $rating_avg = 0.00,
        bool $subtitles = false,
        ?int $studio_id = null,
        ?string $film_cover = null
    ) {
        parent::__construct();
        $this->title = $title;
        $this->release_year = $release_year;
        $this->duration_minutes = $duration_minutes;
        $this->description = $description;
        $this->language_id = $language_id;
        $this->country_id = $country_id;
        $this->genre_id = $genre_id;
        $this->rating_avg = $rating_avg;
        $this->subtitles = $subtitles;
        $this->studio_id = $studio_id;
        $this->film_cover = $film_cover;
    }
}
