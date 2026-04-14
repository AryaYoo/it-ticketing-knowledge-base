<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'display_name',
        'location',
        'is_active',
        'is_hospital_asset',
        'user_id',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hospital_asset' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the user associated with this IP mapping.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the asset record associated with this IP mapping.
     */
    public function asset()
    {
        return $this->hasOne(Asset::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($ipMapping) {
            if ($ipMapping->is_hospital_asset) {
                // Ensure asset record exists
                Asset::updateOrCreate(
                    ['ip_mapping_id' => $ipMapping->id],
                    [
                        'category' => 'computer',
                        'status' => 'active',
                        'location' => $ipMapping->location,
                    ]
                );
            } else {
                // If it was an asset and now it's not, we might want to keep the record but mark category? 
                // Or just delete if category is computer. Let's delete to keep it simple as per user's "otomatis masuk".
                $ipMapping->asset()->where('category', 'computer')->delete();
            }
        });

        static::deleted(function ($ipMapping) {
            $ipMapping->asset()->delete();
        });
    }

    /**
     * Scope a query to only include active IP mappings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Validate IP address format (192.168.100.x).
     */
    public static function validateIpFormat($ip)
    {
        return preg_match('/^192\.168\.100\.\d{1,3}$/', $ip) === 1 || $ip === '127.0.0.1';
    }

    /**
     * Update the last used timestamp.
     */
    public function updateLastUsed()
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Get or create user for this IP mapping.
     */
    public function getOrCreateUser()
    {
        if ($this->user_id && $this->user) {
            return $this->user;
        }

        // Create a new user for this IP
        $user = User::create([
            'name' => $this->display_name,
            'email' => 'ip-' . str_replace('.', '-', $this->ip_address) . '@local.ip',
            'password' => bcrypt(\Illuminate\Support\Str::random(32)), // Random password, won't be used
            'role' => 'client',
            'is_ip_user' => true,
        ]);

        $this->update(['user_id' => $user->id]);

        return $user;
    }
}
