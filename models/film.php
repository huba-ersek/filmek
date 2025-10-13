<?php
declare(strict_types=1);

class Film {
    public ?int $id = null;
    public string $title;
    public int $release_year;
    public int $duration_minutes;
    public string $description;
    public int $language_id;
    public int $country_id;
    public int $genre_id;
    public float $rating_avg;
    public bool $subtitles;
    public int $studio_id;
    public ?string $film_cover = null;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Ha az érték numerikus, próbáljuk meg integerre konvertálni
                if ($key === 'release_year' || $key === 'language_id' || $key === 'country_id' || $key === 'genre_id' || $key === 'studio_id' || $key === 'duration_minutes') {
                    $this->$key = (int)$value; // Az összes integer típusú mezőt konvertáljuk
                } elseif ($key === 'rating_avg') {
                    $this->$key = (float)$value; // A rating_avg float típusú, konvertáljuk
                } elseif ($key === 'subtitles') {
                    $this->$key = (bool)$value; // A subtitles bool típusú
                } else {
                    $this->$key = $value;
                }
            }
        }
    }
}
