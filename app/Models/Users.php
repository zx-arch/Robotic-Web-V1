<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ExcludeAdminScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'role',
        'status',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExcludeAdminScope);
    }
}