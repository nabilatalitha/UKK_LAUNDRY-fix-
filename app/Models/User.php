<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
 
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table ='user';
    protected $primaryKey='id_user';
 
    protected $fillable = [
        'nama_user', 'username', 'password','role'
    ];
 
    protected $hidden = [
        'password', 'remember_token',
    ];
 
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}