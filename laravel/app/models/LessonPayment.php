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
            $transaction = Transaction::charge($this);

            // if we have success billing, we'll note the fact and save things
            if ($transaction) {
                $this->payment_complete = true;

                // @TODO: change from stripe_charge_id to just a transaction_id instead ...
                $this->stripe_charge_id = $transaction->id;
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

    /**
     * Get mutator that returns the payment amount formatted as a currency with a currency symbol
     */
    public function getAmountAttribute()
    {
        return $this->lesson->userRate->currency . number_format($this->payment_amount, 2);
    }

    /**
     * Get mutator that returns the payment amount formatted as a currency with a currency symbol
     */
    public function getAmountMinusFeeAttribute()
    {
        return $this->lesson->userRate->currency . number_format($this->payment_amount - $this->fee, 2);
    }
}
