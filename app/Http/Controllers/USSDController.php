<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class USSDController extends Controller
{
    protected $user_current_level;

    public function index(Request $request)
    {
        $text = $request->get('text');

        $input = $this->getInput($text);

        switch ($input['level']) {
            case 0:
                $response = $this->getMainMenu();
                break;
            
            

            default:
                $response = $this->getMainMenu();
                break;

        }


        $this->sendResponse($response, 1);
    }


    public function getMainMenu()
    {
        return "Please choose option".PHP_EOL. "1.Plumber".PHP_EOL. "2.Electrician";
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
