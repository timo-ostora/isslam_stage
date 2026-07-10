<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

/**
 * Issues one certificate per completed enrollment. Relies on the
 * unique(user_id, course_id) constraint added to the certificates table
 * to make this safely re-runnable via firstOrCreate.
 */
class CertificatesSeeder extends Seeder
{
    public function run(): void
    {
        Enrollment::with(['user', 'course'])
            ->where('status', 'completed')
            ->get()
            ->each(function (Enrollment $enrollment) {
                Certificate::firstOrCreate(
                    ['user_id' => $enrollment->user_id, 'course_id' => $enrollment->course_id],
                    [
                        'certificate_number' => sprintf(
                            'CERT-%s-%s',
                            now()->year,
                            Str::upper(Str::random(10))
                        ),
                        'issued_at' => $enrollment->completed_at ?? now(),
                    ]
                );
            });

        $this->command->info('✅  Certificates seeded.');
    }
}