<?php

class LessonController extends BaseController {

    /**
     * Loads the filters for the controller actions
     */
    public function __construct()
    {
        $this->beforeFilter('auth');

        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('ajax', array('only' => array('createWithExpert')));
    }

    /**
     * Not currently being used, but not removing for now.
     * @param $expertId
     * @return \Illuminate\View\View
     */
    public function createWithExpert($expertId)
    {
        $expert = User::find($expertId);
        if (!$expert){
            App::abort('404', trans('Invalid value passed for Expert'));
        }

        $model = new Lesson;
        $model->tutor = $expert->id;
        $model->student = Auth::user()->id;

        return View::make('lessons/modalContent', array(
                'model'     => $model,
                'submit'    => 'lesson.create',
                'subtitle'  => trans('Propose Lesson Meeting'),
                'title'     => trans('Add New Lesson'),
            ));
    }

    /**
     * Books a lesson (data passed via AJAX)
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate()
    {
        $model = new Lesson;
        $model->fill(Input::all());

        /* A little hack because the validation of lesson_time must be G:i:s, so that a freshly retrieved
         * lesson from the db passes validation. However, data coming from the datetimepicker is just G:i
         * Unfortunately, using a get mutator doesn't work, since it's not automatically called before the
         * validation is called. I'm not spending any more time on this for now.
         * MJL - 2014-08-13
         */
        $model->lesson_time = $model->formatLessonTime('G:i:s');

        if (!$model->validate()){
            return Response::json(array(
                    'result' => 'failed',
                    'errorMessage'  => trans("The new lesson could not be created."),
                    'errors' => $model->errors()->all(),
                ));
        }

        // Since the form data has been validated, we just need to switch on the appropriate
        //   revision flags and send a message
        if ($model->manageChangesThenSave(true)) {
            Event::fire('lesson.created', array($model, Auth::user()));
            return Response::json(array(
                    'id'        => $model->id,
                    'result'    => 'success',
                ));

        }

