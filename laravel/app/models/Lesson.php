<?php

use Carbon\Carbon;

class Lesson extends MagniloquentContextsPlus {

    const ROLE_STUDENT = 'student';
    const ROLE_TUTOR = 'tutor';
    const ROLE_STUDENT_AND_TUTOR = 'mixed';

    const EVENT_COLOR_STUDENT = '#FED2A1';
    const EVENT_COLOR_TUTOR = '#CF6F07';
    const EVENT_COLOR_MIXED = '#F38918';

    const HISTORY_TYPE_CREATED      = 'created';
    const HISTORY_TYPE_CHANGE       = 'change';
    const HISTORY_TYPE_CONFIRMATION = 'confirmation';
    const HISTORY_TYPE_CANCELLATION = 'cancellation';

    /**
     * Adjusting our Laravel created / updated column names to match
     * what we had in Botangle
     *
     * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
     * Note: this table doesn't have a CREATED_AT, so a mutator is in place lower down to prevent that
     * causing problems.
     */
    const UPDATED_AT = 'add_date';

    public $dates = ['lesson_at'];

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'tutor',
        'student',
        'lesson_at',
        'duration',
        'rate',
        'rate_type',
        'subject',
        'notes',
        'active',
        'is_confirmed',
    );

    protected $guarded = array('history');

    /**
     * Former and Form classes will use these niceNames instead of deriving them from the db column names
     * where not specific label is defined on a form, etc.
     * @var array
     */
    protected $niceNames = array(
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
            'tutor'                     => array('required', 'exists:users,id'),
            'student'                   => array('required', 'exists:users,id'),
            'lesson_at'                 => array('required', 'date_format:Y-m-d G:i:s'),
            'rate'                      => ['required', 'numeric'],
            'rate_type'                 => ['required', 'in:permin,perhour'],
            'duration'                  => array('numeric'),
            'subject'                   => array('required'),
        ),
        // additional validation rules for the following contexts
        'update'    => array(),
        'create'    => array(),
    );

    private $_openTokToken;

    /**
     * Lesson doesn't have an created_at column but is does have a updated_at column (add_date) - see CONST above
     * This mutator prevents Eloquent from trying to include the created_at column
     * in db updates.
     * @param $value
     */
    public function setCreatedAtAttribute($value)
    {
        // Do nothing.
    }

    public function studentUser()
    {
        return $this->belongsTo('User', 'student');
    }

    public function tutorUser()
    {
        return $this->belongsTo('User', 'tutor');
    }

    public function payment()
    {
        return $this->hasOne('LessonPayment');
    }

    public function review()
    {
        return $this->hasOne('Review');
    }

    /**
     * Generally we're only interested in active lessons
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('active', true);
    }

    /**
     * @param $query
     * @param User $user
     */
    public function scopeInvolvingUser($query, User $user)
    {
        $query->where(function($query) use($user){
            $query->where('tutor', $user->id)
                ->orWhere('student', $user->id);
        });
    }

    /**
     * Lessons where the user is the one who needs to confirm changes
     * @param $query
     * @param User $user
     */
    public function scopeUnread($query, User $user)
    {
        // this whole thing needs to be wrapped in the outer where, otherwise there might be unexpected results
        //  when combined with other queries and scopes
        $query->where(function($query) use($user){
                // Is student for lesson where student hasn't read
                $query->where(function($query) use($user){
                        $query->where('student', $user->id)
                            ->where('readlesson', false);
                    });
                // or is tutor lesson where tutor hasn't read
                $query->orWhere(function($query) use($user){
                        $query->where('tutor', $user->id)
                            ->where('readlessontutor', false);
                    });
            });
    }

    /**
     * Lessons that are still proposals (active, unconfirmed and in the future)
     * @param $query
     */
    public function scopeProposals($query)
    {
        $query->where('is_confirmed', false)
            ->where('lesson_at', '>=', Carbon::now()->format('Y-m-d G:i:s'));
    }

    /**
     * Lessons that are upcoming (active, confirmed and in the future)
     * @param $query
     */
    public function scopeUpcoming($query)
    {
        $query->where('is_confirmed', true)
            ->where('lesson_at', '>=', Carbon::now()->format('Y-m-d G:i:s'));
    }

    /**
     * Lessons that are past (active, confirmed and in the past)
     * @param $query
     */
    public function scopePast($query)
    {
        $query->where('is_confirmed', true)
            ->where('lesson_at', '<', Carbon::now()->format('Y-m-d G:i:s'));
    }

    /**
     * Returns the calendar event title corresponding to the current lesson
     * If the current logged in user is either the student or tutor, the title provides details.
     *
     * @param $loggedInUserId
     * @param $viewingUserId
     * @return string
     */
    public function getCalendarEventTitle($loggedInUserId, $viewingUserId)
    {
        // Since subject and username are user entered, HTML encode them since they'll be directly output
        //   to a 3rd party js plugin that may not encode the text
        $loggedInUser = User::find($loggedInUserId);
        $lessonTime = $this->lesson_at->timezone($loggedInUser->timezone)->format('g:i A');

        if ($this->studentUser->id == $loggedInUserId){
            $title = e($this->subject);
            if ($loggedInUserId == $viewingUserId){
                $title .= "\n   ". Lang::get('Tutor: '). e($this->tutorUser->username);
            } else {
                $title .= "\n   ". Lang::get('Student: '). e($this->studentUser->username);
            }
            $title .= "\n   ". trans('When: '). $lessonTime;

        } elseif ($this->tutorUser->id == $loggedInUserId){
            $title = e($this->subject);
            if ($loggedInUserId == $viewingUserId){
                $title .= "\n   ". Lang::get('Student: '). e($this->studentUser->username);
            } else {
                $title .= "\n   ". Lang::get('Tutor: '). e($this->tutorUser->username);
            }
            $title .= "\n   ". trans('When: '). $lessonTime;

        } else {
            $title = "Lesson at {$lessonTime}";
        }
        if (!$this->is_confirmed){
            $title .= ' ('. trans ('unconfirmed') . ')';
        }
        return $title;
    }

    /**
     * Works out the day type (used to determine the appropriate color to display on calendar)
     * given the current lesson and the previous lessons on the same day
     * @param $viewingUserId
     * @param $loggedInUserId
     * @param null $currentDayType
     * @return null|string
     */
    public function getDayType($viewingUserId, $loggedInUserId, $currentDayType = null)
    {
        if ($currentDayType == null){
            return $this->getLessonDayType($viewingUserId, $loggedInUserId);
        } else {
            $latestDayType = $this->getLessonDayType($viewingUserId, $loggedInUserId);
            if ($currentDayType != $latestDayType){
                return Lesson::ROLE_STUDENT_AND_TUTOR;
            } else {
                return $currentDayType;
            }
        }
    }

    /**
     * Gets the calendar day type corresponding to the current lesson for the user profile
     * @param $viewingUserId
     * @param $loggedInUserId
     * @return string
     */
    public function getLessonDayType($viewingUserId, $loggedInUserId)
    {
        if ($this->tutorUser->id != $loggedInUserId && $this->studentUser->id != $loggedInUserId){
            return Lesson::ROLE_STUDENT_AND_TUTOR;
        }

        if ($this->tutorUser->id == $viewingUserId) {
            return Lesson::ROLE_TUTOR;
        } elseif ($this->studentUser->id == $viewingUserId){
            return Lesson::ROLE_STUDENT;
        } else {
            return Lesson::ROLE_STUDENT_AND_TUTOR;
        }
    }

    /**
     * Returns the color to be used for the day background on the calendar based on the $dayType
     * @param $dayType
     * @return string
     */
    public static function getCalendarEventColor($dayType)
    {
        switch($dayType){
            case self::ROLE_TUTOR:
                return self::EVENT_COLOR_TUTOR;
            case self::ROLE_STUDENT:
                return self::EVENT_COLOR_STUDENT;
            case self::ROLE_STUDENT_AND_TUTOR:
                return self::EVENT_COLOR_MIXED;
        }
    }

    public static function getDurationOptions()
    {
        $options = array();
        for($i = 1; $i < 25; $i++){
            if ($i == 1){
                $text = '30 ' . trans('minutes');
            } elseif ($i == 2){
                $text = '1 ' . trans('hour');
            } else {
                $text = ($i/2) .' ' . trans('hours');
            }
            $options[$i*30] = $text;
        }
        return $options;
    }

    /**
     * Adds history $eventData of $type to the history attribute.
     * Note: this data is held on the Lesson model and
     * the Lesson model MUST BE SAVED to save your history changes.
     *
     * @param $type Should be one of the HISTORY_TYPE constants
     * @param $eventData Could be a string or an array
     */
    public function addHistory($type, $eventData)
    {
        $history = json_decode($this->history);
        if (empty($history)){
            $history = array();
        }
        $history[] = array(
            'type'      => $type,
            'user_id'   => Auth::user()->id,
            'username'  => Auth::user()->username, // for readability of the raw history
            'when'      => (new \DateTime())->format('Y-m-d H:i:s'),
            'data'      => $eventData,
        );
        $this->history = json_encode($history);
    }

    /**
     * Update the lesson status attributes according to the changes made since the last version of the lesson
     * @param bool $isNew
     * @param bool $isConfirming
     * @return bool
     */
    public function manageChangesThenSave($isNew = false, $isConfirming = false)
    {
        if ($isNew){
            $this->addHistory(self::HISTORY_TYPE_CREATED, null);
            $this->updateStatusesAfterChanges();
            return $this->save();

        } elseif ($isConfirming) {
            $this->confirm(Auth::user());
            $this->addHistory(self::HISTORY_TYPE_CONFIRMATION, null);
            return $this->save();

        } else {
            $changesArray = array();
            foreach ($this->getDirty() as $field => $newData)
            {
                $oldData = $this->getOriginal($field);
                if ($oldData != $newData)
                {
                    $changesArray[$field] = array(
                        'from'  => $oldData,
                        'to'    => $newData,
                    );
                }
            }
            if (count($changesArray) > 0){
                $this->addHistory(self::HISTORY_TYPE_CHANGE, $changesArray);
                $this->updateStatusesAfterChanges();
                return $this->save();
            }
        }
        return true;
    }

    /**
     * Updates the lesson statuses to reflect that the current logged in user has made changes to the lesson
     * that need to be reviewed and confirmed by the other user related to the lesson.
     * Note: this now handles the circumstance where a 3rd party could amend the lesson requiring both the tutor
     * and the student to confirm the changes. Not sure it'll ever be needed but it was simple to add.
     */
    public function updateStatusesAfterChanges()
    {
        $loggedInUserId = Auth::user()->id;
        if ($loggedInUserId == $this->student) {
            // the tutor hasn't confirmed this change yet, but the student has
            $this->readlessontutor = 0;
            $this->readlesson = 1;

            // the student made the last change and the tutor didn't
            $this->laststatus_student = 1;
            $this->laststatus_tutor = 0;

        } else if ($loggedInUserId == $this->tutor) {
            // the student hasn't confirmed this change yet, but the tutor has
            $this->readlesson = 0;
            $this->readlessontutor = 1;

            // the tutor made the last change and the student didn't
            $this->laststatus_tutor = 1;
            $this->laststatus_student = 0;
        } else {
            $this->readlesson = 0;
            $this->readlessontutor = 0;
            $this->laststatus_tutor = 0;
            $this->laststatus_student = 0;
        }
        $this->is_confirmed = false;
    }

    /**
     * User $user confirms that they have accepted the lesson
     * @param User $user
     */
    public function confirm(User $user)
    {
        if ($user->id == $this->tutor){
            $this->readlessontutor = 1;
            $this->laststatus_tutor = 1;
            if ($this->readlesson){
                $this->is_confirmed = true;
            }
        } elseif ($user->id == $this->student){
            $this->readlesson = 1;
            $this->laststatus_student = 1;
            if ($this->readlessontutor){
                $this->is_confirmed = true;
            }
        }
    }

    /**
     * Returns the lesson_at attribute formatted according to $format and the user's timezone
     * @param $format
     * @param User $forUser The User object for the person who will be viewing the dates (so the time
     *                 should be switched to their timezone
     * @return null|string
     */
    public function formattedLessonAt($format, User $forUser = null)
    {
        if ($this->lesson_at){
            if (!$forUser){
                $forUser = Auth::user();
            }
            return $this->lesson_at->timezone($forUser->timezone)->format($format);
        } else {
            return null;
        }
    }

    /**
     * TODO: replace uses of this function with formattedLessonAt, then remove this function
     * Returns the date part of the lesson_at attribute formatted according to $format and the user's timezone
     * @param $format
     * @param User $forUser The User object for the person who will be viewing the dates (so the time
     *                 should be switched to their timezone
     * @return null|string
     */
    public function formatLessonDate($format, User $forUser = null)
    {
        return $this->formattedLessonAt($format, $forUser);
    }

    /**
     * TODO: replace uses of this function with formattedLessonAt, then remove this function
     * Returns the time part of the lesson_at attribute formatted according to $format and the user's timezone
     * @param $format
     * @param User $forUser The User object for the person who will be viewing the dates (so the time
     *                 should be switched to their timezone
     * @return null|string
     */
    public function formatLessonTime($format, User $forUser = null)
    {
        return $this->formattedLessonAt($format, $forUser);
    }

    public function setLessonAtFromInputs($lessonDate, $lessonTime)
    {
        // The date/time are provided in the user's timezone
        $lessonInUserTimezone = Carbon::createFromFormat(
            "Y-m-d G:i",
            $lessonDate .' '. $lessonTime,
            Auth::user()->timezone
        );
        // lesson_at should always be UTC
        $this->lesson_at = $lessonInUserTimezone->timezone('UTC');
    }

    public function getDurationAttribute($value)
    {
        return $value * 60;
    }

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = $value / 60;
    }

    public function getDisplayDurationAttribute()
    {
        $duration = $this->duration / 60;
        if($duration == .5) {
            return '30 '. trans('minutes');
        } elseif($duration == 1.0) {
            return '1 ' . trans('hour');
        } else {
            return $duration .' ' . trans('hours');
        }
    }

    public function getDisplayRateAttribute()
    {
        return Lang::get('app.rate-text', [
                'rate' => $this->rate,
                'rateType' => Lang::get('app.rate-type-text-'. $this->rate_type),
            ]);
    }

    /**
     * Can the current user confirm this lesson
     * @param User $user
     * @return bool
     */
    public function userCanConfirm(User $user)
    {
        if (!$this->is_confirmed){
            if ($this->userIsTutor($user) && !$this->readlessontutor){
                return true;
            } elseif (($this->userIsStudent($user) && !$this->readlesson)){
                return true;
            }
        }
        return false;
    }

    /**
     * Is $user the lesson's tutor
     * @param User $user
     * @return bool
     */
    public function userIsTutor(User $user)
    {
        return ($user->id == $this->tutor);
    }

    /**
     * Is $user the lesson's student
     * @param User $user
     * @return bool
     */
    public function userIsStudent(User $user)
    {
        return ($user->id == $this->student);
    }

    public function determineMessageRecipient(User $authUser)
    {
        if ($this->userIsTutor($authUser)){
            return $this->studentUser;
        } elseif ($this->userIsStudent($authUser)){
            return $this->tutorUser;
        } else {
            return null;
        }
    }

    /**
     * Sends a message to the other user (i.e. the one who isn't the current user)
     *
     * @param $viewName The name of the view to generate the body of the message combined with $this Lesson
     * @param User $authUser The authenticated user
     * @return null|UserMessage
     */
    public function sendLessonMessage($viewName, User $authUser)
    {
        // TODO A better implementation would be to call UserMessage::send(Lesson $lesson,...)
        //  where Lesson implements an interface that allows UserMessage to get the data it requires
        //  instead of this function

        $recipient = $this->determineMessageRecipient($authUser);
        if (!$recipient){
            return null;
        }
        $message = new UserMessage;
        if (!$message->send($authUser, $recipient, $viewName, array(
                    'model'     => $this,
                    'recipient' => $recipient,
                ))){
            // TODO Log $message->errors()

        }
    }

    /**
     * Note: $authUser is passed in instead of referencing Auth::user() since this might be a queued job initiated
     * by a CRON task.
     * @param $eventType
     * @param User $authUser The authenticated user
     * @param $description = ''
     * @return array|\Illuminate\Database\Eloquent\Model|static
     */
    public function logUserEvent($eventType, User $authUser, $description = '')
    {
        // TODO Improve by replacing this function with dependency injection of Lesson to UserLog::new()
        $logEntry = UserLog::create(array(
                'user_id'           => $authUser->id,
                'type'              => $eventType,
                'related_type_id'   => $this->id,
                'created'           => $this->freshTimestampString(),
                'description'       => $description,
            ));
        if (!$logEntry->id){
            Event::fire('user_log.errors', array($logEntry->errors()));
        }
    }

    /**
     * Prepare for the lesson by generating the OpenTok session and Twiddla meeting ids
     */
    public function prepareLessonTools()
    {
        // TODO: Consider queueing these for completion by a CRON task because it
        //  creates a long pause for the person who inadvertently triggered this
        if (!$this->opentok_session_id){
            $this->generateOpenTokSessionId();
        }
        if (!$this->twiddlameetingid){
            $this->generateTwiddlaMeetingId();
        }
    }

    /**
     * Generate the Twiddla meeting id for the lesson and store it in the db
     * @return Lesson
     */
    protected function generateTwiddlaMeetingId()
    {
        $twiddla = new Twiddla(Config::get('services.twiddla.username'), Config::get('services.twiddla.password'));
        $this->twiddlameetingid = $twiddla->getMeetingId();
        return $this->save();
    }

    /**
     * Generate the OpenTok session id for the lesson and store it in the db
     * @return Lesson
     */
    protected function generateOpenTokSessionId()
    {
        $openTok = new OpenTok(Config::get('services.openTok.apiKey'), Config::get('services.openTok.apiSecret'));
        $this->opentok_session_id = $openTok->generateSessionId();
        return $this->save();
    }

    /**
     * Returns true if the student has enough credit to pay for this lesson
     */
    public function billingReady()
    {
        return ($this->userIsTutor(Auth::user()) || $this->studentUser->creditAmount >= $this->estimatedCost());
    }

    /**
     * @return float|mixed
     */
    public function estimatedCost()
    {
        // Note: duration is returned by a get mutator as minutes
        $rate = $this->userRate;
        if ($rate->price_type == UserRate::RATE_PER_MINUTE){
            return $this->duration * $rate->rate;
        } else {
            return $this->duration / 60 * $rate->rate ;
        }
    }

    public function getOpenTokTokenAttribute()
    {
        if (!isset($this->_openTokToken)){
            $openTok = new OpenTok(Config::get('services.openTok.apiKey'), Config::get('services.openTok.apiSecret'));
            $this->_openTokToken = $openTok->generateToken($this->opentok_session_id);
        }
        return $this->_openTokToken;
    }

    /**
     * Function taken straight from Old Botangle's whiteboard view
     */
    private function secondsToTime($seconds)
    {
        // extract hours
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        // return the final array
        $obj = array(
            "h" => (int) $hours,
            "m" => (int) $minutes,
            "s" => (int) $seconds,
        );
        return $obj;
    }

    /**
     * Returns the number of seconds (in hh:mm:ss format) spent in the lesson
     * @return string|void
     */
    public function getWhiteboardTimerAttribute()
    {
        // I'm just hacking in the code from the original Botangle that was actually in the view
        // It's not pretty, but it's not yet my remit to re-write this code
        $timer = "00:00:00";
        if($this->userIsStudent(Auth::user())){
            $timerArray =  $this->secondsToTime($this->student_lessontaekn_time);
        }else{
            $timerArray =  $this->secondsToTime($this->remainingduration);
        }
        $timer = sprintf("%02d:%02d:%02d", $timerArray['h'], $timerArray['m'], $timerArray['s']);
        return $timer;
    }

    /**
     * Returns the number of seconds remaining for the lesson (from the Expert's perspective)
     * @return mixed
     */
    public function getSecondsRemainingAttribute()
    {
        /**
         * First things first, attribute remainingduration is actually the number of seconds that
         * the Expert has been in the meeting for the lesson. So, this function returns the time
         * remaining in seconds
         */
        $lessonLength = $this->duration * 60 * 60;
        return $lessonLength - $this->remainingduration;
    }

    public function hasFinished()
    {
        $lessonLength = $this->duration * 60 * 60;
        return ($lessonLength <= $this->student_lessontaekn_time);
    }

    public function getTwiddlaMeetingUrlAttribute()
    {
        $twiddlaUrl = Config::get('services.twiddla.apiUrl');
        $twiddlaUrl .= http_build_query(array(
                'sessionid'    => $this->twiddlameetingid,
                'guestname'    => Html::entities(Auth::user()->username),
                'autostart'    => 1,
                'exiturl'      => 'https://www.botangle.com', //url('/'),
            ));
        return $twiddlaUrl;
    }

    /**
     * This mutator encapsulates a workaround to support the old system's concept that a user
     * is either an expert or a student not both. However, that restriction is no longer enforced
     * and the role effectively is determined per lesson.
     * @return int
     */
    public function getRoleTypeAttribute()
    {
        if ($this->userIsTutor(Auth::user())){
            return 2;
        } else {
            return 4;
        }
    }

    /**
     * This is used to disable the exit lesson button if the lesson payment is complete
     * Moved this code out of the view... still not the best implementation... but that's my
     * remit for now
     */
    public function getExitDisabledAttribute()
    {
        if ($this->payment){
            if($this->payment->lesson_complete_student){
                return "disabled='disabled'";
            }
        }
        return "";
    }

    /**
     * Only the id realtime is manipulated by the timer. So, it's effectively switching the
     * timer off if the lesson has been completed.
     * Moved this code out of the view... still not the best implementation... but that's my
     * remit for now
     */
    public function getCountdownIdAttribute()
    {
        if ($this->payment){
            if($this->payment->lesson_complete_student){
                return "realtime2";
            }
        }
        return 'realtime';
    }

    /**
     * Formats the notes field so that includes line breaks in the email
     * @return mixed
     */
    public function getNotesForEmailAttribute()
    {
        return str_replace("\r\n", "<br>", e($this->notes));
    }

    /**
     * There are separate timers kept for students and experts (seems like an unnecessary complication to me or
     * a bad solution to the wrong problem)
     * Anyway, this code has more or less been taken straight from the old botangle without significant change
     * @param $role
     * @return int
     */
    public function updateTimer($role)
    {
        // Admin
        if ($role == 2) {
            $this->remainingduration += 60;
            $totalTime = $this->remainingduration;

        // Student
        } else if ($role == 4) {
            $this->student_lessontaekn_time += 60;
            $totalTime = $this->student_lessontaekn_time;
        }
        $this->save();

        return $totalTime;
    }

    /**
     * Returns the UserRate for the lesson
     */
    public function getUserRateAttribute()
    {
        // Rather than use a related UserRate object, the user rate attributes are stored in Lesson
        // So, we create (but don't store) a new UserRate, set the attributes and return it
        $userRate = new UserRate;
        $userRate->rate = $this->rate;
        $userRate->price_type = $this->rate_type;
        return $userRate;
    }

    /**
     * Sets the Lesson's rate based upon the Tutor's latest rate
     */
    public function setRateFromTutor()
    {
        $userRate = $this->tutorUser->getActiveUserRateObject();
        if ($userRate->rate){
            $this->rate = $userRate->rate;
            $this->rate_type = $userRate->price_type;
        } else {
            $this->rate = 0;
            $this->rate_type = UserRate::RATE_PER_HOUR;
        }
        return $this->save();
    }

    /**
     * Determines whether the lesson can be started or not
     * If it is more than $flex minutes before or after the end of the lesson, return false
     * @param $flex
     * @return bool
     */
    public function canBeStarted($flex = 0)
    {
        return (!$this->isBeforeStartableTime($flex) && !$this->isAfterEndTime($flex));
    }

    public function isBeforeStartableTime($flex = 0)
    {
        return ($this->lesson_at->subMinutes($flex) > Carbon::now());
    }

    public function isAfterEndTime($flex = 0)
    {
        return ($this->lesson_at->addMinutes($this->duration + $flex) > Carbon::now());
    }
}
