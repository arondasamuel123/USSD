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

        switch ($input['level']) {//seven levels each doing deal with a menu or a number of menus 
            case 0:
            $response = $this->getMainMenu($phoneNumber);
            break;

            case 1:

            $response = $this->getCityInput($input,$phoneNumber);
            break;

             case 2:
            $response = $this->getNationalID($input,$phoneNumber);
            break;


            case 3:
            $response = $this->getAccountType($input,$phoneNumber);
            break;

            
            case 4:
             $response = $this->levelOneProcess($input,$phoneNumber);
            break;

             case 5:
              $response = $this->processInput($input,$phoneNumber);
            break;

             case 6:
            $this->user_current_level = 6;
             $response = $this->selectInput($input);
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
        
        return "Please enter your full names". PHP_EOL; 

    }
    elseif($user->accounttype=1) {

            return "Welcome"."  ".$user->name. PHP_EOL."Please choose a service".PHP_EOL."1.Mama wa Nguo".PHP_EOL."2.Electrician".PHP_EOL."3.Gas Delivery".PHP_EOL."4.Clean Water Delivery".PHP_EOL;
        }

    elseif ($user->accounttype=2 || $user->accounttype=3) {
        $response = "Your number is registered as an employee or supplier.You will recieve jobs once you are activated";
        
        $this->sendResponse($response, 2);
        
    }

        
    }
    public function getCityInput($input,$phoneNumber) {


        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();

        if($user){
      
        $user->name= $message;
        $user->save();
       
            return "Please enter your area of residence/operation-Employees".PHP_EOL;
        }else {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
            return "user needs to be created";
        }

    }

    public function getNationalID($input,$phoneNumber) {


        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();

        if($user){
      
        $user->city= $message;
        $user->save();
       
            return "Please enter your national ID".PHP_EOL;
        }else {
            return "user needs to be created";
        }
       
    }



   
    public function getAccountType($input,$phoneNumber) {
        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user){
      
        $user->nationalid= $message;
        $user->save();
       
        return "Please choose your account type".PHP_EOL. "1.Employer".PHP_EOL. "2.Employee".PHP_EOL."3.Supplier";
        
          
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

        $response = "Thank you for registering";
       
       }
        $this->sendResponse($response, 2);  

    }
    public function getEmployeeMenu($input,$phoneNumber) {
        $message = $input["message"];

         $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user){
      
        $user->accounttype= $message;
        $user->save();
        return "Please choose your job type".PHP_EOL. "1.Mama wa Nguo".PHP_EOL. "2.Electrician".PHP_EOL;
       
       }
   }

    public function getSupplierMenu($input,$phoneNumber) {
        $message = $input["message"];

         $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user){
      
        $user->accounttype= $message;
        $user->save();
        return "Please choose your supply type".PHP_EOL. "1.Clean water delivery ".PHP_EOL. "2.Gas".PHP_EOL;
       
       }
   }

       public function getJobType($input,$phoneNumber) {
        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user->accounttype=2){
      
        $user->jobtype= $message;
        $user->save();

        $response = "Thank you for registering.Go for vetting at our offices then you will be activated";
       
       }
        $this->sendResponse($response, 2);

       }


       public function getSupplyType($input,$phoneNumber) {
        $message = $input["message"];

        $user= User::where('phonenumber',$phoneNumber)->first();
        
        if($user->accounttype=3){
      
        $user->supplytype= $message;
        $user->save();

        $response = "Thank you for registering.Your vetting process will be done by customer ratings";
       
       }
        $this->sendResponse($response, 2);

       }

       public function selectEmployee($input){
        $message = $input["message"];

        $employee = User::where('jobtype',1)->first();
        $response = $employee->name." ". $employee->phonenumber.PHP_EOL;

        $this->sendResponse($response, 2);

       }
       


        protected function levelOneProcess($input,$phoneNumber)//Display thank you message for different account types
    {
        switch ($input['message']) {
            case 1:
                $response = $this->getData($input,$phoneNumber);
                break;
            case 2:
                $response = $this->getEmployeeMenu($input,$phoneNumber);
                break;
            case 3:
                $response = $this->getSupplierMenu($input,$phoneNumber);
                break;
           
            default:
                $response = $this->getErrorMessage();
                break;
        }
        return $response;
    }

    protected function processInput($input,$phoneNumber) {
            switch ($input['message']) {
                case 1:
                $response = $this->getJobType($input,$phoneNumber);
                break;

                case 2:
                $response = $this->getSupplyType($input,$phoneNumber);
                break;

                default:
                $response = $this->getErrorMessage();
                break;
            }
            return $response;

    }

    protected function selectInput($input) {
        switch ($input['message']) {
                case 1:
                $response = $this->selectEmployee($input);
                break;

                default:
                $response = $this->getErrorMessage();
                break;
            }
            return $response;
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
