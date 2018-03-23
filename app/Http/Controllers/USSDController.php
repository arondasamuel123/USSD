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
        
        $input = $this->getInput($text);

        switch ($input['level']) {
            case 0:
            $response = $this->getMainMenu($input);
            break;

            case 1:
            $response = $this->getCityInput($input);
            break;

            case 2:
            $response = $this->getPhonenumber($input);
            break;


            case 3:
            $response = $this->getAccountType($input);
            break;

           
            case 4:
            $response = $this->register();
            break;

            default:
            $response = $this->getErrorMessage();
            break;

        }


        $this->sendResponse($response, 1);
    }


    public function getMainMenu($input)
    {
        
        return "Please enter your name". PHP_EOL; //"1.Plumber".PHP_EOL. "2.Electrician" .PHP_EOL. "Mama Wa Nguo";
    }
    public function getCityInput($input) {
       
            return "Please enter your city". PHP_EOL;
        
       
    }

    public function getPhonenumber($input) {
       
            return "Please enter your phonenumber". PHP_EOL;
        
       
    }

   
    public function getAccountType($input) {
      
          return "Please choose your account type".PHP_EOL. "1.Employer".PHP_EOL. "2.Employee";
        
        
        

    }

    

    public function register() {

       $user = User::create(['name', 'city', 'phonenumber','accounttype']);

        return "Thank you for registering";

        $this->sendResponse($response, 2);
        
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
