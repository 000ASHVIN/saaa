<?php

namespace App;

/**
 * Moodle.
 */
class Moodle
{
    private $token;
    private $domainName;
    private $serverUrl;
    private $restformat;

    public function __construct()
    {
        $this->restformat = 'json';

        $this->token = env('MOODLE_API_TOKEN', '');
        $this->domainName = env('MOODLE_API_URL', 'https://www.anasource.com/team4/basic_moodle');

        $this->serverUrl = $this->domainName.'/webservice/rest/server.php'.'?wstoken='.$this->token;
    }

    public function register($user1)
    {
        $functionName = 'core_user_create_users';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        // $user1 = new \stdClass();
        // $user1->username = $user->name;
        // $user1->password = 'Password123!';
        // $user1->firstname = $user->first_name;
        // $user1->lastname = $user->last_name;
        // $user1->email = $user->email;
        // $user1->city = 'apo';
        // $user1->country = 'IN';
        // $user1->auth = 'manual';
        $enrolments = [$user1];
        $params = ['users' => $enrolments];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function userUpdate($user)
    {
        $functionName = 'core_user_update_users';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $user = [$user];
        $params = ['users' => $user];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }
    
    public function courseCreate($course)
    {
        $functionName = 'core_course_create_courses';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $enrolments = [$course];
        $params = ['courses' => $enrolments];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function courseUpdate($course)
    {
        $functionName = 'core_course_update_courses';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $enrolments = [$course];
        $params = ['courses' => $enrolments];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function courseEnroll($enrollment)
    {
        $functionName = 'enrol_manual_enrol_users';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $enrolments = [$enrollment];
        $params = ['enrolments' => $enrolments];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function courseUnenroll($enrollment)
    {
        $functionName = 'enrol_manual_unenrol_users';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $enrolments = [$enrollment];
        $params = ['enrolments' => $enrolments];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function userGradesReport($userid, $courseid)
    {
        $functionName = 'gradereport_user_get_grade_items';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        // $user = [$user];
        $params = [
            'userid' => $userid,
            'courseid' => $courseid
        ];

        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function getCourseList()
    {
        $functionName = 'core_course_get_courses';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat;
        $params = [];
        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function getSingleCourseDetails($id)
    {
        $functionName = 'core_course_get_courses_by_field';

        $serverurl = $this->serverUrl.'&wsfunction='.$functionName.'&moodlewsrestformat='.$this->restformat.'&field=id&value='.$id;
        $params = [];
        try {
            $response = $this->send($serverurl, $params);

            $data = json_decode($response, true);

            return $data;
        } catch (\Exception $e) {

            return json_decode($e->getMessage(), true);
        }
    }

    public function send($url,$params)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($params), ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
