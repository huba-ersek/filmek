<?php ob_start(); ?>

<h1>Film szerkesztése</h1>

<form method="post" enctype="multipart/form-data">
    <label>Cím:<br>
        <input type="text" name="title" required value="<?= htmlspecialchars($filmData['title']) ?>">
    </label><br>

    <label>Év:<br>
        <input type="number" name="release_year" required value="<?= htmlspecialchars($filmData['release_year']) ?>">
    </label><br>

    <label>Leírás:<br>
        <textarea name="description" rows="4"><?= htmlspecialchars($filmData['description']) ?></textarea>
    </label><br>

    <label>Nyelv:<br>
        <select name="language_id" required>
            <?php foreach ($data['languages'] as $lang): ?>
                <option value="<?= $lang['lang_id'] ?>" <?= $lang['lang_id'] == $filmData['language_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($lang['language']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Ország:<br>
        <select name="country_id" required>
            <?php foreach ($data['countries'] as $country): ?>
                <option value="<?= $country['country_id'] ?>" <?= $country['country_id'] == $filmData['country_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($country['country']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Kategória:<br>
        <select name="genre_id" required>
            <?php foreach ($data['genres'] as $genre): ?>
                <option value="<?= $genre['genre_id'] ?>" <?= $genre['genre_id'] == $filmData['genre_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Stúdió:<br>
        <select name="studio_id" required>
            <?php foreach ($data['studios'] as $studio): ?>
                <option value="<?= $studio['studio_id'] ?>" <?= $studio['studio_id'] == $filmData['studio_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($studio['studio']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Rendezők:<br>
        <select name="director_ids[]" multiple size="5">
            <?php foreach ($data['directors'] as $director): ?>
                <option value="<?= $director['director_id'] ?>" <?= in_array($director['director_id'], $filmData['current_director_ids']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($director['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><small>Több rendező kijelöléséhez tartsd lenyomva a Ctrl billentyűt.</small>
    </label><br>

    <label>Új rendezők hozzáadása:<br>
        <input type="text" name="new_director_names[]" placeholder="Rendező neve 1"><br>
        <input type="text" name="new_director_names[]" placeholder="Rendező neve 2"><br>
        <input type="text" name="new_director_names[]" placeholder="Rendező neve 3"><br>
    </label><br>

    <label>Színészek:<br>
        <select name="actor_ids[]" multiple size="10">
            <?php foreach ($data['people'] as $person): ?>
                <option value="<?= $person['person_id'] ?>" <?= in_array($person['person_id'], $filmData['current_actor_ids']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($person['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><small>Több színész kijelöléséhez tartsd lenyomva a Ctrl billentyűt.</small>
    </label><br>

    <label>Új színészek hozzáadása:<br>
        <input type="text" name="new_actor_names[]" placeholder="Színész neve 1"><br>
        <input type="text" name="new_actor_names[]" placeholder="Színész neve 2"><br>
        <input type="text" name="new_actor_names[]" placeholder="Színész neve 3"><br>
    </label><br>

    <label>Film borító:<br>
        <input type="file" name="film_cover" accept="image/*">
        <?php if (!empty($filmData['film_cover'])): ?>
            <br><small>Jelenlegi borító: <img src="data:image/jpeg;base64,<?= base64_encode($filmData['film_cover']) ?>" alt="Film borító" style="max-width: 100px; max-height: 100px;"></small>
        <?php endif; ?>
    </label><br>

    <label>Feliratos:<br>
        <input type="checkbox" name="subtitles" <?= $filmData['subtitles'] ? 'checked' : '' ?>>
    </label><br>

    <label>Átlagos értékelés:<br>
        <input type="number" step="0.1" min="0" max="10" name="rating_avg" value="<?= htmlspecialchars($filmData['rating_avg']) ?>" required>
    </label><br>

    <label>Időtartam (perc):<br>
        <input type="number" name="duration_minutes" required value="<?= htmlspecialchars($filmData['duration_minutes']) ?>">
    </label><br>

    <button type="submit">Mentés</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
