<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use DB;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

use App\Notifications\TeacherResetPasswordNotification;

class Manager extends Authenticatable
{

    // This trait has notify() method defined
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 
    	'last_name', 
    	'username',
        'password', 
    	'picture', 
    	'state_manager_id',
    	'identification_id',
    	'address_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    public function getFullNameInverseAttribute()
    {
        return "{$this->last_name} {$this->name}";
    }

    /**
     * Obtiene la relacion que hay entre el ente administrativo y el docente
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'manager_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y ente administrativo
     */
    public function identification()
    {
        return $this->belongsTo(Identification::class, 'identification_id');
    }

    /**
     * Obtiene la relacion que hay entre direccion y ente administrativo
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TeacherResetPasswordNotification($token));
    }

    /***/
    public static function getByHeadquarter($headquarter_id)
    {
        return self::select(DB::raw('DISTINCT managers.id'), DB::raw('CONCAT(managers.`name`," ",managers.last_name) AS name'), 'identification.identification_number')
        ->join('identification', 'managers.identification_id', '=', 'identification.id')
        ->join('teachers', 'teachers.manager_id', '=', 'managers.id')
        ->join('group_pensum', 'group_pensum.teacher_id', '=', 'teachers.id')
        ->join('group', 'group_pensum.group_id', '=', 'group.id')
        ->join('headquarter', 'group.headquarter_id', '=', 'headquarter_id')
        ->where('group.headquarter_id', '=', $headquarter_id)
        ->orderBy('name')
        ->get();
    }

    /***/
    public static function getByGroup($group_id)
    {
        return self::select(DB::raw('DISTINCT managers.id'), DB::raw('CONCAT(managers.name," ",managers.last_name) AS name'), 'identification.identification_number')
        ->join('identification', 'managers.identification_id', '=', 'identification.id')
        ->join('teachers', 'teachers.manager_id', '=', 'managers.id')
        ->join('group_pensum', 'group_pensum.teacher_id', '=', 'teachers.id')
        ->join('group', 'group_pensum.group_id', '=', 'group.id')
        ->where('group.id', '=', $group_id)
        ->orderBy('name')
        ->get();
    }
}
