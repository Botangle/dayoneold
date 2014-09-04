<?php
/**
 * User: martyn ling <mling@str8-4ward.com>
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

        $lesson->sendLessonMessage(UserMessage::LESSON_REVIEWED, $authUser);

    }
}
