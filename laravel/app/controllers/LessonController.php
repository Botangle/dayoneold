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
                'mode'      => 'create',
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
            return Response::json(array(
                    'result' => 'success',
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
                'mode'      => 'edit',
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
        if (!$model->validate()){
            return Response::json(array(
                    'result' => 'failed',
                    'errorMessage'  => trans("The lesson could not be updated."),
                    'errors' => $model->errors()->all(),
                ));

        }

        if ($model->manageChangesThenSave(true)) {
            return Response::json(array(
                    'result' => 'success',
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
            return Response::json(array(
                    'result' => 'success',
                ));
        }

        return Response::json(array(
                'result' => 'failed',
                'errorMessage'  => trans("A problem occurred while saving the review. Please try again."),
                'errors'        => array(),
            ));
    }
}
