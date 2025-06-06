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
        "remember_token",
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

    public function school(){
        return $this->hasOne(School::class);
    }

    public function grades(){
        return $this->hasMany(SchoolGrade::class);
    }

    public function modelHasRole(){
        return $this->hasOne(ModelHasRole::class,"model_id","id");
    }



    public function teacher(){
        return $this->hasOneThrough(Teacher::class, ModelHasRole::class,'model_id','id','id','idInRole');
    }


    public function student(){
        return $this->hasOneThrough(Student::class, ModelHasRole::class,'model_id','id','id','idInRole');
    }

    public function students(){
        return $this->hasMany(Student::class,"fatherPhone","phone");
    }

    public function roleOfUser(){
        switch ($this->role){
            case config("constant.roles.assistant"):
            case config("constant.roles.teacher"):
                return $this->teacher();
                break;
            case config("constant.roles.student"):
            case config("constant.roles.parent"):
                return $this->student();
                break;
            default:
                return $this->school();
        }
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
