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
        'role',
        'role_id',
        'status',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExcludeAdminScope);

        static::checkAndCreateTable();
    }

    protected static function checkAndCreateTable()
    {

        if (!Schema::hasColumn('users', 'role_id')) {
            DB::statement('ALTER TABLE users ADD COLUMN role_id BIGINT DEFAULT NULL');

            DB::table('users')->where('username', 'AdminIP')->update([
                'role_id' => '1',
            ]);

            DB::table('users')->where('username', 'PengurusIP')->update([
                'role_id' => '2',
            ]);

            DB::table('users')->where('username', 'TestUserIP')->update([
                'role_id' => '3',
            ]);

        }
    }
    public function masterStatus()
    {
        return $this->belongsTo(MasterStatus::class, 'id');
    }
    public function roles()
    {
        return $this->belongsTo(roles::class, 'role_id');
    }
}