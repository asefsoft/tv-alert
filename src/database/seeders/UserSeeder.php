<?php

namespace Database\Seeders;

use App\Models\EmailSubscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(isTesting()) {
            User::truncate();
        }

        User::factory(15)
            // include an email subscription relation
//            ->has(EmailSubscription::factory(1), 'emailSubscriptions')
            ->create();
    }
}
