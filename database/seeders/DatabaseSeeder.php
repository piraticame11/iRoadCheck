<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\StaffPermission;
use App\Models\StaffRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => Crypt::encryptString('Howard Glen'),
                'middle_name' => Crypt::encryptString('Doe'),
                'last_name' => Crypt::encryptString('Gloria'),
                'date_of_birth' => '1990-01-01',
                'sex' => Crypt::encryptString('male'),
                'username' => Crypt::encryptString('admin'),
                'email' => null,
                'password' => Hash::make('password'),
                'user_type' => 1,
                'remember_token' => null,
                'generated_password' => null,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => Crypt::encryptString('Jane'),
                'middle_name' => Crypt::encryptString('D.'),
                'last_name' => Crypt::encryptString('Resident'),
                'date_of_birth' => '1995-02-02',
                'sex' => Crypt::encryptString('female'),
                'username' => null,
                'email' => Crypt::encryptString('resident@example.com'),
                'password' => Hash::make('password'),
                'user_type' => 2,
                'remember_token' => null,
                'generated_password' => null,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => Crypt::encryptString('Bright'),
                'middle_name' => Crypt::encryptString('D.'),
                'last_name' => Crypt::encryptString('PatcherKo'),
                'date_of_birth' => '1995-02-05',
                'sex' => Crypt::encryptString('female'),
                'user_type' => 3,
                'email' => null,
                'username' => Crypt::encryptString('staff'),
                'password' => Hash::make('password'),
                'generated_password' => 'password',
                'remember_token' => null,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        DB::table('severities')->insert([
            [
                'label' => 'Level 1',
            ],
            [
                'label' => 'Level 2',
            ],
            [
                'label' => 'Level 3',
            ],
            [
                'label' => 'Level 4',
            ],
            [
                'label' => 'Level 0',
            ],
        ]);

        $this->call([
            UserSeeder::class,
//            ActivityLogSeeder::class,
//            ReportSeeder::class,
            RolePermissionSeeder::class,
//            AdminLogSeeder::class,
//            StaffLogSeeder::class,
//            ResidentLogSeeder::class,
            AdminSeeder::class,
            StaffSeeder::class,
            ResidentSeeder::class,
//            StaffRoleSeeder::class,
//            StaffPermissionSeeder::class
            BarangaysTableSeeder::class,
            StreetsTableSeeder::class,
        ]);
    }
}
