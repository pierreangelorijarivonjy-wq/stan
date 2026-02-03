@extends('layouts.app')

@section('content')
    <div class="flex flex-col h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-white overflow-hidden transition-colors duration-300">
        <!-- Top Navigation (Course specific) -->
        <div class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3 flex justify-between items-center shrink-0 z-20 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <a href="{{ route('courses') }}" class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800/80 group-hover:bg-blue-600 flex items-center justify-center border border-gray-200 dark:border-gray-700 group-hover:border-blue-500 shadow-lg group-hover:shadow-blue-500/30 transition-all">
                        <i class="fas fa-arrow-left text-sm group-hover:scale-110 transition-transform group-hover:text-white"></i>
                    </div>
                    <span class="font-bold text-sm hidden sm:block tracking-wide group-hover:translate-x-1 transition-transform">RETOUR</span>
                </a>
                <div class="h-6 w-px bg-gray-300 dark:bg-gray-700 mx-2"></div>
                <h1 class="font-bold text-lg truncate max-w-md text-gray-900 dark:text-white">{{ $course['title'] }}</h1>
                <span class="bg-blue-100 dark:bg-blue-600 text-blue-800 dark:text-white text-xs px-2 py-0.5 rounded font-bold hidden md:inline-block">
                    {{ $course['level'] }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end mr-4">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Progression</span>
                    <div class="w-32 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mt-1">
                        <div class="bg-green-500 h-full rounded-full" style="width: 35%"></div>
                    </div>
                </div>
                <button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 p-2 rounded-full transition text-gray-700 dark:text-white">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar (Modules & Lessons) -->
            <div class="w-80 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col shrink-0 overflow-hidden hidden md:flex transition-colors duration-300">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="font-bold text-gray-500 dark:text-gray-300 uppercase text-xs tracking-wider mb-1">Contenu du cours</h2>
                    <p class="text-xs text-gray-500">{{ count($course['modules']) }} Modules • {{ $course['duration'] }}</p>
                </div>
                
                <div class="overflow-y-auto flex-1 custom-scrollbar">
                    @foreach($course['modules'] as $index => $module)
                        <div class="border-b border-gray-200 dark:border-gray-800">
                            <button class="w-full px-4 py-3 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition text-left" 
                                    onclick="document.getElementById('module-{{ $index }}').classList.toggle('hidden');">
                                <span class="font-semibold text-sm text-gray-700 dark:text-gray-200">{{ $module['title'] }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            <div id="module-{{ $index }}" class="bg-white dark:bg-gray-900">
                                @foreach($module['lessons'] as $lesson)
                                    <a href="{{ route('course.show', ['slug' => $slug, 'lesson' => $lesson['id']]) }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 transition border-l-2 {{ $currentLesson['id'] == $lesson['id'] ? 'border-blue-500 bg-blue-50 dark:bg-gray-800' : 'border-transparent' }}">
                                        <div class="shrink-0 w-5 h-5 rounded-full border {{ $currentLesson['id'] == $lesson['id'] ? 'border-blue-500 text-blue-500' : 'border-gray-400 dark:border-gray-600 text-transparent' }} flex items-center justify-center text-[10px]">
                                            <i class="fas fa-play {{ $currentLesson['id'] == $lesson['id'] ? '' : 'hidden' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm truncate {{ $currentLesson['id'] == $lesson['id'] ? 'text-blue-600 dark:text-white font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $lesson['title'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-600 flex items-center gap-1">
                                                <i class="far fa-clock text-[10px]"></i> {{ $lesson['duration'] }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto bg-gray-50 dark:bg-black relative transition-colors duration-300">
                <!-- Video Player Container -->
                <div class="aspect-video bg-black w-full relative group">
                    @if($currentLesson['type'] == 'video')
                        <iframe src="{{ $currentLesson['video_url'] }}" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    @else
                        <div id="quiz-container" class="w-full h-full bg-white dark:bg-gray-900 flex flex-col relative overflow-hidden transition-colors duration-300">
                            <!-- Quiz Start Screen -->
                            <div id="quiz-start" class="absolute inset-0 flex flex-col items-center justify-center bg-white dark:bg-gray-900 z-10 transition-opacity duration-500">
                                <div class="text-center p-8 max-w-2xl">
                                    <div class="w-24 h-24 bg-blue-100 dark:bg-blue-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-brain text-4xl text-blue-600 dark:text-blue-500"></i>
                                    </div>
                                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Quiz: {{ $currentLesson['title'] }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">Testez vos connaissances sur ce module. Vous devez obtenir au moins 70% pour valider.</p>
                                    <button onclick="startQuiz()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition transform hover:scale-105 shadow-lg shadow-blue-900/50 flex items-center gap-3 mx-auto">
                                        <span>Commencer le Quiz</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Quiz Question Screen -->
                            <div id="quiz-question" class="absolute inset-0 flex flex-col bg-white dark:bg-gray-900 z-0 opacity-0 pointer-events-none transition-opacity duration-500 p-8 md:p-16">
                                <div class="w-full max-w-4xl mx-auto flex flex-col h-full">
                                    <!-- Header -->
                                    <div class="flex justify-between items-center mb-8">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Question <span id="current-question-num">1</span>/<span id="total-questions">3</span></span>
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-clock text-blue-500"></i>
                                            <span class="text-blue-600 dark:text-blue-400 font-mono" id="timer">00:00</span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-800 rounded-full mb-12">
                                        <div id="progress-bar" class="h-full bg-blue-600 rounded-full transition-all duration-500" style="width: 0%"></div>
                                    </div>

                                    <!-- Question -->
                                    <h2 id="question-text" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-8 leading-tight"></h2>

                                    <!-- Options -->
                                    <div id="options-container" class="grid gap-4">
                                        <!-- Options will be injected here -->
                                    </div>

                                    <!-- Footer -->
                                    <div class="mt-auto flex justify-end pt-8">
                                        <button id="next-btn" onclick="nextQuestion()" class="bg-gray-200 dark:bg-gray-700 text-gray-400 px-6 py-3 rounded-lg font-bold cursor-not-allowed transition" disabled>
                                            Suivant
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Result Screen -->
                            <div id="quiz-result" class="absolute inset-0 flex flex-col items-center justify-center bg-white dark:bg-gray-900 z-0 opacity-0 pointer-events-none transition-opacity duration-500">
                                <div class="text-center p-8 max-w-2xl bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                                    <div id="result-icon" class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-5xl"></div>
                                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Quiz Terminé !</h3>
                                    <p id="result-message" class="text-xl text-gray-600 dark:text-gray-300 mb-8"></p>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm mb-1">Score</span>
                                            <span id="final-score" class="text-3xl font-bold text-gray-900 dark:text-white">0%</span>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm mb-1">Temps</span>
                                            <span id="final-time" class="text-3xl font-bold text-gray-900 dark:text-white">00:00</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-4 justify-center">
                                        <button onclick="resetQuiz()" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2">
                                            <i class="fas fa-redo"></i> Réessayer
                                        </button>
                                        <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2">
                                            <i class="fas fa-check"></i> Terminer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Quiz Data injected from Controller
                            const quizData = @json($currentLesson['questions'] ?? []);
                            let currentQuestionIndex = 0;
                            let score = 0;
                            let timerInterval;
                            let seconds = 0;
                            let selectedOption = null;

                            function startQuiz() {
                                if(quizData.length === 0) return;
                                
                                document.getElementById('quiz-start').style.opacity = '0';
                                document.getElementById('quiz-start').style.pointerEvents = 'none';
                                
                                const questionScreen = document.getElementById('quiz-question');
                                questionScreen.style.opacity = '1';
                                questionScreen.style.pointerEvents = 'auto';
                                
                                document.getElementById('total-questions').innerText = quizData.length;
                                startTimer();
                                loadQuestion();
                            }

                            function startTimer() {
                                timerInterval = setInterval(() => {
                                    seconds++;
                                    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
                                    const secs = (seconds % 60).toString().padStart(2, '0');
                                    document.getElementById('timer').innerText = `${mins}:${secs}`;
                                }, 1000);
                            }

                            function loadQuestion() {
                                const question = quizData[currentQuestionIndex];
                                document.getElementById('current-question-num').innerText = currentQuestionIndex + 1;
                                document.getElementById('question-text').innerText = question.question;
                                
                                // Update Progress
                                const progress = ((currentQuestionIndex) / quizData.length) * 100;
                                document.getElementById('progress-bar').style.width = `${progress}%`;

                                // Reset UI
                                document.getElementById('next-btn').disabled = true;
                                document.getElementById('next-btn').classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-400', 'cursor-not-allowed');
                                document.getElementById('next-btn').classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                                selectedOption = null;

                                // Render Options
                                const optionsContainer = document.getElementById('options-container');
                                optionsContainer.innerHTML = '';
                                
                                question.options.forEach((option, index) => {
                                    const btn = document.createElement('button');
                                    btn.className = 'w-full text-left p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 flex items-center justify-between group';
                                    btn.onclick = () => selectOption(index, btn);
                                    btn.innerHTML = `
                                        <span class="font-medium">${option}</span>
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center group-hover:border-gray-400 dark:group-hover:border-gray-500">
                                            <div class="w-3 h-3 rounded-full bg-blue-500 opacity-0 transition-opacity"></div>
                                        </div>
                                    `;
                                    optionsContainer.appendChild(btn);
                                });
                            }

                            function selectOption(index, btnElement) {
                                // Reset all options
                                const buttons = document.getElementById('options-container').children;
                                for(let btn of buttons) {
                                    btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                                    btn.classList.add('border-gray-200', 'dark:border-gray-700', 'bg-white', 'dark:bg-gray-800/50');
                                    btn.querySelector('.w-3').classList.remove('opacity-100');
                                    btn.querySelector('.w-3').classList.add('opacity-0');
                                }

                                // Highlight selected
                                btnElement.classList.remove('border-gray-200', 'dark:border-gray-700', 'bg-white', 'dark:bg-gray-800/50');
                                btnElement.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                                btnElement.querySelector('.w-3').classList.remove('opacity-0');
                                btnElement.querySelector('.w-3').classList.add('opacity-100');

                                selectedOption = index;

                                // Enable Next Button
                                const nextBtn = document.getElementById('next-btn');
                                nextBtn.disabled = false;
                                nextBtn.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-400', 'cursor-not-allowed');
                                nextBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                                
                                if(currentQuestionIndex === quizData.length - 1) {
                                    nextBtn.innerText = 'Terminer';
                                }
                            }

                            function nextQuestion() {
                                // Check answer
                                if(selectedOption === quizData[currentQuestionIndex].correct) {
                                    score++;
                                }

                                currentQuestionIndex++;

                                if(currentQuestionIndex < quizData.length) {
                                    loadQuestion();
                                } else {
                                    showResults();
                                }
                            }

                            function showResults() {
                                clearInterval(timerInterval);
                                
                                document.getElementById('quiz-question').style.opacity = '0';
                                document.getElementById('quiz-question').style.pointerEvents = 'none';
                                
                                const resultScreen = document.getElementById('quiz-result');
                                resultScreen.style.opacity = '1';
                                resultScreen.style.pointerEvents = 'auto';

                                const percentage = Math.round((score / quizData.length) * 100);
                                document.getElementById('final-score').innerText = `${percentage}%`;
                                
                                const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
                                const secs = (seconds % 60).toString().padStart(2, '0');
                                document.getElementById('final-time').innerText = `${mins}:${secs}`;

                                const icon = document.getElementById('result-icon');
                                const msg = document.getElementById('result-message');

                                if(percentage >= 70) {
                                    icon.innerHTML = '<i class="fas fa-trophy text-yellow-500"></i>';
                                    icon.className += ' bg-yellow-500/20';
                                    msg.innerText = 'Félicitations ! Vous avez validé ce module.';
                                    msg.className = 'text-xl text-green-600 dark:text-green-400 mb-8';
                                } else {
                                    icon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                                    icon.className += ' bg-red-500/20';
                                    msg.innerText = 'Dommage, vous n\'avez pas atteint le seuil de réussite.';
                                    msg.className = 'text-xl text-red-600 dark:text-red-400 mb-8';
                                }
                            }

                            function resetQuiz() {
                                currentQuestionIndex = 0;
                                score = 0;
                                seconds = 0;
                                selectedOption = null;
                                
                                document.getElementById('quiz-result').style.opacity = '0';
                                document.getElementById('quiz-result').style.pointerEvents = 'none';
                                
                                startQuiz();
                            }
                        </script>
                    @endif
                </div>

                <!-- Lesson Info & Resources -->
                <div class="p-8 max-w-4xl mx-auto w-full">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">{{ $currentLesson['title'] }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                {{ $course['instructor'] }} • Mis à jour en Décembre 2025
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <button id="like-btn" onclick="toggleLike()" class="border {{ $isLiked ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-white' }} px-4 py-2 rounded flex items-center gap-2 transition">
                                <i class="fas fa-thumbs-up"></i> <span class="hidden sm:inline">{{ $isLiked ? 'Aimé' : "J'aime" }}</span>
                            </button>
                            
                            <div class="h-10 w-px bg-gray-300 dark:bg-gray-700 mx-2"></div>

                            <a href="{{ $previousLessonId ? route('course.show', ['slug' => $slug, 'lesson' => $previousLessonId]) : '#' }}" 
                               class="bg-gray-200 dark:bg-gray-700 {{ $previousLessonId ? 'hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white' : 'text-gray-400 cursor-not-allowed' }} px-6 py-2 rounded font-bold flex items-center gap-2 transition">
                                <i class="fas fa-chevron-left text-xs"></i> Précédent
                            </a>
                            <a href="{{ $nextLessonId ? route('course.show', ['slug' => $slug, 'lesson' => $nextLessonId]) : '#' }}" 
                               class="bg-blue-600 {{ $nextLessonId ? 'hover:bg-blue-700 text-white' : 'bg-blue-400 cursor-not-allowed' }} px-6 py-2 rounded font-bold flex items-center gap-2 transition shadow-lg shadow-blue-900/20">
                                Suivant <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tabs with Alpine.js -->
                    <div x-data="{ activeTab: 'description' }">
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="flex gap-8">
                                <button @click="activeTab = 'description'" 
                                        :class="activeTab === 'description' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                        class="border-b-2 pb-4 font-medium transition">Description</button>
                                <button @click="activeTab = 'resources'" 
                                        :class="activeTab === 'resources' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                        class="border-b-2 pb-4 font-medium transition">Ressources</button>
                                <button @click="activeTab = 'notes'" 
                                        :class="activeTab === 'notes' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                        class="border-b-2 pb-4 font-medium transition">Notes</button>
                                <button @click="activeTab = 'discussion'" 
                                        :class="activeTab === 'discussion' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                        class="border-b-2 pb-4 font-medium transition">Discussion</button>
                            </nav>
                        </div>

                        <!-- Tab Content -->
                        <div x-show="activeTab === 'description'" x-cloak>
                            <div class="prose prose-invert max-w-none">
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Dans cette leçon, nous allons explorer les concepts fondamentaux de <strong>{{ $currentLesson['title'] }}</strong>. 
                                    Ceci est une étape cruciale pour comprendre l'ensemble du module.
                                </p>
                                <h3 class="text-xl font-bold mt-6 mb-4 text-gray-900 dark:text-white">Points clés abordés :</h3>
                                <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-300">
                                    <li>Compréhension approfondie des théories de base</li>
                                    <li>Application pratique des formules</li>
                                    <li>Résolution de problèmes complexes</li>
                                    <li>Préparation aux examens finaux</li>
                                </ul>
                            </div>
                        </div>

                        <div x-show="activeTab === 'resources'" x-cloak>
                            @if(isset($currentLesson['resources']) && count($currentLesson['resources']) > 0)
                                <div class="space-y-4">
                                    @foreach($currentLesson['resources'] as $resource)
                                        <a href="{{ route('course.download', ['slug' => $slug, 'lesson' => $currentLesson['id'], 'file' => $resource['title']]) }}" class="flex items-center justify-between p-4 bg-white dark:bg-gray-800/50 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition group border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    @if($resource['type'] == 'pdf')
                                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                                    @elseif($resource['type'] == 'zip')
                                                        <i class="fas fa-file-archive text-yellow-500 text-2xl"></i>
                                                    @else
                                                        <i class="fas fa-file text-gray-400 text-2xl"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">{{ $resource['title'] }}</p>
                                                    <p class="text-xs text-gray-500">{{ $resource['size'] }} • {{ strtoupper($resource['type']) }}</p>
                                                </div>
                                            </div>
                                            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-download"></i>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Aucune ressource disponible pour cette leçon.</p>
                                </div>
                            @endif
                        </div>

                        <div x-show="activeTab === 'notes'" x-cloak>
                            <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/20 p-6 rounded-2xl mb-6">
                                <h4 class="font-bold text-yellow-800 dark:text-yellow-500 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lightbulb"></i> Astuce
                                </h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-600/80">
                                    Prenez des notes pendant la vidéo pour mieux mémoriser les concepts clés. Vos notes sont sauvegardées automatiquement.
                                </p>
                            </div>
                            <textarea class="w-full h-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none" placeholder="Commencez à écrire vos notes ici..."></textarea>
                            <div class="flex justify-end mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold transition shadow-lg shadow-blue-900/20">
                                    Enregistrer les notes
                                </button>
                            </div>
                        </div>

                        <div x-show="activeTab === 'discussion'" x-cloak>
                            <div class="space-y-6">
                                <!-- Comment Input -->
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shrink-0">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <textarea class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Posez une question ou laissez un commentaire..." rows="3"></textarea>
                                        <div class="flex justify-end mt-2">
                                            <button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                                                Publier
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dummy Comments -->
                                <div class="space-y-6 mt-8">
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0">J</div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-sm text-gray-900 dark:text-white">Jean Dupont</span>
                                                <span class="text-[10px] text-gray-500">Il y a 2 heures</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Super explication sur les matrices ! Est-ce qu'on verra les déterminants dans le prochain module ?
                                            </p>
                                            <div class="flex items-center gap-4 mt-2">
                                                <button class="text-xs text-gray-500 hover:text-blue-500 transition">Répondre</button>
                                                <button class="text-xs text-gray-500 hover:text-blue-500 transition flex items-center gap-1">
                                                    <i class="far fa-thumbs-up"></i> 12
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold shrink-0">M</div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-sm text-gray-900 dark:text-white">Marie Rakoto</span>
                                                <span class="text-[10px] text-gray-500">Il y a 5 heures</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                J'ai un peu de mal avec la multiplication des matrices 3x3. Quelqu'un a une astuce ?
                                            </p>
                                            <div class="flex items-center gap-4 mt-2">
                                                <button class="text-xs text-gray-500 hover:text-blue-500 transition">Répondre</button>
                                                <button class="text-xs text-gray-500 hover:text-blue-500 transition flex items-center gap-1">
                                                    <i class="far fa-thumbs-up"></i> 5
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLike() {
            fetch("{{ route('course.like', ['slug' => $slug, 'lesson' => $currentLesson['id']]) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('like-btn');
                const text = btn.querySelector('span');
                
                if (data.liked) {
                    btn.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-600');
                    btn.classList.remove('border-gray-300', 'dark:border-gray-600', 'hover:bg-gray-100', 'dark:hover:bg-gray-800', 'text-gray-700', 'dark:text-white');
                    text.innerText = 'Aimé';
                } else {
                    btn.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-600');
                    btn.classList.add('border-gray-300', 'dark:border-gray-600', 'hover:bg-gray-100', 'dark:hover:bg-gray-800', 'text-gray-700', 'dark:text-white');
                    text.innerText = "J'aime";
                }
            });
        }
    </script>

    <style>
        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #111827; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db; 
            border-radius: 3px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; 
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #4b5563; 
        }
    </style>
@endsection