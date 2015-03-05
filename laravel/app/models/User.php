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
     * Timezone update constants
     */
    const TIMEZONE_UPDATE_NEVER = 'never';
    const TIMEZONE_UPDATE_ASK   = 'ask';
    const TIMEZONE_UPDATE_AUTO  = 'auto';

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
        'email',
        'username',
        'name',
        'lname',
        'role_id',
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
        'password',
        'password_confirmation',
        'terms',
        'timezone',
        'timezone_update',
        'status',
    );

    protected $niceNames = array(
        'name'  => 'First Name',
        'lname' => 'Last Name',
        'terms' => 'Terms of Use and Privacy Policy',
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
        ),
        'student-save' => array(
            'username'                  => array('required', 'max:60', 'unique:users'),
            'email'                     => array('required', 'email', 'max:100', 'unique:users'),
            'name'                      => array('required', 'max:50'),
            'lname'                     => array('required', 'max:50'),
            'timezone'                  => array('required', 'timezone'),
            'timezone_update'           => array('required', 'in:never,ask,auto'),
        ),
        'tutor-save' => array(
            'subject'                   => array('required', 'max:1000'),
            'university'                => array('max:255'),
            'other_experience'          => array('max:255'),
            'link_fb'                   => array('max:255'),
            'link_twitter'              => array('max:255'),
            'link_googleplus'           => array('max:255'),
            'link_thumblr'              => array('max:255'),
        ),
        'registration-save' => array(
            'terms'                     => array('accepted'),
        ),
        'password-save' => array(
            'password'                  => array('required', 'min:6', 'max:100', 'confirmed'),
        ),
        // This should only be validated when a new file is uploaded because
        // S3 files stored in db records will always fail this validation
        'profile-pic-upload'    => array(
            'profilepic'                => array('image', 'max:5000'), // 5MB
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
        return $this->hasMany('UserRate', 'userid')->orderBy('id', 'desc');
    }

    public function statuses()
    {
        return $this->hasMany('UserStatus', 'created_by_id')->orderBy('created_at', 'desc');
    }

    public function credit()
    {
        return $this->hasOne('UserCredit');
    }

    public function transactions()
    {
        return $this->hasMany('Transaction');
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
        $query->orderBy('average_rating', 'DESC');
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

    public function scopeHasTutor($query, User $tutor)
    {
        $query->whereHas('lessonsStudying', function($query) use($tutor){
                $query->where('tutor', '=', $tutor->id);
            });
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
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

	/**
	 * Returns our last name (using a different DB column)
	 *
	 * @TODO: replace things throughout the system to use the new DB naming system
	 *
	 * @return mixed|string
	 */
	public function getFirstNameAttribute()
	{
		return $this->name;
	}

	/**
	 * Sets our first name DB attribute (which is named differently under the hood)
	 *
	 * @TODO: replace things throughout the system to use the new DB naming system
	 *
	 * @param $value
	 */
	public function setFirstNameAttribute($value)
	{
		$this->attributes['name'] = $value;
	}

	/**
	 * Returns our last name (using a different DB column)
	 *
	 * @TODO: replace things throughout the system to use the new DB naming system
	 *
	 * @return mixed|string
	 */
	public function getLastNameAttribute()
	{
		return $this->lname;
	}

	/**
	 * Sets our first name DB attribute (which is named differently under the hood)
	 *
	 * @TODO: replace things throughout the system to use the new DB naming system
	 *
	 * @param $value
	 */
	public function setLastNameAttribute($value)
	{
		$this->attributes['lname'] = $value;
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

    public function getRatingStarsAttribute()
    {
        return round($this->average_rating, 0, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Returns whether the supplied password is correct for the current user
     * TODO: Replace the use of this with Auth::attempt and then remove this, now unnecessary, function
     * @param $password
     * @return bool
     */
    public function isPasswordCorrect($password)
    {
        return Auth::attempt(array(
                'username' => $this->username,
                'password' => $password,
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
            // Only change the password if it has actually been changed
            //  Note: $this->password is encrypted, $newPassword isn't yet
            if (!Hash::check($newPassword, $this->password)){
                // Magniloquent's autoHasher will encrypt the password, so we don't here
                $this->password = $newPassword;
                return $this->save();
            }
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
        $rate = $this->getActiveUserRateObject();
        return $rate->formattedRate;
    }

    /**
     * @return UserRate
     */
    public function getActiveUserRateObject()
    {
        if (count($this->rates) > 0){
            // Rates relation is sorted in descending order so the first rate is the currently set rate
            return $this->rates->first();
        } else {
            return new UserRate;
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

    public function formatLastMessageDateForHumans()
    {
        if ($this->last_message){
            return \Carbon\Carbon::createFromTimeStamp(strtotime($this->last_message))->diffForHumans();
        } else {
            return '';
        }
    }

    /**
     * @param $eventType
     * @return array|\Illuminate\Database\Eloquent\Model|static
     */
    public function logEvent($eventType, $description = '')
    {
        $logEntry = UserLog::create(array(
                'user_id'   => $this->id,
                'type'      => $eventType,
                'created'   => $this->freshTimestampString(),
                'description'   => $description,
            ));
        if (!$logEntry->id){
            Event::fire('user_log.errors', array($logEntry->errors()));
        }
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Determines whether a notification email should be sent immediately.
     * This is a stub for a future notifications system
     * @return bool
     */
    public function notifyImmediately()
    {
        return true;
    }

    public function notify(UserMessage $message, $viewName)
    {
        $subject = '[Botangle] ' . UserMessage::getEmailSubjectFromViewName($viewName, $message->sender->fullName);
        $user = $this;
        $viewData = [
            'messageBody'  => $message->body,
            'sender' => $message->sender->fullName,
            'recipient' => $message->recipient
        ];
        try{
            Mail::send('emails.message-wrapper', $viewData, function($message) use($user, $subject)
                {
                    $message->to($user->email, $user->fullName)->subject($subject);
                });
            return true;
        } catch(Exception $e){
            Event::fire('user.email-notification-failed', array($message, 'error' => $e->getMessage()));
            return false;
        }
    }

    /**
     * Puts together the details into an array for the summary card on TopCharts and User Search
     */
    public function getSummaryCardDetailsArray()
    {
        $details = [];
        if ($this->qualification){
            $details[] = e($this->qualification);
        }
        if ($this->extracurricular_interests){
            $details[] = e($this->extracurricular_interests);
        }
        return $details;
    }

    /**
     * Get mutator to shortcut getting the credit amount from related UserCredit object (if it exists)
     */
    public function getCreditAmountAttribute()
    {
        if ($this->credit){
            return $this->credit->amount;
        } else {
            return 0;
        }
    }

    public function getTimezoneCountryAttribute()
    {
        if($this->timezone){
            $tz = new DateTimeZone($this->timezone);
            return $tz->getLocation()['country_code'];
        } else {
            return '';
        }
    }

    public static function getTimezoneOptions()
    {
        $timezones = DateTimeZone::listIdentifiers();
        // We want the key and value to be the same
        return array_combine($timezones, $timezones);
    }

    public function getTimezoneForHumans()
    {
        if($this->timezone){
            $tz = new DateTimeZone($this->timezone);
            $loc = $tz->getLocation();
            $tzName = str_replace("_", " ", $tz->getName());
            if ($loc['comments']) {
                return $loc['comments'] . " ($tzName)";
            } else {
                return $tzName;
            }
        } else {
            return '';
        }

    }

    public function checkTimezoneWarning()
    {
        if ($this->timezone == 'UTC'){
            return Lang::get('app.timezone-warning', [
                    'loginRoute' => route('login'),
                    'timezone'      => $this->getTimezoneForHumans(),
                ]);
        }
    }

    public function getSubjectsArray()
    {
        $categories = explode(", ", $this->subject);
        $result = [];
        foreach ($categories as $item) {
            $itemName = strip_tags($item);
            if ($itemName){
                $result[$itemName] = $itemName;
            }
        }
        return $result;
    }

    public function getStudents()
    {
        $users = User::hasTutor($this)->get();
        $list = [];
        foreach($users as $user){
            $list[$user->id] = $user->fullName;
        }
        return $list;
    }

    public function recalculateRatings()
    {
        $reviews = $this->reviews;
        $this->review_count = $reviews->count();
        $this->average_rating = $reviews->sum('rating') / $this->review_count;
        return $this->save();
    }
}
