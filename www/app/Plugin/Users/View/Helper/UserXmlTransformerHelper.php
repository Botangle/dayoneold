<?php
App::uses('AppHelper', 'View/Helper');

class UserXmlTransformerHelper extends AppHelper {

    /**
     * Pass in a user array and we'll spit out only what we want our XML system to get
     *
     * @param $user
     * @return array
     */
    public function transformFullProfile($user)
    {
        $transformedUser = array(
            'id'                            => $user['id'],
            'username'                      => $user['username'],
            'firstname'                     => $user['name'],
            'lastname'                      => $user['lname'],
            'email'                         => $user['email'],
            'website'                       => $user['website'],
            'bio'                           => $user['bio'],
            'profilepic'                    => $this->transformProfilePic($user['profilepic']),
            'timezone'                      => 'UTC',
            'qualification'                 => $user['qualification'],
            'teaching_experience'           => $user['teaching_experience'],
            'extracurricular_interests'     => $user['extracurricular_interests'],
            'university'                    => $user['university'],
            'other_experience'              => $user['other_experience'],
            'expertise'                     => $user['expertise'],
            'aboutme'                       => $user['aboutme'],
            'is_online'                     => $user['is_online'],
            'is_featured'                   => $user['is_featured'],
            'created'                       => $user['created'],
            'updated'                       => $user['updated'],
        );

        return $transformedUser;
    }

    public function transformAuthUser($user)
    {
        $transformedUser = array(
            'id'                            => $user['id'],
            'username'                      => $user['username'],
            'firstname'                     => $user['name'],
            'lastname'                      => $user['lname'],
            'profilepic'                    => isset($user['profilepic'])
                                                    ? $this->transformProfilePic($user['profilepic'])
                                                    : $this->transformProfilePic(''),
        );

        return $transformedUser;
    }

    public function transformProfilePic($profilePic)
    {
        if($profilePic == '') {
            return 'https://www.botangle.com/images/botangle-default-pic.jpg';
        } elseif(strpos($profilePic, '/') === 0) {
            return 'https://www.botangle.com' . $profilePic;
        } else {
            return $profilePic;
        }
    }
}