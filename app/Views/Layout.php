<?php

namespace App\Views;

use App\Database\Install;

class Layout
{
    public static function header($title = "Filmek")
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$title</title>
        </head>
        <body>
        HTML;
        self::navbar();
        self::installButton();
        self::handleMessages();
        echo '<div class="container">';
    }
    public static function handleMessages()
    {
        // TODO
    }
    public static function navbar()
    {
        echo <<<HTML
        <nav class="navbar">
            <ul class="nav-list">
                <li class="nav-button"><a href="/"><button style="button" title="Kezdőlap">Kezdőlap</button></a></li>
                <li class="nav-button"><a href="/filmek"><button style="button" title="Filmek">Filmek</button></a></li>
            </ul>
        </nav>
        HTML;
    }
    public static function installButton()
    {
        $install = new Install();
        if ($install->dbExists()) return;
        echo <<<HTML
        <form method="post" action="/install">
            <button type="submit" name="btn-install">Adatbázis telepítése</button>
        </form>
        HTML;
    }
    public static function sidebar()
    {
        echo <<<HTML
        <aside>
            <h3>Sidebar</h3>
        </aside>
        HTML;
    }
    public static function footer()
    {
        echo <<<HTML
        </div>
        <footer>
            <hr>
            <p>2025 &copy; Érsek Huba</p>
        </footer>
        </body>
        </html>
        HTML;
    }
}