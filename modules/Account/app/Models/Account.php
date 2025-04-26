<?php

namespace Modules\Account\App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Database\Factories\AccountFactory;
use Modules\Transaction\App\Models\Transaction;
use Modules\Transaction\App\Models\TransactionEntry;
use Modules\User\App\Models\User;

class Account extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'number',
        'amount',
        'currency',
        'primary',
        'accountable_type',
        'accountable_id',
    ];

    /**
     * Get Balance for an Account
     * To calculate the balance of an account:
     * - Assets and expenses: Debit increases balance, credit decreases.
     * - Liabilities, equity, and revenue: Credit increases balance, debit decreases.
     */
    public function getBalanceAttribute(?Account $account_id = null)
    {
        if (is_null($account_id)) {
            $account_id = (Auth::user())->account->id;
        }

        return TransactionEntry::where('account_id', $account_id)
            ->selectRaw("
                SUM(CASE WHEN type = 'cr' THEN amount ELSE 0 END) -
                SUM(CASE WHEN type = 'dr' THEN amount ELSE 0 END) AS balance
            ")
            ->value('balance');
    }

    public function number(): Attribute
    {
        return Attribute::get(fn ($key, $account) => str_pad($account['number'], max(3, strlen($account['number'])), '0', STR_PAD_LEFT));
    }

    public function accountable()
    {
        return $this->morphTo();
    }

    public function entries()
    {
        return $this->hasMany(TransactionEntry::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, TransactionEntry::class);
    }

    protected static function newFactory(): Factory
    {
        return AccountFactory::new();
    }
}
