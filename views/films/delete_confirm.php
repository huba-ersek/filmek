<?php ob_start(); ?>

<h1>Film törlése</h1>

<p>Biztosan törölni szeretnéd a következő filmet?</p>

<ul>
    <li><strong>Cím:</strong> <?= htmlspecialchars($film['title']) ?></li>
    <li><strong>Év:</strong> <?= htmlspecialchars($film['release_year']) ?></li>
    <li><strong>Leírás:</strong> <?= htmlspecialchars($film['description']) ?></li>
    <li><strong>Időtartam:</strong> <?= htmlspecialchars($film['duration_minutes']) ?> perc</li>
    <li><strong>Átlagos értékelés:</strong> <?= htmlspecialchars($film['rating_avg']) ?>/10</li>
    <li><strong>Feliratos:</strong> <?= $film['subtitles'] ? 'Igen' : 'Nem' ?></li>
    <li><strong>Nyelv:</strong> <?= htmlspecialchars($film['language']) ?></li>
    <li><strong>Ország:</strong> <?= htmlspecialchars($film['country']) ?></li>
    <li><strong>Kategória:</strong> <?= htmlspecialchars($film['genre']) ?></li>
    <li><strong>Stúdió:</strong> <?= htmlspecialchars($film['studio']) ?></li>
    <li><strong>Rendező(k):</strong> <?= htmlspecialchars($film['director']) ?></li>
    <li><strong>Színészek:</strong> <?= htmlspecialchars($film['actors']) ?></li>
    <?php if (!empty($film['film_cover'])): ?>
    <li><strong>Film borító:</strong> <img src="data:image/jpeg;base64,<?= base64_encode($film['film_cover']) ?>" alt="Film borító" style="max-width: 200px; max-height: 200px;"></li>
    <?php endif; ?>
</ul>

<form method="post">
    <button type="submit">Igen, törlés</button>
    <a href="index.php" class="button">Mégsem</a>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
