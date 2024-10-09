<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        "hasChanged",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function grades(){
        return $this->hasMany(UserGrade::class);
    }

    public function modelHasRole(){
        return $this->hasOne(ModelHasRole::class,"model_id","id");
    }

    public function bells(){
        return $this->hasMany(Bell::class);
    }

    public function teacher(){
        return $this->hasOneThrough(Teacher::class, ModelHasRole::class,'model_id','id','id','idInRole');
    }


    public function student(){
        return $this->hasOneThrough(Student::class, ModelHasRole::class,'model_id','id','id','idInRole');
    }

    public function absents(){
        return $this->hasMany(Absent::class);
    }

    public function getRoleAttribute(){
        return $this->roles->first()->id;
    }

    public function getRoleIdAttribute(){
        return $this->modelHasRole->iRoleId;
    }
}
