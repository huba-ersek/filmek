<?php ob_start(); ?>
<h1>Filmek listája</h1>

<form method="get">
    <input type="hidden" name="action" value="index">
    <input type="text" name="actor" placeholder="Színész" value="<?= htmlspecialchars($_GET['actor'] ?? '') ?>">
    <input type="text" name="studio" placeholder="Stúdió" value="<?= htmlspecialchars($_GET['studio'] ?? '') ?>">
    <input type="text" name="genre" placeholder="Kategória" value="<?= htmlspecialchars($_GET['genre'] ?? '') ?>">
    <button type="submit">Szűrés</button>
    <a href="index.php" class="button">Szűrő törlése</a>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Cím</th><th>Év</th><th>Stúdió</th><th>Kategória</th><th>Nyelv</th><th>Ország</th><th>Felirat</th><th>Átlag Értékelés</th><th>Leírás</th><th>Időtartam (perc)</th><th>Színészek</th><th>Rendező</th><th>Borító</th><th>Műveletek</th>
    </tr>
    <?php foreach ($films as $film): ?>
    <tr>
        <td><?= htmlspecialchars($film['title']) ?></td>
        <td><?= htmlspecialchars($film['release_year']) ?></td>
        <td><?= htmlspecialchars($film['studio']) ?></td>
        <td><?= htmlspecialchars($film['genre']) ?></td> <!-- Kategória -->
        <td><?= htmlspecialchars($film['language']) ?></td> <!-- Nyelv -->
        <td><?= htmlspecialchars($film['country']) ?></td> <!-- Ország -->
        <td><?= $film['subtitles'] ? 'Igen' : 'Nem' ?></td> <!-- Felirat -->
        <td><?= htmlspecialchars($film['rating_avg']) ?></td> <!-- Átlag Értékelés -->
        <td><?= htmlspecialchars($film['description']) ?></td> <!-- Leírás -->
        <td><?= htmlspecialchars($film['duration_minutes']) ?></td> <!-- Időtartam (perc) -->
        <td><?= htmlspecialchars($film['actors']) ?></td> <!-- Színészek -->
        <td><?= htmlspecialchars($film['director']) ?></td> <!-- Rendező -->
        <td>
            <?php if (!empty($film['film_cover'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($film['film_cover']) ?>" alt="Film borító" style="max-width: 100px; max-height: 100px; border: 2px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <?php else: ?>
                Nincs borító
            <?php endif; ?>
        </td>
        <td>
            <a href="index.php?action=edit&id=<?= $film['id'] ?>">Szerkeszt</a> |
            <a href="index.php?action=delete&id=<?= $film['id'] ?>">Töröl</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
