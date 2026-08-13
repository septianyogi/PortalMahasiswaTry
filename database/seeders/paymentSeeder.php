<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class paymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();

        foreach ($students as $student) {
           Payment::create([
            'order_id' =>  'INV-' . date('Ymd') . '-' . strtoupper(uniqid()) . '-' . $student->id,
            'student_id' => $student->id,
            'type' => 'UKT',
            'description' => 'Pembayaran Semester 3',
            'amount' => 5000000,
            'status' => 'pending'
           ]);
        }
    }
}
