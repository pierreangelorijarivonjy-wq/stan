<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController as BaseDashboardController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard data based on user role
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $baseController = new BaseDashboardController();

        if ($user->hasRole('student')) {
            $data = $this->getStudentDataFromBase($baseController);
        } elseif ($user->hasRole('comptable')) {
            $data = $this->getComptableDataFromBase($baseController);
        } elseif ($user->hasRole('scolarite')) {
            $data = $this->getScolariteDataFromBase($baseController);
        } else {
            $data = $this->getAdminDataFromBase($baseController);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
            'dashboard' => $data,
        ]);
    }

    private function getStudentDataFromBase($controller)
    {
        return $controller->index(request())->getData()['data'] ?? [];
    }

    private function getComptableDataFromBase($controller)
    {
        return $controller->index(request())->getData()['data'] ?? [];
    }

    private function getScolariteDataFromBase($controller)
    {
        return $controller->index(request())->getData()['data'] ?? [];
    }

    private function getAdminDataFromBase($controller)
    {
        return $controller->index(request())->getData()['data'] ?? [];
    }
}
