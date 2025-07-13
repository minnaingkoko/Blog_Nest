<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo_path',
        'owner_email',
        'color_theme',
        'social_links'
    ];

    protected $casts = [
        'social_links' => 'array'
    ];

    // Singleton pattern to always get the first record
    public static function getSettings()
    {
        return self::firstOrCreate([], [
            'site_name' => config('app.name'),
            'owner_email' => config('mail.from.address'),
            'color_theme' => 'light'
        ]);
    }
}