        return Response::json(array(
                'result' => 'failed',
                'errorMessage'  => trans("A problem occurred while creating the new lesson. Please try again."),
                'errors'        => array(),
            ));
    }

    /**
     * Returns the modal dialog content for editing $lesson
     * @param $lesson - bound to the model in routes.php, so it should already have been looked up
     * @return \Illuminate\View\View
     */
    public function getEdit($lesson)
    {
        if (!$lesson->userIsTutor(Auth::user()) && !$lesson->userIsStudent(Auth::user())){
            App::abort('403', trans('Unauthorized access to lesson'));
        }

        return View::make('lessons/modalContent', array(
                'model'     => $lesson,
                'submit'    => 'lesson.edit',
                'subtitle'  => trans('Update Lesson Details'),
                'title'     => trans('Edit Lesson'),
            ));
    }

    public function postEdit()
    {
        $model = Lesson::findOrFail(Input::get('id'));

        $model->fill(Input::all());
        /* A little hack because the validation of lesson_time must be G:i:s, so that a freshly retrieved
         * lesson from the db passes validation. However, data coming from the datetimepicker is just G:i
         * Unfortunately, using a get mutator doesn't work, since it's not automatically called before the
         * validation is called. I'm not spending any more time on this for now.
         * MJL - 2014-08-13
         */
        $model->lesson_time = $model->formatLessonTime('G:i:s');

        if (!$model->validate()){
            return Response::json(array(
                    'result'        => 'failed',
                    'errorMessage'  => trans("The lesson could not be updated."),
                    'errors'        => $model->errors()->all(),
                ));

        }

        if ($model->manageChangesThenSave(false)) {
            Event::fire('lesson.updated', array($model, Auth::user()));
            return Response::json(array(
                    'id'        => $model->id,
                    'result'    => 'success',
                ));

        }

        return Response::json(array(
                'result' => 'failed',
                'errorMessage'  => trans("A problem occurred while creating the new lesson. Please try again."),
                'errors'        => array(),
            ));

    }

    /**
     * Returns the modal dialog content for reviewing $lesson
     * @param $lesson - bound to the model in routes.php, so it should already have been looked up
     * @return \Illuminate\View\View
     */
    public function getReview($lesson)
    {
        if (!$lesson->userIsStudent(Auth::user())){
            App::abort('403', trans('Only the student can review a lesson.'));
        }

        return View::make('reviews/modalContent', array(
                'mode'      => 'create',
                'model'     => $lesson,
                'submit'    => 'lesson.review',
                'subtitle'  => trans('How did you rate it?'),
                'title'     => trans('Review Lesson'),
            ));
    }

    public function postReview()
    {
        $model = Lesson::findOrFail(Input::get('lesson_id'));

        if (Input::get('rate_by') != Auth::user()->id){
            return Response::json(array(
                    'result' => 'failed',
                    'errorMessage'  => trans("Only the student can review a lesson."),
                    'errors'        => array(),
                ));
        }

        $review = new Review;
        $review->fill(Input::all());
        if (!$review->validate()){
            return Response::json(array(
                    'result' => 'failed',
                    'errorMessage'  => trans("The review could not be created."),
                    'errors' => $review->errors()->all(),
                ));
        }

        if ($review->save()) {
            Event::fire('lesson.reviewed', array($review, Auth::user()));
            return Response::json(array(
                    'id'        => $model->id,
                    'result'    => 'success',
                ));
        }

        return Response::json(array(
                'result' => 'failed',
                'errorMessage'  => trans("A problem occurred while saving the review. Please try again."),
                'errors'        => array(),
            ));
    }

    /**
     * Confirms the lesson if the current user is able to do so
     * @param $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getConfirm(Lesson $lesson)
    {
        if ($lesson->userCanConfirm(Auth::user())){
            if ($lesson->manageChangesThenSave(false, true)) {
                Event::fire('lesson.confirmed', array($lesson, Auth::user()));
                return Redirect::back()
                    ->with('flash_success', trans("You have confirmed your lesson."));
            } else {
                return Redirect::back()
                    ->with('flash_error', trans("There was a problem confirming your lesson. Please try again."));
            }
        }
        try {
            return Redirect::back()
                ->with('flash_error', trans("You are not authorized to confirm this lesson."));
        } catch (Exception $e){
            return Redirect::route('user.lessons')
                ->with('flash_error', trans("You are not authorized to confirm that lesson."));
        }
    }

    public function getWhiteboard(Lesson $lesson)
    {
        if ($lesson->billingReady()){
            if (!$lesson->opentok_session_id){
                $lesson->prepareLessonTools();
            }
        } else {
            // TODO: decide what should be done if billing isn't ready
        }

        return View::make('lessons.whiteboard', array(
                'model'     => $lesson,
            ));
    }

    /**
     * Input values sent
     * - roleType (2 = expert, 4 = student)
     * - completeLesson (only sent if we're trying to bill this lesson, will be one in that case)
     */
    public function postUpdateTimer(Lesson $lesson)
    {
        $totalTime = 0;
        $lessonComplete = false;
        $userRate = null;

        $pleaseCompleteLesson = false;
        if (Input::has('completeLesson') && Input::get('completeLesson')){
            $pleaseCompleteLesson = true;
        }

        $role = (int) Input::get('roleType');
        if ($role != 2 && $role != 4) {
            throw new Exception("Sorry, things aren't working.");
        }

        if (!$lesson->payment){
            $lessonPayment = LessonPayment::create(array(
                'lesson_id'         => $lesson->id,
                'student_id'        => $lesson->student,
                'tutor_id'          => $lesson->tutor,
                'payment_complete'  => 0,
            ));
        } else {
            $lessonPayment = $lesson->payment;
        }

        if ($lessonPayment->lesson_complete_tutor && $lessonPayment->lesson_complete_student) {
            $lessonComplete = true;
        }

        // if our lesson payment is not complete, then we've got a bunch of things we want to do
        if (!$lessonComplete) {

            // update our lesson timer depending on our role
            // we do this for both parties on a regular basis to keep folks honest
            // TODO: investigate potential for timers to get out of sync with each other, which could really
            //  cause problems down the line
            $totalTime = $lesson->updateTimer($role);

            // retrieve our user rate and hang on to it so we can re-use it in a bit
            // @TODO: long-term, we want to be pulling this user rate from the Lesson
            // where we keep it to prevent tutors from raising rates after the lesson gets scheduled
            $userRate = UserRate::where('userid', $lesson->tutor)->first();

            // figure out the payment amount
            $lessonPayment->payment_amount = $userRate->calculateTutorTotalAmount($totalTime);

            // if someone wants to end the lesson, then we want to record that
            if ($pleaseCompleteLesson) {
                $lessonPayment->lesson_complete_tutor = true;
                $lessonPayment->lesson_complete_student = true;
            }
            if (!$lessonPayment->save()){
                Log::alert(sprintf(
                        'Error saving lesson payment changes for lesson payment id %s : %s',
                        $lessonPayment->id,
                        $lessonPayment->errors()->toJSON()
                    ));
            }

            // then, if our payment isn't complete yet, then let's bill for it
            // we don't leave this outside for fear of having race conditions between the student and the tutor
            // we'd really like the first person to ask for this to be completed to be the one who calculates totals
            // theoretically, if the second person makes it past the lesson payment query above before the first person
            // charges things here, we could end up with a double-billing ... :-/
            if ($pleaseCompleteLesson && !$lessonPayment->payment_complete) {
                $lessonPayment->charge();
            }
        }

        /**
         * response sent back is JSON
         * - specifically, we're interested in lesson_complete_student on the frontend to notify the student if the lesson is over)
         *      we'll then notify the student about what is going on
         */
        if ($lessonComplete) {
            // @TODO: we want to send back different information requesting that this person get sent to the receipt page
            return Response::json(array('lessonComplete' => 1));

        } else {
            // we want to show them the updated information
            return Response::json(array(
                    'newPrice' => $userRate->calculateTutorTotalAmount($totalTime + 60),
                    'lessonComplete' => $lessonPayment->lesson_complete_student,
                    'totalTime' => $totalTime,
                ));
        }
    }

    public function getPayment(Lesson $lesson)
    {

    }
}
