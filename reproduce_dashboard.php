<?php

use App\Models\User;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "Reproduction du dashboard pour l'utilisateur 6..." . PHP_EOL;

$u = User::find(6);
if (!$u) {
    echo "User 6 not found" . PHP_EOL;
    return;
}

Auth::login($u);
$request = Request::create('/dashboard', 'GET');
$controller = new DashboardController();

try {
    $response = $controller->index($request);
    echo "Dashboard index() executed successfully." . PHP_EOL;

    // Si c'est une vue, on peut essayer de la rendre pour voir si le problème est dans la vue
    if ($response instanceof \Illuminate\View\View) {
        echo "Rendering view..." . PHP_EOL;
        $response->render();
        echo "View rendered successfully." . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo "Trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
