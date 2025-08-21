<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key','value'];
    public $timestamps = true;

    public static function v(string $key, $default=null) {
        $all = Cache::rememberForever('site:settings', fn() => self::pluck('value','key')->toArray());
        if (!array_key_exists($key, $all)) return $default;
        $raw = $all[$key];
        $json = json_decode($raw, true);
        return $json ?? $raw;
    }
    public static function set(string $key, $value): void {
        self::updateOrCreate(['key'=>$key], ['value'=> is_array($value)? json_encode($value) : $value]);
        Cache::forget('site:settings');
    }
}
