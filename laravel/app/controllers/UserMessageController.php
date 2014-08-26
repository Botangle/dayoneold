<?php

class UserMessageController extends BaseController {

    /**
     * Loads the filters for the controller actions
     */
    public function __construct()
    {
        $this->beforeFilter('auth');

        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function index($username = null)
    {
        if ($username == null){
            $user = Auth::user();
        } else {
            $user = User::where('username', $username)->firstOrFail();
        }

        // Get all the users that the current user has exchanged messages with including the date of the
        //  last_message - this will serve as a messaging menu to view message threads with other users
        $userList = Auth::user()->getUsersMessaged();

        if($username == null && $userList->count() == 0){
            return View::make('user.messages-none');
        } else {
        }


        if ($user->id == Auth::user()->id){
            // Since the user is looking at their own user messages page, we need to decide which
            //   user from the userlist to show messages for... perhaps the
            //   one which has the oldest unread last_message, ... otherwise the first on the list
            $user = $userList->first();
            foreach($userList as $listUser){
                $message = UserMessage::find($listUser->message_id);
                if ($message){
                    if($message->send_to == Auth::user()->id && !$message->readmessage){
                        $user = $listUser;
                    }
                }
            }

        } elseif (!$userList->find($user->id)){
            // Since the user is looking at someone else's messages page, check to make that other user
            //  is in the userList. If they're not, add them to the beginning of the list, so that
            //  the logged in user can send them a message, even though there's no current message history
            //  between them
                $userList->prepend($user);
        }

        $messages = UserMessage::between($user, Auth::user())->orderBy('date')->get();

        // Mark any messages sent to Auth::user as read (would be better to set that after they've actually
        //  been read.
        // This is rather crude, but is an improvement on the CakePHP Botangle version.
        //  TODO: consider finding a better solution to marking messages as read
        foreach($messages as $message){
            if ($message->readmessage){
                continue;
            }
            if($message->send_to == Auth::user()->id){
                $message->readmessage = true;
                $message->save();
            }
        }

        return View::make('user.messages', array(
                'messages'      => $messages,
                'userList'      => $userList,
                'viewingUser'   => $user,
            ));

    }

    public function postCreate()
    {
        $recipient = User::findOrFail(Input::get('send_to'));

        $message = new UserMessage;
        $message->fill(array_merge(Input::all(), array(
                    'sent_from' => Auth::user()->id,
                    'date'      => $message->freshTimestampString(),
                )));
        if ($message->save()) {
            Event::fire('user.sent-message', array($message));
            return Redirect::route('user.messages', $recipient->username)
                ->with('flash_success', trans("You have sent a message."));

        } else {
            return Redirect::route('user.messages', $recipient->username)
                ->with(Input::all())
                ->with('flash_error', trans("There was a problem sending your message."))
                ->withErrors($message->errors());

        }

    }
}
