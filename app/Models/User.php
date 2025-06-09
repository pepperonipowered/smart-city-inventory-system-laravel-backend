<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\BorrowTransactions;
use App\Models\Categories;
use App\Models\Borrowers;
use App\Models\Offices;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'email',
        'password',
        'for_911',
        'for_inventory',
        'for_traffic',
        'is_deleted',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $guarded = [
        'role',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // set the 1st registered user role for admin access
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Set default role for the first user
            if (User::count() === 0) {
                $user->role = true;
                $user->for_911 = true;
                $user->for_inventory = true;
                $user->for_traffic = true;
            }
        });
    }

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

    public function borrowers()
    {
        return $this->hasMany(BorrowTransactions::class, 'lender_id');
    }

    public function categories()
    {
        return $this->hasMany(Categories::class, 'deleted_by');
    }

    public function borrowersCreatedBy()
    {
        return $this->hasMany(Borrowers::class, 'deleted_by');
    }

    public function offices()
    {
        return $this->hasMany(Offices::class, 'deleted_by');
    }
}
