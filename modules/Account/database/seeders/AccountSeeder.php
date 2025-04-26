<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Account\App\Models\Account;
use Modules\User\App\Models\User;
use Modules\User\Database\Seeders\UserSeeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the default user
        $this->call(UserSeeder::class);

        $accountables = User::all();
        foreach ($accountables as $accountable) {
            $account_number = (int) Account::max('number');
            $account = [
                'name' => 'Savings',
                'number' => ++$account_number,
                'accountable_type' => get_class($accountable),
                'accountable_id' => $accountable->id,
            ];

            Account::firstOrCreate(Arr::only($account, ['name', 'number', 'currency', 'accountable_type', 'accountable_id']), $account);

            // // Users' accounts
            // $users = User::doesntHave('accounts')->get();
            // $account_number = (int) Account::max('number');
            // foreach ($users as $user) {
            //     $user->accounts()->create([
            //         'number' => ++$account_number,
            //     ]);
            // }
        }
    }
}
