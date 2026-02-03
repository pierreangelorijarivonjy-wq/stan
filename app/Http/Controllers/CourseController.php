<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $level = $request->input('level');
        $category = $request->input('category');

        $courses = $this->courses;

        // Filter by Search Query
        if ($query) {
            $courses = array_filter($courses, function ($course) use ($query) {
                $searchTerm = strtolower($query);
                return str_contains(strtolower($course['title']), $searchTerm) ||
                    str_contains(strtolower($course['description']), $searchTerm) ||
                    str_contains(strtolower($course['instructor']), $searchTerm);
            });
        }

        // Filter by Level
        if ($level) {
            $courses = array_filter($courses, function ($course) use ($level) {
                return $course['level'] === $level;
            });
        }

        // Filter by Category
        if ($category) {
            $courses = array_filter($courses, function ($course) use ($category) {
                return $course['category'] === $category;
            });
        }

        // Get unique levels and categories for filters
        $levels = array_unique(array_column($this->courses, 'level'));
        $categories = array_unique(array_column($this->courses, 'category'));

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $results = [];
            foreach ($courses as $slug => $course) {
                $results[] = [
                    'slug' => $slug,
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'instructor' => $course['instructor'],
                    'image' => $course['image'],
                    'level' => $course['level'],
                    'category' => $course['category'],
                    'progress' => $course['progress'],
                    'url' => route('course.show', $slug)
                ];
            }
            return response()->json(['courses' => $results]);
        }

        return view('courses.index', compact('courses', 'levels', 'categories'));
    }

    private $courses = [
        'mathematiques' => [
            'title' => 'Mathématiques Avancées',
            'description' => 'Maîtrisez l\'algèbre linéaire, le calcul différentiel et les probabilités.',
            'instructor' => 'Dr. Sarah Rakoto',
            'duration' => '12h 30m',
            'level' => 'Avancé',
            'category' => 'Sciences',
            'image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1200&h=600&fit=crop',
            'progress' => 65,
            'modules' => [
                [
                    'title' => 'Module 1: Algèbre Linéaire',
                    'lessons' => [
                        [
                            'id' => 1,
                            'title' => 'Introduction aux Matrices',
                            'duration' => '15:00',
                            'type' => 'video',
                            'video_url' => 'https://www.youtube.com/embed/fNk_zzaMoSs',
                            'resources' => [
                                ['title' => 'Slides du cours.pdf', 'type' => 'pdf', 'size' => '2.4 MB', 'url' => '#'],
                                ['title' => 'Exercices pratiques.zip', 'type' => 'zip', 'size' => '1.1 MB', 'url' => '#']
                            ]
                        ],
                        ['id' => 2, 'title' => 'Systèmes d\'Équations', 'duration' => '22:30', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/fNk_zzaMoSs'],
                        ['id' => 3, 'title' => 'Espaces Vectoriels', 'duration' => '18:45', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/fNk_zzaMoSs'],
                        [
                            'id' => 4,
                            'title' => 'Quiz Module 1',
                            'duration' => '10:00',
                            'type' => 'quiz',
                            'questions' => [
                                [
                                    'question' => 'Quelle est la dimension d\'une matrice 3x4 ?',
                                    'options' => ['3 lignes, 4 colonnes', '4 lignes, 3 colonnes', '7 éléments', '12 lignes'],
                                    'correct' => 0
                                ],
                                [
                                    'question' => 'Si A est une matrice identité, alors A * A = ?',
                                    'options' => ['2A', 'A', '0', 'A^2 (mais A^2 = A)'],
                                    'correct' => 1
                                ],
                                [
                                    'question' => 'Le déterminant d\'une matrice singulière est :',
                                    'options' => ['1', '-1', '0', 'Indéfini'],
                                    'correct' => 2
                                ]
                            ]
                        ],
                    ]
                ],
                [
                    'title' => 'Module 2: Calcul Différentiel',
                    'lessons' => [
                        ['id' => 5, 'title' => 'Limites et Continuité', 'duration' => '20:15', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/WUvTyaaNkzM'],
                        ['id' => 6, 'title' => 'Dérivées', 'duration' => '25:00', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/WUvTyaaNkzM'],
                        ['id' => 7, 'title' => 'Applications des Dérivées', 'duration' => '30:00', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/WUvTyaaNkzM'],
                    ]
                ]
            ]
        ],
        'physique' => [
            'title' => 'Physique Quantique',
            'description' => 'Comprendre les fondements de la mécanique quantique et de la relativité.',
            'instructor' => 'Prof. Jean Andria',
            'duration' => '10h 45m',
            'level' => 'Intermédiaire',
            'category' => 'Sciences',
            'image' => 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?w=1200&h=600&fit=crop',
            'progress' => 30,
            'modules' => [
                [
                    'title' => 'Module 1: Mécanique Classique',
                    'lessons' => [
                        [
                            'id' => 1,
                            'title' => 'Lois de Newton',
                            'duration' => '12:00',
                            'type' => 'video',
                            'video_url' => 'https://www.youtube.com/embed/kKKM8Y-u7ds',
                            'resources' => [
                                ['title' => 'Résumé des 3 lois.pdf', 'type' => 'pdf', 'size' => '1.8 MB', 'url' => '#'],
                                ['title' => 'Problèmes corrigés.pdf', 'type' => 'pdf', 'size' => '2.1 MB', 'url' => '#']
                            ]
                        ],
                        ['id' => 2, 'title' => 'Énergie et Travail', 'duration' => '18:30', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/kKKM8Y-u7ds'],
                    ]
                ],
                [
                    'title' => 'Module 2: Ondes et Lumière',
                    'lessons' => [
                        [
                            'id' => 3,
                            'title' => 'Nature de la Lumière',
                            'duration' => '15:45',
                            'type' => 'video',
                            'video_url' => 'https://www.youtube.com/embed/kKKM8Y-u7ds',
                            'resources' => [
                                ['title' => 'Expérience fentes Young.zip', 'type' => 'zip', 'size' => '5.4 MB', 'url' => '#']
                            ]
                        ],
                        ['id' => 4, 'title' => 'Interférences', 'duration' => '20:00', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/kKKM8Y-u7ds'],
                    ]
                ]
            ]
        ],
        'chimie' => [
            'title' => 'Chimie Organique',
            'description' => 'Exploration des structures, propriétés et réactions des composés organiques.',
            'instructor' => 'Dr. Marie Rasoa',
            'duration' => '14h 15m',
            'level' => 'Avancé',
            'category' => 'Sciences',
            'image' => 'https://images.unsplash.com/photo-1603126857599-f6e157fa2fe6?w=1200&h=600&fit=crop',
            'progress' => 0,
            'modules' => [
                [
                    'title' => 'Module 1: Structure Atomique',
                    'lessons' => [
                        [
                            'id' => 1,
                            'title' => 'Atomes et Molécules',
                            'duration' => '14:00',
                            'type' => 'video',
                            'video_url' => 'https://www.youtube.com/embed/PpkWe-lO8ks',
                            'resources' => [
                                ['title' => 'Tableau Périodique.pdf', 'type' => 'pdf', 'size' => '3.5 MB', 'url' => '#'],
                                ['title' => 'Modèles moléculaires 3D.zip', 'type' => 'zip', 'size' => '8.2 MB', 'url' => '#']
                            ]
                        ],
                        ['id' => 2, 'title' => 'Liaisons Chimiques', 'duration' => '19:30', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/PpkWe-lO8ks'],
                    ]
                ]
            ]
        ],
        'biologie' => [
            'title' => 'Biologie Cellulaire',
            'description' => 'Étude approfondie de la cellule, unité de base de la vie.',
            'instructor' => 'Prof. Paul Raja',
            'duration' => '11h 00m',
            'level' => 'Débutant',
            'category' => 'Sciences',
            'image' => 'https://images.unsplash.com/photo-1530026405186-ed1f139313f8?w=1200&h=600&fit=crop',
            'progress' => 10,
            'modules' => [
                [
                    'title' => 'Module 1: La Cellule',
                    'lessons' => [
                        [
                            'id' => 1,
                            'title' => 'Structure Cellulaire',
                            'duration' => '16:00',
                            'type' => 'video',
                            'video_url' => 'https://www.youtube.com/embed/URUJD5NEXC8',
                            'resources' => [
                                ['title' => 'Schéma Cellule Animale.pdf', 'type' => 'pdf', 'size' => '1.5 MB', 'url' => '#'],
                                ['title' => 'Notes de cours.pdf', 'type' => 'pdf', 'size' => '0.8 MB', 'url' => '#']
                            ]
                        ],
                        ['id' => 2, 'title' => 'Division Cellulaire', 'duration' => '21:00', 'type' => 'video', 'video_url' => 'https://www.youtube.com/embed/URUJD5NEXC8'],
                    ]
                ]
            ]
        ],
        'anglais' => [
            'title' => 'Anglais Business',
            'description' => 'Maîtrisez l\'anglais professionnel pour le monde des affaires.',
            'instructor' => 'Mr. John Smith',
            'duration' => '8h 00m',
            'level' => 'Intermédiaire',
            'category' => 'Langues',
            'image' => 'https://images.unsplash.com/photo-1543167664-40d2383d7598?w=1200&h=600&fit=crop',
            'progress' => 80,
            'modules' => []
        ],
        'francais' => [
            'title' => 'Français Académique',
            'description' => 'Perfectionnez votre français pour la rédaction universitaire.',
            'instructor' => 'Mme. Sophie Morel',
            'duration' => '10h 00m',
            'level' => 'Avancé',
            'category' => 'Langues',
            'image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=1200&h=600&fit=crop',
            'progress' => 45,
            'modules' => []
        ],
        'malagasy' => [
            'title' => 'Malagasy Avancé',
            'description' => 'Étude approfondie de la langue et de la culture malgache.',
            'instructor' => 'M. Rabe Andriana',
            'duration' => '12h 00m',
            'level' => 'Avancé',
            'category' => 'Langues',
            'image' => 'https://images.unsplash.com/photo-1516961642265-531546e84af2?w=1200&h=600&fit=crop',
            'progress' => 90,
            'modules' => []
        ],
        'communication' => [
            'title' => 'Techniques de Communication',
            'description' => 'Améliorez vos compétences en communication orale et écrite.',
            'instructor' => 'Mme. Fanja',
            'duration' => '6h 30m',
            'level' => 'Débutant',
            'category' => 'Langues',
            'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200&h=600&fit=crop',
            'progress' => 15,
            'modules' => []
        ]
    ];

    public function show($slug, Request $request)
    {
        if (!isset($this->courses[$slug])) {
            abort(404);
        }

        $course = $this->courses[$slug];

        // Déterminer la leçon active (par défaut la première)
        $currentLessonId = $request->query('lesson', 1);
        $currentLesson = null;

        foreach ($course['modules'] as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ($lesson['id'] == $currentLessonId) {
                    $currentLesson = $lesson;
                    break 2;
                }
            }
        }

        // Si leçon non trouvée, prendre la première
        if (!$currentLesson) {
            $currentLesson = $course['modules'][0]['lessons'][0];
        }

        // Dispatch course accessed event
        if (auth()->check()) {
            event(new \App\Events\CourseAccessed(
                auth()->user(),
                $slug,
                $course['title'],
                $currentLessonId
            ));
        }

        // Calculate previous and next lessons
        $allLessons = [];
        foreach ($course['modules'] as $module) {
            foreach ($module['lessons'] as $lesson) {
                $allLessons[] = $lesson;
            }
        }

        $currentIndex = -1;
        foreach ($allLessons as $index => $lesson) {
            if ($lesson['id'] == $currentLessonId) {
                $currentIndex = $index;
                break;
            }
        }

        $previousLessonId = ($currentIndex > 0) ? $allLessons[$currentIndex - 1]['id'] : null;
        $nextLessonId = ($currentIndex < count($allLessons) - 1) ? $allLessons[$currentIndex + 1]['id'] : null;

        // Check if liked
        $isLiked = session()->get("likes.{$slug}.{$currentLessonId}", false);

        return view('courses.show', compact('course', 'slug', 'currentLesson', 'previousLessonId', 'nextLessonId', 'isLiked'));
    }

    public function toggleLike($slug, $lessonId)
    {
        $key = "likes.{$slug}.{$lessonId}";
        $isLiked = session()->get($key, false);
        session()->put($key, !$isLiked);

        return response()->json(['liked' => !$isLiked]);
    }

    public function downloadResource($slug, $lessonId, Request $request)
    {
        if (!isset($this->courses[$slug])) {
            abort(404);
        }

        $course = $this->courses[$slug];
        $filename = $request->query('file');

        // Trouver la leçon
        $targetLesson = null;
        foreach ($course['modules'] as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ($lesson['id'] == $lessonId) {
                    $targetLesson = $lesson;
                    break 2;
                }
            }
        }

        if (!$targetLesson || !isset($targetLesson['resources'])) {
            abort(404);
        }

        // Trouver la ressource
        $resource = null;
        foreach ($targetLesson['resources'] as $res) {
            if ($res['title'] === $filename) {
                $resource = $res;
                break;
            }
        }

        if (!$resource) {
            abort(404);
        }

        // Générer le fichier
        if ($resource['type'] === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('
                <div style="font-family: sans-serif; padding: 20px;">
                    <h1 style="color: #2563eb;">' . $course['title'] . '</h1>
                    <h2 style="color: #4b5563;">' . $targetLesson['title'] . '</h2>
                    <hr style="border: 1px solid #e5e7eb; margin: 20px 0;">
                    <h3 style="color: #1f2937;">' . $resource['title'] . '</h3>
                    <p>Ceci est un document de démonstration généré automatiquement pour EduPass.</p>
                    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <strong>Description du cours :</strong><br>
                        ' . $course['description'] . '
                    </div>
                    <p style="margin-top: 30px; font-size: 12px; color: #9ca3af;">Généré le ' . date('d/m/Y H:i') . '</p>
                </div>
            ');

            // Dispatch file download event
            if (auth()->check()) {
                event(new \App\Events\FileDownloaded(
                    auth()->user(),
                    'pdf',
                    $resource['title'],
                    "course_{$slug}"
                ));
            }

            return $pdf->download($resource['title']);
        } elseif ($resource['type'] === 'zip') {
            $zipFileName = tempnam(sys_get_temp_dir(), "zip");
            $zip = new \ZipArchive;
            if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                $zip->addFromString('readme.txt', "Ceci est un fichier ZIP de démonstration pour le cours : " . $course['title']);
                $zip->addFromString('exercice.txt', "Contenu de l'exercice pour : " . $targetLesson['title']);
                $zip->close();

                // Dispatch file download event
                if (auth()->check()) {
                    event(new \App\Events\FileDownloaded(
                        auth()->user(),
                        'zip',
                        $resource['title'],
                        "course_{$slug}"
                    ));
                }

                return response()->download($zipFileName, $resource['title'])->deleteFileAfterSend(true);
            }
        }

        abort(404);
    }
}
