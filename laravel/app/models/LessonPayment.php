<?php

class LessonPayment extends Eloquent {

    public $fillable = array(
        'lesson_id',
        'student_id',
        'tutor_id',
        'payment_complete',
    );

    const UPDATED_AT = 'payment_date';

    public function setCreatedAtAttribute($value)
    {
        // Do nothing.
    }

    public function lesson()
    {
        return $this->belongsTo('Lesson');
    }

    public function student()
    {
        return $this->belongsTo('User', 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo('User', 'tutor_id');
    }

    public function charge() {
        try {
            $fee = $this->payment_amount * .15; // take a 15% commission

            // @TODO: add in a DB transaction here to handle making sure things all go through together

            // format our fee to work well with Stripe and when we save it to the DB
            $this->fee = sprintf('%0.2f', $fee);

            // TODO: implement this with billing
//            $transaction = new Transaction;
//            $results = $transaction->charge((int)$lessonId, (int) $tutorId, (int) $studentId, $amount, $fee);
            $results = array(
                'id'    => rand(1, 9999),
            );

            // if we have success billing, we'll note the fact and save things
            if (is_array($results)) {
                $this->payment_complete = true;

                // @TODO: change from this to just a transaction_id instead ...
                $this->stripe_charge_id = $results['id'];
                $this->save();

                Event::fire('lesson.paid', array($this->lesson, Auth::user()));
            }
        } catch (Exception $e) {
            // otherwise
            Event::fire('lesson.payment-failed', array($this, Auth::user(), $e->getMessage()));
            Log::error(sprintf('Error charging for lesson payment %s : %s', $this->id, $e->getMessage()));
        }

        // otherwise we'll leave this for the system to bill again somehow
        // @TODO: should we have an auto-retry system setup here?

    }
}
