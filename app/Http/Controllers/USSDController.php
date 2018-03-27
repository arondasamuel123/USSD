<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class USSDController extends Controller
{
    protected $user_current_level;

    public function index(Request $request)
    {
        $sessionId = $request->get('sessionId');
        $serviceCode = $request->get('serviceCode');
        $phoneNumber = $request->get('phoneNumber');
        $text = $request->get('text');
        
        $input = $this->getInput($text);

        switch ($input['level']) {
            case 0:
            $response = $this->getMainMenu($phoneNumber);
            break;

            case 1:
            $response = $this->getCityInput($input,$phoneNumber);
            break;


            case 2:
            $response = $this->getAccountType($input,$phoneNumber);
            break;

            case 3:
            $response = $this->getData($input,$phoneNumber);
            break;


            default:
            $response = $this->getErrorMessage();
            break;

        }


        $this->sendResponse($response, 1);
    }


    public function getMainMenu($phoneNumber)
    {
        $user= User::where('phonenumber',$phoneNumber)->first();

        if(!$user){
        $user = new User;
        $user->phonenumber= $phoneNumber;
        $user->save();
        
        return "Please enter your name". PHP_EOL; 
    }
        else {
            return "Welcome".$user->name.;
        }
    }
    public function getCityInput($input,$phoneNumber) {


        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();

        if($user){
      
        $user->name= $message;
        $user->save();
       
            return "Please enter your city". PHP_EOL;
        }else {
            return "user needs to be created";
        }
       
    }

   
    public function getAccountType($input,$phoneNumber) {
        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user){
      
        $user->city= $message;
        $user->save();
       
        return "Please choose your account type".PHP_EOL. "1.Employer".PHP_EOL. "2.Employee";
        
          
        }else {

            return "user needs to be created";
        }
}
    public function getData($input,$phoneNumber) {
        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user){
      
        $user->accounttype= $message;
        $user->save();
       
        return  "Thank you for registering";

        $this->sendResponse($response, 2);
          
        }

        

    }       




   public function getErrorMessage()
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
