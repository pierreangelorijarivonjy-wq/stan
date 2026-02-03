<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LmsCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:scolarite|admin']);
    }

    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $courses = Course::with('modules.lessons')
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('admin.lms.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.lms.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = 'draft';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('admin.lms.courses.show', $course)
            ->with('success', 'Cours créé avec succès.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['modules.lessons.resources', 'modules.lessons.quiz']);

        return view('admin.lms.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.lms.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'status' => 'required|in:draft,active,archived',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.lms.courses.show', $course)
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.lms.courses.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Add a module to the course.
     */
    public function addModule(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? $course->modules()->count() + 1;

        $module = Module::create($validated);

        return back()->with('success', 'Module ajouté avec succès.');
    }

    /**
     * Add a lesson to a module.
     */
    public function addLesson(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,pdf,audio,quiz,text',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|string|max:20',
            'order' => 'nullable|integer',
        ]);

        $validated['module_id'] = $module->id;
        $validated['order'] = $validated['order'] ?? $module->lessons()->count() + 1;

        $lesson = Lesson::create($validated);

        return back()->with('success', 'Leçon ajoutée avec succès.');
    }
}
