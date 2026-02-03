<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    /**
     * Display student's enrolled courses.
     */
    public function index()
    {
        $user = auth()->user();

        // Get enrolled courses with progress
        $courses = $user->student->courses()
            ->with(['modules.lessons'])
            ->where('status', 'active')
            ->get()
            ->map(function ($course) use ($user) {
                $totalLessons = $course->modules->sum(fn($m) => $m->lessons->count());
                $completedLessons = LessonProgress::where('user_id', $user->id)
                    ->where('is_completed', true)
                    ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
                    ->count();

                $course->progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                return $course;
            });

        return view('student.courses.index', compact('courses'));
    }

    /**
     * Display the specified course player.
     */
    public function show(Course $course, Request $request)
    {
        $user = auth()->user();

        // Check if student is enrolled
        if (!$user->student->courses->contains($course->id)) {
            abort(403, 'Vous n\'êtes pas inscrit à ce cours.');
        }

        $course->load(['modules.lessons.resources', 'modules.lessons.quiz']);

        // Get current lesson (from query or first lesson)
        $currentLessonId = $request->query('lesson');
        $currentLesson = null;

        if ($currentLessonId) {
            $currentLesson = Lesson::find($currentLessonId);
        }

        if (!$currentLesson) {
            $currentLesson = $course->modules->first()?->lessons->first();
        }

        // Get or create progress for current lesson
        $progress = null;
        if ($currentLesson) {
            $progress = LessonProgress::firstOrCreate([
                'user_id' => $user->id,
                'lesson_id' => $currentLesson->id,
            ]);
        }

        return view('student.courses.show', compact('course', 'currentLesson', 'progress'));
    }

    /**
     * Mark a lesson as completed.
     */
    public function completeLesson(Lesson $lesson)
    {
        $user = auth()->user();

        $progress = LessonProgress::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $progress->markComplete();

        return response()->json([
            'success' => true,
            'message' => 'Leçon marquée comme terminée.',
        ]);
    }

    /**
     * Download a lesson resource.
     */
    public function downloadResource($resourceId)
    {
        $user = auth()->user();
        $resource = \App\Models\Resource::findOrFail($resourceId);

        // Verify enrollment
        $lesson = $resource->lesson;
        $course = $lesson->module->course;

        if (!$user->student->courses->contains($course->id)) {
            abort(403, 'Accès non autorisé.');
        }

        $resource->incrementDownloads();

        // Log download event
        event(new \App\Events\FileDownloaded(
            $user,
            $resource->file_type,
            $resource->title,
            "course_{$course->slug}"
        ));

        return response()->download(storage_path('app/' . $resource->file_path));
    }

    /**
     * Start a quiz attempt.
     */
    public function startQuiz(Quiz $quiz)
    {
        $user = auth()->user();

        // Check enrollment
        $course = $quiz->course;
        if (!$user->student->courses->contains($course->id)) {
            abort(403, 'Accès non autorisé.');
        }

        // Check max attempts
        if ($quiz->max_attempts) {
            $attemptCount = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->count();

            if ($attemptCount >= $quiz->max_attempts) {
                return back()->with('error', 'Nombre maximum de tentatives atteint.');
            }
        }

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('student.quiz.take', $attempt);
    }

    /**
     * Submit quiz answers.
     */
    public function submitQuiz(Request $request, QuizAttempt $attempt)
    {
        $user = auth()->user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz = $attempt->quiz;
        $questions = $quiz->questions;

        $score = 0;
        $totalPoints = $questions->sum('points');

        foreach ($questions as $question) {
            $userAnswer = $validated['answers'][$question->id] ?? null;
            if ($question->isCorrect($userAnswer)) {
                $score += $question->points;
            }
        }

        $scorePercentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        $attempt->update([
            'answers' => $validated['answers'],
            'score' => $scorePercentage,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $passed = $attempt->isPassed();

        return redirect()->route('student.quiz.result', $attempt)
            ->with(
                $passed ? 'success' : 'warning',
                $passed ? 'Quiz réussi !' : 'Quiz échoué. Réessayez.'
            );
    }
}
