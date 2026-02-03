<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Ancien',
                'icon' => 'fas fa-user-graduate',
                'description' => 'Attribué aux étudiants inscrits depuis plus de 2 ans.',
                'color' => 'indigo',
            ],
            [
                'name' => 'Top Payeur',
                'icon' => 'fas fa-star',
                'description' => 'Toujours à jour dans ses paiements avant la date limite.',
                'color' => 'emerald',
            ],
            [
                'name' => 'Ambassadeur',
                'icon' => 'fas fa-medal',
                'description' => 'Aide activement les nouveaux étudiants sur la plateforme.',
                'color' => 'amber',
            ],
            [
                'name' => 'Vérifié',
                'icon' => 'fas fa-check-double',
                'description' => 'Identité et documents entièrement vérifiés par l\'administration.',
                'color' => 'blue',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
