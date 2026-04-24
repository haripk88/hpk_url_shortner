<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('companies')->truncate();
        Schema::enableForeignKeyConstraints();

        // Creating superadmin company
        $superadminCompany = new Company();
        $superadminCompany->name = 'Global Company';
        $superadminCompany->email = 'gloablcompany@gmail.com';
        $superadminCompany->is_supper = 1;
        $superadminCompany->save();
    }
}
