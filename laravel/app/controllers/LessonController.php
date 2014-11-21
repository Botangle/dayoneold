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

        $model->setLessonAtFromInputs(Input::get('lesson_date'), Input::get('lesson_time'));
        $model->setRateFromTutor();

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

            // If credit is needed for the lesson, we'll need to pass back where to buy credit
            if (Session::has('credit_refill_needed')){
                $redirectTo = route('transaction.buy');
            } else {
                $redirectTo = '';
            }

            return Response::json(array(
                    'id'        => $model->id,
                    'result'    => 'success',
                    'redirect'  => $redirectTo,
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

        if ($lesson->userIsTutor(Auth::user())){
            $otherUser = $lesson->studentUser;
            $otherDesc = 'student';
        } else {
            $otherUser = $lesson->tutorUser;
            $otherDesc = 'tutor';
        }

        return View::make('lessons/modalContent', array(
                'model'     => $lesson,
                'otherUser' => $otherUser,
                'otherDesc' => $otherDesc,
                'submit'    => 'lesson.edit',
                'subtitle'  => trans('Update Lesson Details'),
                'title'     => trans('Edit Lesson'),
            ));
    }

    public function postEdit()
    {
        $model = Lesson::findOrFail(Input::get('id'));

        $model->fill(Input::all());

        $model->setLessonAtFromInputs(Input::get('lesson_date'), Input::get('lesson_time'));

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
            Event::fire('lesson.reviewed', array($model, Auth::user()));
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
            return Redirect::route('transaction.buy')
                ->with(
                    'flash_error',
                    trans("You need ". $lesson->estimatedCost() ." credits to pay to be able to pay for your lesson.<br>
                    Please topup your credit."
                    ));
        }
        // Check to see if the other user is still present and update sync_status if necessary
        $lesson->checkOtherUserPresence();

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
        $lesson->updateAttendance(Auth::user());

        // Store the current status, so that we process the data passed through according to this status
        $status = $lesson->sync_status;

        // Check to see if the other user is still present and update sync_status if necessary
        $lesson->checkOtherUserPresence();

        switch($status){
            case Lesson::SYNC_STATUS_WAITING:
                if ($lesson->syncInitiated()){
                    $responseArray = [
                        'countdown'         => $lesson->getSyncCountdown(),
                        'lessonComplete'    => false,
                        'newPrice'          => $lesson->getCurrentPaymentDue(),
                        'status'            => $lesson->sync_status,
                        'totalTime'         => $lesson->seconds_used,
                    ];
                } else {
                    $responseArray = [
                        'newPrice'          => $lesson->getCurrentPaymentDue(),
                        'lessonComplete'    => false,
                        'status'            => $lesson->sync_status,
                        'totalTime'         => $lesson->seconds_used,
                    ];
                }
                break;
            case Lesson::SYNC_STATUS_SYNCING:
                $lesson->syncStarting();
                $responseArray = [
                    'countdown'         => $lesson->getSyncCountdown(),
                    'lessonComplete'    => false,
                    'newPrice'          => $lesson->getCurrentPaymentDue(),
                    'status'            => $lesson->sync_status,
                    'totalTime'         => $lesson->seconds_used,
                ];
                break;
            case Lesson::SYNC_STATUS_STARTING:
                if (!$lesson->syncActivate(Input::get('status'))){
                    $lesson->syncStarting();
                    $responseArray = [
                        'countdown'         => $lesson->getSyncCountdown(),
                        'lessonComplete'    => false,
                        'newPrice'          => $lesson->getCurrentPaymentDue(),
                        'status'            => $lesson->sync_status,
                        'totalTime'         => $lesson->seconds_used,
                    ];
                    break;
                }
                // We want the activated lesson to continue through and be processed as now active,
                // so no break here

            case Lesson::SYNC_STATUS_ACTIVE:
                if ($lesson->updateSecondsUsed(
                    (int) Input::get('secondsUsed'),
                    Input::get('status') == Lesson::SYNC_STATUS_FINISHED
                )) {
                    $lesson->updatePaymentDue();
                }
                // If the lesson has finished
                if ($lesson->syncFinished(Input::get('status'))){
                    $lesson->payment->lesson_complete_tutor = true;
                    $lesson->payment->lesson_complete_student = true;
                    if (!$lesson->payment->save()){
                        Log::alert(sprintf(
                                'Error saving lesson payment changes for lesson payment id %s : %s',
                                $lesson->payment->id,
                                $lesson->payment->errors()->toJSON()
                            ));
                        throw new Exception("There was a problem updating lesson payment.");
                    }
                    if (!$lesson->payment->payment_complete) {
                        $lesson->payment->charge();
                    }
                } else {
                    $responseArray = [
                        'newPrice'          => $lesson->getNextPriceIncrement(),
                        'lessonComplete'    => $lesson->payment->lesson_complete_student,
                        'status'            => $lesson->sync_status,
                        'totalTime'         => $lesson->seconds_used,
                    ];
                    break;
                }
            case Lesson::SYNC_STATUS_FINISHED:
                // This would only be accessed by the second user to exit the lesson, so all the processing
                //  ought to have been completed by the other user
                $responseArray = [
                    'newPrice'          => $lesson->payment->payment_amount,
                    'lessonComplete'    => $lesson->payment->lesson_complete_student,
                    'status'            => $lesson->sync_status,
                    'totalTime'         => $lesson->seconds_used,
                ];
                break;

        }
        return Response::json($responseArray);

    }

    public function getPayment(Lesson $lesson)
    {
        return View::make('lessons.payment', array(
                'lesson'    => $lesson,
                'isStudent' => $lesson->userIsStudent(Auth::user()),
            ));
    }
}
