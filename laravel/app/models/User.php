<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends MagniloquentContextsPlus implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

    /**
     * Adjusting our Laravel created / updated column names to match
     * what we had in Cake
     *
     * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
     */
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'name',
        'lname',
        'subject',
        'qualification',
        'teaching_experience',
        'extracurricular_interests',
        'university',
        'other_experience',
        'expertise',
        'profilepic',
        'link_fb',
        'link_twitter',
        'link_googleplus',
        'link_thumblr',
    );

    protected $niceNames = array(
        'name'  => 'First Name',
        'lname' => 'Last Name',
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
        ),
        "change-password" => array(

        ),
        'student-save' => array(
            'name'                      => array('required|max:50'),
            'lname'                     => array('required|max:50'),
            'profilepic'                => array('image', 'max:5000'), // 5MB
        ),
        'tutor-save' => array(
            'subject'                   => array('required'),
            'university'                => array('max:255'),
            'other_experience'          => array('max:255'),
            'link_fb'                   => array('max:255'),
            'link_twitter'              => array('max:255'),
            'link_googleplus'           => array('max:255'),
            'link_thumblr'              => array('max:255'),
        ),
        'update'    => array(),
        'create'    => array(),
    );

    /**
     * Relationship for user reviews (reviews of this user by other users)
     * @return mixed
     */
    public function reviews()
    {
        return $this->hasMany('Review', 'rate_to')->orderBy('add_date', 'desc');
    }

    public function rates()
    {
        return $this->hasMany('UserRate', 'userid');
    }

    public function statuses()
    {
        return $this->hasMany('UserStatus', 'created_by_id')->orderBy('created_at', 'desc');
    }

    public function lessonsStudying()
    {
        return $this->hasMany('Lesson', 'student')->where('active', true);
    }

    public function lessonsTutoring()
    {
        return $this->hasMany('Lesson', 'tutor')->where('active', true);
    }

    public function messagesSent()
    {
        return $this->hasMany('Message', 'sent_from');
    }

    public function messagesReceived()
    {
        return $this->hasMany('Message', 'send_to');
    }

    /**
     * Scopes our users down to just active users
     *
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    /**
     * Scopes our users down to just show our featured users on the homepage
     *
     * @param $query
     */
    public function scopeFeatured($query)
    {
        $query->where('is_featured', '=', 1);
    }

    /**
     * Scopes our users down to just online users
     *
     * @param $query
     */
    public function scopeOnline($query)
    {
        $query->where('is_online', 1);
    }

    public function scopeTutor($query)
    {
        $query->where('role_id', 2);
    }

    public function scopeAverageRating($query)
    {
        $query->leftJoin('reviews', 'reviews.rate_to', '=', 'users.id')
            ->select(array('users.*',
                    DB::raw('COUNT(reviews.id) as review_count'),
                    DB::raw('AVG(rating) as ratings_average')
                ))
            ->groupBy('users.id')
            ->orderBy('ratings_average', 'DESC');
    }

    public function scopeLessonsSummary($query)
    {
        $query->leftJoin('lessons', 'lessons.tutor', '=', 'users.id')
            ->select(array('users.*',
                    DB::raw('COUNT(lessons.id) as lessons_count'),
                    DB::raw('SUM(duration) as total_duration')
                ))
            ->where('active', 1)
            ->groupBy('users.id');
    }

    public function scopeLastMessageSent($query, User $toUser)
    {
        $query->leftJoin('usermessages', 'usermessages.sent_from', '=', 'users.id')
            ->select(array('users.*', DB::raw('usermessages.id as message_id'),
                    DB::raw('MAX(usermessages.date) as last_message'),
                ))
            ->whereRaw('usermessages.send_to = '. $toUser->id)
            ->groupBy('users.id');
    }

    public function scopeLastMessageReceived($query, User $fromUser)
    {
        $query->leftJoin('usermessages', 'usermessages.send_to', '=', 'users.id')
            ->select(array('users.*', DB::raw('usermessages.id as message_id'),
                    DB::raw('MAX(usermessages.date) as last_message'),
                ))
            ->whereRaw('usermessages.sent_from = '. $fromUser->id)
            ->groupBy('users.id');
    }

    /**
     * Get the password for the user.
     *
     * @TODO: replace this hack with a proper use of Bcrypt hashing setup long-term: http://stackoverflow.com/a/23409614/334913
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return Hash::make($this->password);
    }

    /**
     * Builds a quick getter (useable at $user->picture) that gets the appropriate image
     *
     * @return mixed|string
     */
    public function getPictureAttribute()
    {
        return ($this->profilepic != null) ? $this->profilepic : "/images/default.png";
    }

    /**
     * Returns our user's fullname ($user->full_name)
     *
     * @TODO: localize this as needed so it works better in countries where names work differently (China, Japan, etc)
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucfirst($this->name) . ' ' . ucfirst($this->lname);
    }

    /**
     * @return bool
     */
    public function isTutor()
    {
        return ($this->role_id == 2) ? true : false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->role_id == 1) ? true : false;
    }

    /**
     * Whether the specified user is online or not
     * @param boolean $isOnline
     */
    public function setOnlineStatus($isOnline)
    {
        $this->is_online = $isOnline;
        $this->save();
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings_average, 0, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Returns the encrypted version of $password
     * @param $password
     * @return string
     */
    protected function encryptPassword($password)
    {
        $salt = Config::get('auth.cake.salt');
        return sha1($salt . $password);
    }

    /**
     * Returns whether the supplied password is correct for the current user
     * @param $password
     * @return bool
     */
    public function isPasswordCorrect($password)
    {
        return Auth::attempt(array(
                'username' => $this->username,
                'password' => $this->encryptPassword($password)
            ));
    }

    /**
     * Updates the user's password to newPassword if the oldPassword matches
     * @param $oldPassword
     * @param $newPassword
     * @return bool
     */
    public function updatePassword($oldPassword, $newPassword)
    {
        if ($this->isPasswordCorrect($oldPassword)){
            $this->password = $this->encryptPassword($newPassword);
            return $this->save();
        }
        return false;
    }

    /**
     * There is currently a 1->many relationship in database but only 1 rate should be active.
     * For now, this function will handle the selection of the appropriate rate so that any future
     * schema changes should only require changes to be made here.
     */
    public function getActiveRateAttribute()
    {
        if (count($this->rates) > 0){
            // select the first rate...(like old system)
            $rate = $this->rates->first();
            $formattedRate =  $rate->formattedRate;
            return $formattedRate;
        } else {
            return UserRate::getCurrency() . 0;
        }
    }

    /**
     * Get the latest UserStatus object for the User
     * @return mixed
     */
    public function getLatestStatusAttribute()
    {
        return $this->statuses->first();
    }

    /**
     * Lists the users that $this user has sent/received messages with along with the last_message date
     * @return mixed
     */
    public function getUsersMessaged()
    {
        // Get the Users who sent a message to this user with the date the last message was sent
        $receivedFromUsers = User::lastMessageSent($this)->orderBy('last_message', 'desc')->get();

        // Get the Users who received a message from this user with the date the last message was received
        $sentToUsers = User::lastMessageReceived($this)->orderBy('last_message', 'desc')->get();

        // Merge the arrays to get 1 item per other user containing the latest date that a message was sent
        //   either to or from this user

        // Identify the users who this user has only sent a message as a basis
        $users = $sentToUsers->diff($receivedFromUsers);

        // Then add those that have both sent and received recording the last message date
        foreach($receivedFromUsers as $fromUser){
            if ($sentToUsers->contains($fromUser->id)){
                $sentUser = $sentToUsers->find($fromUser->id);
                if ($sentUser->last_message > $fromUser->last_message){
                    $users[] = $sentUser;
                } else {
                    $users[] = $fromUser;
                }
            } else {
                $users[] = $fromUser;

            }
        }

        // Then sort the array by the last_message date descending
        $users->sort(function($a, $b)
            {
                $a = $a->last_message;
                $b = $b->last_message;
                if ($a === $b) {
                    return 0;
                }
                return ($a < $b) ? 1 : -1;
            });
        return $users;
    }

}
