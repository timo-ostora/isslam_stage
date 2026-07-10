<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * FIX: ShieldPermissionsSeeder is retired. Hand-listing resource slugs
     * in a seeder can silently drift from what actually exists in
     * app/Filament/Resources — which is exactly what caused the super_admin
     * 403: the seeder inserted permission *rows*, but never generated the
     * real app/Policies/*Policy.php classes (or page/widget permissions)
     * that Filament + Shield actually check against. `shield:generate --all`
     * is the only thing that scans your real resources/pages/widgets and
     * produces both the permission rows AND the policy classes, so it's now
     * step 1, always run fresh, before any role gets permissions synced.
     *
     * Order:
     *   1. shield:generate --all  → real permissions + policy classes, from
     *      whatever Resources/Pages/Widgets actually exist right now.
     *   2. RolesSeeder            → syncs those real permissions onto roles.
     *   3. UsersSeeder            → creates demo users, assigns roles.
     *   4. Content seeders        → categories → courses → modules →
     *      module content → questions → enrollments → attempts →
     *      certificates.
     *
     * NOTE: shield:generate only sees resources that exist at the time this
     * runs. Every time you add a new Filament Resource/Page/Widget, either
     * re-run this seeder or run `php artisan shield:generate --all` directly
     * so the new permissions/policies get created — otherwise the new
     * resource will 403 for everyone except super_admin, the same failure
     * mode you just hit.
     */
    public function run(): void
    {
        $this->command->info('🛡️  Generating Shield permissions & policies...');
        Artisan::call('shield:generate', ['--all' => true, '--option' => 'policies_and_permissions', '--panel' => 'admin']);
        $this->command->line(Artisan::output());

        // $this->command->info('🛡️  Generating Shield permissions & policies...');

        // // 1. Pre-fill the interactive command prompts automatically
        // Artisan::setInputs([
        //     'yes', // Answers the "Do you want to generate policies and permissions?" prompt
        //     'yes', // Answers any secondary confirmation prompt if applicable
        // ]);

        // // 2. Run the command safely without stalling
        // Artisan::call('shield:generate', ['--all' => true]);

        // // 3. Print the execution log
        // $this->command->line(Artisan::output());

        // $this->command->info('🛡️  Generating Shield permissions & policies...');

        // // Using the strict option tells Shield exactly what to do without asking
        // Artisan::call('shield:generate', [
        //     '--all' => true,
        //     '--minimal' => true, // Overrides the interactive wizard questions
        // ]);

        // $this->command->line(Artisan::output());




        // $this->command->info('🛡️  Generating Shield permissions & policies...');

        // // 1. Resolve the console kernel instance
        // $kernel = Container::getInstance()->make(\Illuminate\Contracts\Console\Kernel::class);

        // // 2. Mock terminal inputs line-by-line
        // $input = new ArrayInput(['--all' => true]);
        // $input->setInteractive(true);

        // // Mock the 'yes' inputs for the Symfony questions
        // $stream = fopen('php://memory', 'r+');
        // fwrite($stream, "yes\nyes\n");
        // rewind($stream);
        // $input->setStream($stream);

        // // 3. Run the output capture cleanly
        // $output = new BufferedOutput;
        // $kernel->handle($input, $output);

        // $this->command->line($output->fetch());




        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            CategoriesSeeder::class,
            CoursesSeeder::class,
            ModulesSeeder::class,
            ModuleContentSeeder::class,
            QuestionsSeeder::class,
            EnrollmentsSeeder::class,
            AttemptsSeeder::class,
            CertificatesSeeder::class,
        ]);
    }
}