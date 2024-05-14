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
        'count_failed_login'
    ];

    protected static function boot()
    {
        parent::boot();

        if (!Schema::hasColumn('users', 'count_failed_login')) {
            DB::statement('ALTER TABLE users ADD COLUMN count_failed_login BIGINT UNSIGNED');
        }

        static::addGlobalScope(new ExcludeAdminScope);
    }

    public function masterStatus()
    {
        return $this->belongsTo(MasterStatus::class, 'id');
    }
}