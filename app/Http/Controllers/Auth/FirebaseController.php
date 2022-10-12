<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Exception;

class FirebaseController extends Controller
{
    protected $auth, $database;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase.json')->withDatabaseUri('https://jaggajameen-3056e-default-rtdb.firebaseio.com');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    //Fetching user list from firebase
    public function viewFirebaseUser(){
        $ref = $this->auth->listUsers();
        $users = iterator_to_array($ref);
        return response()->json([
            'ref' => $users
        ]);
        dump($ref);
    }

    //Delete user from firebase
    public function destroy($cid){
        try{
        $ref = $this->database->getReference('Users/'.$cid)->remove();
        $ref = $this->auth->deleteUser($cid);
        return response()->json([
            'status' => 200,
            'message' => "User deleted successfully!"
        ]);
        }catch(Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }
}
