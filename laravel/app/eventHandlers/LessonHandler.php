<?php
/**
 * User: martyn
 * Date: 04/09/14
 * Time: 10:12
 */

/**
 * Class LessonHandler
 */
class LessonHandler {

    /**
     * Handle the lesson.create event
     * @param Lesson $lesson
     * @param User $authUser
     */
    public function onCreate(Lesson $lesson, User $authUser)
    {
        // Add to User log
        $lesson->logUserEvent('booked-lesson', $authUser);

        $lesson->sendLessonMessage(UserMessage::LESSON_NEW, $authUser);

        // Check that billing has been setup before the lesson sessions are created
        if ($lesson->billingReady()){
            $lesson->prepareLessonTools();
        } else {
            Session::put('credit_refill_needed', true);
            Session::put('new_lesson_id', $lesson->id);
        }
    }

    public function onUpdate(Lesson $lesson, User $authUser)
    {
        // Add to User log
        $lesson->logUserEvent('updated-lesson', $authUser);

        $lesson->sendLessonMessage(UserMessage::LESSON_CHANGED, $authUser);

    }

    public function onConfirm(Lesson $lesson, User $authUser)
    {
        // Add to User log
        $lesson->logUserEvent('confirmed-lesson', $authUser);

        $lesson->sendLessonMessage(UserMessage::LESSON_CONFIRMED, $authUser);

        // Just in case the ids weren't assigned at creation (billing would be resolved for confirmation to be possible
        $lesson->prepareLessonTools();
    }

    public function onReview(Lesson $lesson, User $authUser)
    {
        // Add to User log
        $lesson->logUserEvent('reviewed-lesson', $authUser);

        // Recalculate the reviewed user's average rating and review count
        $lesson->tutorUser->recalculateRatings();

        $lesson->sendLessonMessage(UserMessage::LESSON_REVIEWED, $authUser);

    }

    public function onPaid(Lesson $lesson, User $authUser)
    {
        // Add to User log
        $lesson->logUserEvent('paid-lesson', $authUser);

    }

    public function onPaymentFailed(Lesson $lesson, User $authUser, $errorMessage)
    {
        // Add to User log
        $lesson->logUserEvent('failed-lesson-payment', $authUser, $errorMessage);

    }
}
