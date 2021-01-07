<?php

namespace App;

use App\Models\AccountActivity;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function accountActivity()
    {
        return $this->hasMany(AccountActivity::class, 'user_id', 'id');
    }

    public function createActivity(float $amount, string $description, $date=null)
    {
        $date = ($date) ? Carbon::parse($date) : Carbon::now();

        $this->accountActivity()->create([
            'description' => $description,
            'amount' => $amount * 100, // we are dealing in cents here
            'activity_date' => $date
        ]);
    }

    public function updateActivity(Request $request)
    {

        $activity = $this->accountActivity()->find($request->id);

        $input = $request->except(['id']);

        $input['activity_date'] = Carbon::parse($input['activity_date']);

        $activity->activity_date = $input['activity_date'];
        $activity->amount = $input['amount'] * 100;
        $activity->description = $input['description'];

        $activity->save();
    }
}
