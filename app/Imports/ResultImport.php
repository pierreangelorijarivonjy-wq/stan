<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ResultImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $examSessionId;

    public function __construct($examSessionId)
    {
        $this->examSessionId = $examSessionId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $student = Student::where('matricule', $row['matricule'])->first();

        if (!$student) {
            return null;
        }

        // Éviter les doublons pour la même session et la même matière
        $existing = Result::where('student_id', $student->id)
            ->where('exam_session_id', $this->examSessionId)
            ->where('subject', $row['matiere'])
            ->first();

        if ($existing) {
            $existing->update([
                'grade' => $row['note'],
                'status' => 'draft'
            ]);
            return null;
        }

        return new Result([
            'student_id' => $student->id,
            'exam_session_id' => $this->examSessionId,
            'subject' => $row['matiere'],
            'grade' => $row['note'],
            'status' => 'draft',
            'metadata' => [
                'imported_at' => now()->toDateTimeString(),
                'imported_by' => auth()->id()
            ]
        ]);
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|exists:students,matricule',
            'matiere' => 'required|string',
            'note' => 'required|numeric|min:0|max:20',
        ];
    }
}
