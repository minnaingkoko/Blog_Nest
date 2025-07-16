<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'color_theme',
        'contact_email',
        'contact_phone',
        'contact_address',
        'google_map_embed',
        'about_us_content',
        'about_us_image',
        'contact_us_content',
        'contact_us_image',
        'footer_content',
    ];
}