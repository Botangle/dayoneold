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
        return View::make('lessons/addNewModalContent', array(
                'expert'    => $expert,
                'student'   => Auth::user(),
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
        $inputs = Input::all();
        $inputs['duration'] = $inputs['duration'] / 60;
        $model->fill($inputs);
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
            ));
    }
}
