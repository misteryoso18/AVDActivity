<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function displayGreetings() {
        $name = "rejie";
        $address = "scc";
        $age = "21";
       // return view ('greetings', ['name'=>$name]);
       return view ('greetings', compact ('name','address','age'));
    }
    public function displayProfile(){
        $grade = 85;
        $name = "Mark Rejie J. Rosario";
        $sex = "Male";
        $address = "San Carlos City";
        
        $clients = array(
            array("name"=>"rejie", "sex"=>"male", "address"=>"scc"),
            array("name"=>"jimgirl", "sex"=>"female", "address"=>"scc1"),
            array("name"=>"jimboy", "sex"=>"male", "address"=>"scc2")
        );
        
        return view ('client', compact('grade', 'name', 'sex', 'address', 'clients'));
    }

     public function displayDashboard(){
        return view ('clientDashboard');
    }
     public function displayAboutUs(){
        return view ('clientAboutUs');
    }

public function index() {
    $grade = 74;
    $client = [
        "name" =>"rejie",
        "sex" => "male",
        "address" => "scc"
    ];

    $clients = array(
        array("name"=>"rejie", "sex"=>"male", "address"=>"scc"),
        array("name"=>"jimgirl", "sex"=>"female", "address"=>"scc1"),
        array("name"=>"jimboy", "sex"=>"male", "address"=>"scc2")
    );

    return view("client")-> with ("grade",$grade)->with($clients);
}
}