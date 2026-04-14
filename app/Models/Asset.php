<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    public const ZONES = [
        'Lantai 1' => [
            'Zona A', 'Zona B', 'Zona C', 'Zona D', 'Zona E', 
            'Zona F', 'Zona G', 'Zona H', 'Zona I', 'Zona J', 
            'Zona K', 'Zona L', 'Zona M', 'Zona N', 'Zona O'
        ],
        'Lantai 2' => [
            'Zona 2A', 'Zona 2B', 'Zona 2C'
        ]
    ];

    protected $fillable = [
        'category',
        'name',
        'ip_mapping_id',
        'description',
        'location',
        'status',
        'remote_app_name',
        'remote_address',
        'remote_password',
    ];

    /**
     * Get the IP mapping associated with this asset (for computer category).
     */
    public function ipMapping()
    {
        return $this->belongsTo(IpMapping::class);
    }

    /**
     * Get the maintenance logs for this asset.
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get the display name of the asset.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->category === 'computer' && $this->ipMapping) {
            return $this->ipMapping->display_name;
        }
        return $this->name;
    }
}
