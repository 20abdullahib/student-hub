<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\Department;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Generation;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\BranchSubjectSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /**         
         * ==================================================
         * |       Insert default department data           |
         * ==================================================
         **/

        Department::insert([
            ['name' => 'Mathematics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Physics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemistry', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Geology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Botany', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Animals', 'created_at' => now(), 'updated_at' => now()],
        ]);

        /** 
         * =========================================================
         * |       Insert all branches into the database           |
         * =========================================================
         **/
        Branch::insert([
            ['name' => 'Mathematics', 'department_id' => 1], // Mathematics
            ['name' => 'Mathematics and Physics', 'department_id' => 1],
            ['name' => 'Computer Science', 'department_id' => 1],
            ['name' => 'Physics', 'department_id' => 2], // Physics
            ['name' => 'Physics and Electronics', 'department_id' => 2],
            ['name' => 'Chemistry', 'department_id' => 3], // Chemistry
            ['name' => 'Chemistry and Microbiology', 'department_id' => 3],
            ['name' => 'Chemistry and Botany', 'department_id' => 3],
            ['name' => 'Chemistry and Zoology', 'department_id' => 3],
            ['name' => 'Chemistry and Physics', 'department_id' => 3],
            ['name' => 'Geology', 'department_id' => 4], // Geology
            ['name' => 'Geophysics', 'department_id' => 4],
            ['name' => 'Chemistry and Geology', 'department_id' => 4],
            ['name' => 'Botany', 'department_id' => 5], // Botany
            ['name' => 'Microbiology', 'department_id' => 5],
            ['name' => 'Zoology', 'department_id' => 6], // Animals
            ['name' => 'Entomology', 'department_id' => 6],
            ['name' => 'Insect Chemistry', 'department_id' => 6],
        ]);

        /**         
         * ========================================
         * |        Insert generations            |
         * ========================================
         **/
        $years = range(2020, 2030); // Insert for 2020, 2021, 2022, 2023
        $peoplePerYear = 10;
        $branchIds = range(1, 18); // Assuming you have 18 branches
        $roles = ['OC', 'IT', 'HR', 'BR']; // Define roles

        $faker = Faker::create();

        foreach ($years as $year) {
            for ($i = 1; $i <= $peoplePerYear; $i++) {
                Generation::create([
                    'name' => $faker->name,
                    'year_joined' => $year,
                    'branch_id' => $faker->randomElement($branchIds),
                    'image' => 'https://codescandy.com/geeks-bootstrap-5/assets/images/mentor/mentor-img-' . $faker->numberBetween(1, 8) . '.jpg', // Adding image link
                    'role' => $faker->randomElement($roles), // Adding random role
                    'publish' => $faker->numberBetween(0, 1), // Adding random publish value
                ]);
            }
        }

        /**         
         * ==========================================================================
         * |        Seed the subjects and attach random branches to them.           |
         * ==========================================================================
         **/
        $this->call(SubjectSeeder::class);


        /**
         * for test only
         * Generate 50 fake subject records
         * Subject::factory()->count(50)->create();
         **/
        // for testing purposes
        // // Fetch all subjects
        // $subjects = Subject::all();

        // // Attach random branches to random subjects
        // foreach ($subjects as $subject) {
        //     // Get a random number of branches to attach (between 1 and 3 for example)
        //     $randomBranches = $faker->randomElements($branchIds, $faker->numberBetween(1, 18));
        //     $subject->branches()->attach($randomBranches);
        // }


        /**         
         * =========================================================
         * |       Seed roles and permissions into the database    |
         * =========================================================
         **/

        $this->call(RolesAndPermissionsSeeder::class);

        $admin = Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('#bom123456'),
            'department_id' => 1,
            'branch_id' => 1,
            'role' => 'super admin',
        ]);
        $admin->assignRole('super admin');

        /**         
         * =========================================================
         * |       Seed Braches and Subjects into the database    |
         * =========================================================
         **/

         $this->call(BranchSubjectSeeder::class);
    }
}
