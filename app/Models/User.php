<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\VerifyEmailMail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'is_approved'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public function penulis(): HasOne
    {
        return $this->hasOne(Penulis::class);
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'user_id');
    }

    public function komentars(): HasMany
    {
        return $this->hasMany(Komentar::class);
    }

    public function reaksis(): HasMany
    {
        return $this->hasMany(Reaksi::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())],
        );

        try {
            Mail::to($this->email)->send(new VerifyEmailMail($this, $url));
        } catch (\Throwable $e) {
            report($e);
        }
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
            'is_approved' => 'boolean',
        ];
    }
}
