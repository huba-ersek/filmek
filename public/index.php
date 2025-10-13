<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/FilmController.php';

$controller = new FilmController();

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        if (!$id) die("Hiányzó ID.");
        $controller->edit($id);
        break;
    case 'delete':
        if (!$id) die("Hiányzó ID.");
        $controller->delete($id);
        break;
    default:
        $controller->index();
        break;
}
