<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Roles;
use App\Builders\UserBuilder;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\NewUserCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'staff_no',
        'gender',
        'lastname',
        'password',
        'firstname',
        'phone_number',
        'password_changed_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function query(): UserBuilder
    {
        return parent::query();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(related: DailyReport::class, foreignKey: 'user_id', localKey: 'id');
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function hasAnyRoles(array $roles): bool
    {
        return null !== $this->roles()->whereIn(column: 'name', values: $roles)->first();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(related: Role::class, table: 'role_user');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Roles::ADMIN);
    }

    public function hasRole(Roles $role): bool
    {
        return null !== $this->roles()->where(column: 'name', operator: '=', value: $role)->first();
    }

    public function isProjectManager(): bool
    {
        return $this->hasRole(Roles::PROJECT_MANAGER);
    }

    public function sendNewUserCreatedNotification(string $temporary_password): void
    {
        $this->notify(new NewUserCreated(
            firstname: $this->firstname,
            lastname: $this->lastname,
            email: $this->email,
            temporary_password: $temporary_password,
        ));
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => "{$attributes['firstname']} {$attributes['lastname']}"
        );
    }
    /**
     * Add a prefix to the id column
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $latestUser = self::latest('id')->first();
            $latestId = $latestUser ? (int)substr($latestUser->id, 3) : 0;
            $newId = 'StaffNo: ' . ($latestId + 1);

            // Check if the generated 'staffno' already exists and generate a new one if needed
            while (self::where('staff_no', $newId)->exists()) {
                $latestId++;
                $newId = 'StaffNo: '  . ($latestId + 1);
            }

            $user->staff_no = $newId;
        });
    }
}
