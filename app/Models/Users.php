<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ExcludeAdminScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'status',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExcludeAdminScope);
    }

    public function masterStatus()
    {
        return $this->belongsTo(MasterStatus::class, 'id');
    }
}