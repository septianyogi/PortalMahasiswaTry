<?php

namespace Database\Seeders;

use App\Models\ClassAttended;
use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentSemester;
use App\Services\GradeService;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClassAttendedSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $students = Student::all();
        $gradeService = app(GradeService::class);

        // Ambil semua kelas per semester
        $sem1Classes = Classes::where('semester', '1')->get();
        $sem2Classes = Classes::where('semester', '2')->get();
        $sem3Classes = Classes::where('semester', '3')->get();

        foreach ($students as $student) {
            // --- Semester 1 & 2 (completed) ---
            $chosenSem1 = $sem1Classes->random(rand(3, 4));
            $chosenSem2 = $sem2Classes->random(rand(3, 4));
            $allChosen = $chosenSem1->merge($chosenSem2);

            foreach ($allChosen as $class) {
                // Generate nilai (beberapa bisa null)
                $assignments = [];
                for ($i = 1; $i <= 4; $i++) {
                    $assignments['assignment_' . $i] = $faker->boolean(15) ? null : $faker->numberBetween(50, 100);
                }
                $mid = $faker->boolean(15) ? null : $faker->numberBetween(40, 100);
                $final = $faker->boolean(15) ? null : $faker->numberBetween(40, 100);

                $totalMeetings = $faker->numberBetween(12, 14);
                $attendance = $faker->numberBetween(6, $totalMeetings);
                $absent = $totalMeetings - $attendance;

                $classAttended = ClassAttended::create([
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                    'semester' => (int) $class->semester,
                    'verified_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'attendance' => $attendance,
                    'absent' => $absent,
                    'assignment_1' => $assignments['assignment_1'],
                    'assignment_2' => $assignments['assignment_2'],
                    'assignment_3' => $assignments['assignment_3'],
                    'assignment_4' => $assignments['assignment_4'],
                    'mid_exam' => $mid,
                    'final_exam' => $final,
                ]);

                $gradeService->calculate($classAttended);
            }

            // --- Semester 3 (aktif) ---
            // Ambil 2-3 kelas dari semester 3
            if ($sem3Classes->count() >= 6) {
                $chosenSem3 = $sem3Classes->random(rand(6, 7));
                foreach ($chosenSem3 as $class) {
                    // Untuk semester aktif, nilai mungkin masih banyak null
                    $assignments = [];
                    for ($i = 1; $i <= 4; $i++) {
                        // 40% kemungkinan null (belum dinilai)
                        $assignments['assignment_' . $i] = $faker->boolean(40) ? null : $faker->numberBetween(50, 100);
                    }
                    $mid = $faker->boolean(40) ? null : $faker->numberBetween(40, 100);
                    $final = $faker->boolean(60) ? null : $faker->numberBetween(40, 100); // final biasanya belum

                    $totalMeetings = $faker->numberBetween(6, 10); // belum selesai semua pertemuan
                    $attendance = $faker->numberBetween(3, $totalMeetings);
                    $absent = $totalMeetings - $attendance;

                    $classAttended = ClassAttended::create([
                        'class_id' => $class->id,
                        'student_id' => $student->id,
                        'semester' => (int) $class->semester,
                        'verified_at' => null, // belum diverifikasi
                        'attendance' => $attendance,
                        'absent' => $absent,
                        'assignment_1' => $assignments['assignment_1'],
                        'assignment_2' => $assignments['assignment_2'],
                        'assignment_3' => $assignments['assignment_3'],
                        'assignment_4' => $assignments['assignment_4'],
                        'mid_exam' => $mid,
                        'final_exam' => $final,
                    ]);

                    // Hitung grade (meskipun banyak null, tetap dihitung)
                    $gradeService->calculate($classAttended);
                }
            }

            // --- Update GPA per semester (1 & 2) ---
            for ($sem = 1; $sem <= 2; $sem++) {
                $classAttendeds = ClassAttended::where('student_id', $student->id)
                    ->where('semester', $sem)
                    ->with('class')
                    ->get();

                $totalCredits = 0;
                $totalQuality = 0;
                foreach ($classAttendeds as $ca) {
                    if (!is_null($ca->gpa)) {
                        $credits = $ca->class->credits;
                        $totalCredits += $credits;
                        $totalQuality += $credits * $ca->gpa;
                    }
                }
                $gpa = $totalCredits > 0 ? round($totalQuality / $totalCredits, 2) : 0;

                $semesterRecord = StudentSemester::where('student_id', $student->id)
                    ->where('semester_number', $sem)
                    ->first();

                if ($semesterRecord) {
                    $semesterRecord->update([
                        'gpa' => $gpa,
                        'credits' => $totalCredits,
                    ]);
                }
            }

            // --- Update student credits & gpa (berdasarkan semester completed) ---
            $completedSemesters = StudentSemester::where('student_id', $student->id)
                ->where('status', 'completed')
                ->get();

            $totalCreditsAll = 0;
            $totalQualityAll = 0;
            foreach ($completedSemesters as $sem) {
                $totalCreditsAll += $sem->credits;
                $totalQualityAll += $sem->credits * $sem->gpa;
            }

            $student->credits = $totalCreditsAll;
            $student->gpa = $totalCreditsAll > 0 ? round($totalQualityAll / $totalCreditsAll, 2) : 0;
            $student->save();
        }
    }
}