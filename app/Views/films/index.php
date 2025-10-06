<?php

$tableBody = "";
foreach ($films as $film) {
    $tableBody .= <<<HTML
            <tr>
                <td>{$film->id}</td>
                <td>{$film->title}</td>
                <td>{$film->release_year}</td>
                <td>{$film->duration_minutes}</td>
                <td>{$film->rating_avg}</td>
                <td>{$film->description}</td>
                <!--<td class='flex float-right'>
                    <form method='post' action='/films/edit'>
                        <input type='hidden' name='id' value='{$film->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                    </form>
                    <form method='post' action='/films'>
                        <input type='hidden' name='id' value='{$film->id}'>    
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                    </form>
                </td>-->
            </tr>
            HTML;
}

$html = <<<HTML
        <h1>Filmek</h1>
        <table id='admin-films-table' class='admin-films-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cím</th>
                    <th>Megjelenési év</th>
                    <th>Időtartam (perc)</th>
                    <th>Átlag értékelés</th>
                    <th>Leírás</th>
                    <!--<th>
                        <form method='post' action='/films/create'>
                            <button type="submit" name='btn-plus' title='Új'>
                                <i class='fa fa-plus plus'></i>&nbsp;Új</button>
                        </form>
                    </th>-->
                </tr>
            </thead>
             <tbody>%s</tbody>
            <tfoot>
            </tfoot>
        </table>
        HTML;

echo sprintf($html, $tableBody);