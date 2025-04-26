<?php

namespace Modules\User\App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Laravel\Pennant\Concerns\HasFeatures;
use Laravel\Scout\Searchable;
use Modules\Account\App\Models\Account;
use Modules\Payment\App\Models\Payment;
use Modules\Payment\App\Models\PaymentMethod;
use Modules\Transaction\App\Models\Transaction;
use Modules\User\Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use /* HasApiTokens, */ HasFactory, HasFeatures, HasPermissions, HasRoles, HasUuids, Notifiable, Searchable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function toSearchableArray()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }

    public function name(): Attribute
    {
        return Attribute::get(fn () => $this->first_name.' '.$this->last_name);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // public function addresses()
    // {
    //     return $this->morphToMany(Address::class, 'addressable');
    // }

    public function accounts()
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable')->wherePrimary(1);
    }

    public function balance(): Attribute
    {
        return Attribute::get(fn () => $this->accounts()->sum('amount'));
    }

    /**
     * Get User's recent transactions
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Account::class)->latest();
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment_methods()
    {
        return $this->hasManyThrough(PaymentMethod::class, Payment::class, 'method_id', 'slug');
    }
}
