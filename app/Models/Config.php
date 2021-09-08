<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected $table = "config";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value'
    ];

    public static function get(string $key)
    {
        $config_row = Config::where('key', $key)->first();
        if ($config_row) return json_decode($config_row->value);
        return null;
    }

    public static function set(string $key, $value)
    {
        Config::updateOrCreate(
            ['key' => $key],
            ['value' => json_encode($value)]
        );
    }
}
