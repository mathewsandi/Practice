<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'avatar', 'facebook_id', 'github_id', 'twitter_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function statuses()
    {
        return $this->hasMany('App\Status');
    }

    public function events(){
        return $this->hasMany('App\Event');
    }

    public function friends(){
        return $this->belongsToMany('App\User', 'friends_users', 'user_id', 'friend_id');
    }

    public function addConnection(User $user){
        $this->friends()->attach($user->id);
    }

    public function removeConnection(User $user){
        $this->friends()->detach($user->id);
    }

    public function hasFriend(User $user){
        return $this->friends->contains($user);
    }

    public function notifications(){
        return $this->hasMany('App\Notification');
    }

    public function messages(){
        return $this->hasMany('App\Message');
    }

    public function participants(){
        return $this->hasMany('App\Participant');
    }

    public function threads(){
        return $this->belongsToMany('App\Thread', 'participants', 'user_id', 'thread_id');
    }
}
