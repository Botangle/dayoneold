<?php

class StreamController extends BaseController {

	/**
     * Loads the filters for the controller actions
     */
    public function __construct()
    {
        $this->beforeFilter('auth', [
	        'except' => [ 'postCreate' ],
        ]);

        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getCreate()
    {
        $stream = new UserStream;
        $stream->user_id = Auth::user()->id;

        return View::make('new.stream.create', array(
                'model'     => $stream,
            ));
    }

    /**
     * Create a new stream
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate()
    {
        $model = new UserStream;
        $model->fill(Input::all());
        $model->user_id = Auth::user()->id;
	    $model->state = 1;

        if (!$model->validate()){
            return Response::json(array(
                    'result' => 'failed',
                    'errorMessage'  => trans("The new stream could not be created."),
                    'errors' => $model->errors()->all(),
                ));
        }

	    // then we'll generate our session id for this model and go!
	    // much better to put this somewhere else long-term as part of the save / create below
	    // but right now, we're cranking things out
        if ($model->createSessionAndSave()) {
            Event::fire('stream.created', array($model, Auth::user()));

	        return Redirect::intended( URL::route( 'new.stream.live', [ 'id' => $model->id ]) )
	                       ->with( 'flash_success', trans( "And 1... 2... 3... You're on the air!" ) );
        }

	    // @TODO: if it's JSON, that's not going to work well for us
        return Response::json(array(
                'result' => 'failed',
                'errorMessage'  => trans("A problem occurred while creating the new stream. Please try again."),
                'errors'        => array(),
            ));
    }

	/**
	 * @param $id
	 * @return \Illuminate\View\View
	 */
	public function getLive($id)
	{
		$stream = UserStream::findOrFail($id);

		return View::make('new.stream.live', array(
			'model'     => $stream,
		));
	}

	/**
	 * Stops a live recording
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function postStop($id)
	{
		$stream = UserStream::findOrFail($id);

		// make sure the user requesting the stream be cancelled is the same user
		// who started the stream ;-)
		if($stream->user_id != Auth::user()->id) {
			// @TODO: throw an exception
		}

		// then we'll turn off our live broadcast flag
		$stream->state = 0;

		// if this works, then we'll start streaming
		if ($stream->save()) {
			Event::fire('stream.stopped', array($stream, Auth::user()));

			return Redirect::intended( URL::route( 'home' ) )
			               ->with( 'flash_success', trans( "Ok, we've completed your broadcast.  Thanks for broadcasting with us!" ) );
		}

		// @TODO: if it's JSON, that's not going to work well for us
		return Response::json(array(
			'result' => 'failed',
			'errorMessage'  => trans("A problem occurred while stopping your stream. Please try again."),
			'errors'        => array(),
		));
	}
}
