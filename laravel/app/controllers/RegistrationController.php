<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as localAdapter;
use Aws\S3\S3Client;
use League\Flysystem\Adapter\AwsS3 as S3Adapter;

use Transit\Transformer\Image\CropTransformer;
use Transit\Transformer\Image\ResizeTransformer;

class RegistrationController extends BaseController {

    public function __construct(){
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getRegisterExpert()
    {
        return View::make('registration.registration', array(
                'mode'  => 'expert',
                'route' => 'register.expert',
            ));
    }

    public function getRegisterStudent()
    {
        return View::make('registration.registration', array(
                'mode'  => 'student',
                'route' => 'register.student',
            ));
    }

    public function postRegisterExpert()
    {
        return $this->processRegistration(Input::all());
    }

    public function postRegisterStudent()
    {
        return $this->processRegistration(Input::all());
    }

    protected function processRegistration($inputs)
    {
        $user = new User;

        /**
         * Configure MagniloquentContextsPlus for validation
         */
        $user->addContext(array('student-save', 'password-save', 'registration-save'));
        // This form can only be used to create experts and students (not Admins)
        if($inputs['mode'] == 'expert'){
            $user->addContext('tutor-save');
            $inputs['role_id'] = 2;
        } else {
            $inputs['role_id'] = 4;
        }
        // Only validate profilepic if a new file has been uploaded
        if (Input::hasFile('profilepic')){
            $user->addContext('profile-pic-upload');
        }

        if (isset($inputs['subject'])){
            $inputs['subject'] = implode(", ", $inputs['subject']);
        }
        $inputs['status'] = true;
        $user->fill($inputs);

        if (!$user->validate()){
            if (isset($inputs['profilepic'])) unset($inputs['profilepic']);
            $inputs['subject'] = explode(", ", $inputs['subject']);
            return Redirect::back()
                ->withErrors($user->errors())
                ->withInput($inputs)
                ->with('flash_error', trans("Please correct the following errors and try again:"));
        }

        /* Profilepic upload to Amazon S3 */
        if (Input::hasFile('profilepic')){
            $user->profilepic = $this->uploadProfilePicToS3();

            // Need to stop save validating the S3 filename, which will fail Laravel's image validation.
            //  Note: the original uploaded file has already passed Laravel's validation above
            $user->removeContext('profile-pic-upload');
        } else {
            // Added to prevent attempts to save a null value under certain circumstances
            $user->profilepic = '';
        }

        if ($user->save()){
            if ($user->isTutor()){
                Event::fire('user.expert-registration', array($user));
            } else {
                Event::fire('user.student-registration', array($user));
            }
            Auth::login($user);
            Event::fire('user.login', array($user));
            if (Auth::user()->isTutor()){
                return Redirect::route('user.billing')
                    ->with('flash_success', trans("You have successfully registered an account. Please add in your hourly rate and you will be set."));

            } else {
                return Redirect::route('user.my-account')
                    ->with('flash_success', trans("You have successfully registered an account! On to find a good tutor! :-)"));

            }
        }
        // Since save failed, we need to pass back the unencrypted password to the form (that may or may
        //  not have been autoHashed by Magniloquent before failing)
        $user->password = $inputs['password'];

        // Profile pic needs to be cleared if there's a failure saving
        if (isset($inputs['profilepic'])) unset($inputs['profilepic']);

        return Redirect::back()
            ->withErrors($user->errors())
            ->withInput($inputs)
            ->with('flash_error', trans("Please correct the following errors and try again:"));

    }

    /**
     * TODO: resolve having duplicate functions in UserController and RegistrationController
     * This uploads the profilepic to S3.
     * This is a quick and perhaps dirty implementation combining the original Botangle's use of Transit
     * and the rather nice FlySystem. Transit is only being used for image cropping and resizing
     * for consistency with the original system.
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    private function uploadProfilePicToS3()
    {
        $file = Input::file('profilepic');
        $extension = $file->guessClientExtension();
        $tmpPath = $file->getPath();
        $transitFile = new \Transit\File($file->getPathname());
        $transitFile = (new CropTransformer(array('width' => 250, 'height' => 250, 'aspect' => true)))->transform($transitFile, true);
        $transitFile = (new ResizeTransformer(array('width' => 250, 'height' => 250, 'aspect' => true)))->transform($transitFile, true);
        $transitFilename = basename($transitFile->path());
        // Need to get the transformed filename

        $local = new Filesystem(new localAdapter($tmpPath));
        $remote = new Filesystem(new S3Adapter(
            S3Client::factory(array(
                    'key'    => Config::get('services.s3.accessKey'),
                    'secret' => Config::get('services.s3.secretKey'),
                )),
            Config::get('services.s3.bucket'),
            Config::get('services.s3.profilepicFolder'),
            ['region'   => Config::get('services.s3.region')]
        ));

        if ($local->has($transitFilename)) {
            $contents = $local->read($transitFilename);
            $randomFilename = str_random(15).'.'.$extension;
            if (!$remote->write($randomFilename, $contents, [
                    'visibility'    => 'public',
                ]))
            {
                return Redirect::route('user.my-account')
                    ->with('flash_error', trans("Profile Picture could not be uploaded. Please try again."));
            }
        }
        if (Auth::check()){
            Event::fire('user.profilepic-uploaded', array(Auth::user()));
        }

        return Config::get('services.s3.url') .'/'. Config::get('services.s3.bucket') .'/'.
        Config::get('services.s3.profilepicFolder') . '/'.$randomFilename;
    }
}
