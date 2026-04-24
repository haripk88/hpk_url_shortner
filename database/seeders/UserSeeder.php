<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $globalCompany = Company::where('is_supper', 1)->first();
        $superadminUser = new User();
        $superadminUser->company_id = $globalCompany->id;
        $superadminUser->name = 'Supper Admin';
        $superadminUser->email = 'superadmin@gmail.com';
        $superadminUser->password = Hash::make(12345678);
        $superadminUser->roles = 'superadmin';
        $superadminUser->status = 'enabled';
        $superadminUser->save();
    }
}
