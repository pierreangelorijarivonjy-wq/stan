<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Convocation;
use Illuminate\Http\Request;

class ConvocationController extends Controller
{
    /**
     * Get user's convocations
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('student')) {
            $student = $user->student;
            if (!$student) {
                return response()->json(['message' => 'Profil étudiant non trouvé'], 404);
            }
            $convocations = $student->convocations()->with('examSession')->latest()->get();
        } else {
            $convocations = Convocation::with(['student', 'examSession'])->latest()->paginate(50);
        }

        return response()->json($convocations);
    }

    /**
     * Get single convocation
     */
    public function show(Request $request, Convocation $convocation)
    {
        // Authorization
        if ($request->user()->hasRole('student') && $convocation->student->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json($convocation->load('student', 'examSession'));
    }

    /**
     * Download convocation PDF
     */
    public function download(Request $request, Convocation $convocation)
    {
        // Authorization
        if ($request->user()->hasRole('student') && $convocation->student->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if (!$convocation->pdf_url) {
            return response()->json(['message' => 'PDF non disponible'], 404);
        }

        $convocation->update([
            'downloaded_at' => now(),
            'status' => 'downloaded',
        ]);

        return response()->json([
            'pdf_url' => url('storage/' . $convocation->pdf_url),
            'qr_code' => $convocation->qr_code,
        ]);
    }
}
