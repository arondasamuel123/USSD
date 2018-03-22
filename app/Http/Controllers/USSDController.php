<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class USSDController extends Controller
{
    protected $user_current_level;

    public function index(Request $request)
    {
        $text = $request->get('text');
        $phonenumber = $request->get('phonenumber');

        $input = $this->getInput($text);

        switch ($input['level']) {
            case 0:
            $response = $this->getMainMenu($phonenumber);
            break;

            case 1:
            $response = $this->getCityInput($input,$phonenumber);
            break;

            case 2:
            $response = $this->getAccountType($input,$phonenumber);
            break;
            case 3:
            $response = $this->register($input,$phonenumber);

            default:
            $response = $this->getErrorMessage();
            break;

        }


        $this->sendResponse($response, 1);
    }


    public function getMainMenu($phonenumber)
    {
        $user = User::where('phonenumber',$phonenumber)->first();
        if(!$user)
        {
            $user->phonenumber = $phonenumber;
            $user->save();
        }
        return "Please enter your name". PHP_EOL; //"1.Plumber".PHP_EOL. "2.Electrician" .PHP_EOL. "Mama Wa Nguo";
    }
    public function getCityInput($input, $phonenumber) {
        $message = $input["message"];
        $user = User::where('phonenumber',$phonenumber)->first();
        if($user)
        {
            $user->name = $message;
            $user->save();
            return "Please enter your city". PHP_EOL;
        }
        return "user not found". PHP_EOL;
    }
    public function getAccountType($input, $phonenumber) {
        $message = $input["message"];
        $user = User::where('phonenumber',$phonenumber)->first();
        if($user)
        {
            $user->city = $message;
            $user->save();
          return "Please choose your account type".PHP_EOL. "1.Employer".PHP_EOL. "2.Employee";
        }
        return "user not found". PHP_EOL;
        

    }
    public function register($input, $phonenumber) {
        $message = $input["message"];
        $user = User::where('phonenumber',$phonenumber)->first();
        if($user)
        {
            $user->city = $message;
            $user->save();
            return "Thank you for registering";
          
        }
        return "user not found". PHP_EOL;
        
    }



    function getErrorMessage()
    {
        return "We do not understand your response";
    }

    protected function getInput($text)
    {
        $input = [];

        if (empty($text)) {
            $input['level'] = 0;
            $input['message'] = "";
        } else {
            $exploded_text = explode('*', $text);

            $input['exploded_text'] = $exploded_text;
            $input['level'] = count($exploded_text);
            $input['message'] = end($exploded_text);
        }

        return $input;
    }


    protected function sendResponse($response, $type)
    {

        switch ($type) {
            case 1:
                $output = "CON ";
                break;
            case 2:
                $output = "END ";
                break;

        }


        $output .= $response;
        header('Content-type: text/plain');
        echo $output;
        exit;

    }
}
