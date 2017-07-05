<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller {
    
    private $client;
    
    public function __construct() {
        $this->client = \Laravel\Passport\Client::find(1);
    }

    public function register(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
           'name' => request('name'), 
           'email' => request('email'), 
           'password' => bcrypt('password'), 
        ]);

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('email'),
            'password' => request('password'),
            'scope' => '*',
        ];

        $request->request->add($params);

        $proxy = \Illuminate\Support\Facades\Request::create('oauth/token', 'POST');

        return \Illuminate\Support\Facades\Route::dispatch($proxy);

    }

}
//$token =
// {
//    "token_type": "Bearer",
//    "expires_in": 31535998,
//    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjVmN2YwNTRlZDc3YmU2ZTU2Y2M5NzJkODZjODQ5ZmRhNzJiNTQ3Y2U5YzQ5MWViZDhlOTRjODAyM2YzMDJiNTg2MWYwNTU5YjA4NmQzZWQxIn0.eyJhdWQiOiIxIiwianRpIjoiNWY3ZjA1NGVkNzdiZTZlNTZjYzk3MmQ4NmM4NDlmZGE3MmI1NDdjZTljNDkxZWJkOGU5NGM4MDIzZjMwMmI1ODYxZjA1NTliMDg2ZDNlZDEiLCJpYXQiOjE0OTkyODM5NDgsIm5iZiI6MTQ5OTI4Mzk0OCwiZXhwIjoxNTMwODE5OTQ4LCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.W-0KNO8VkjhcHaBS6NylGLDR5TKcigGIQ_X77BfilevkiszfrUAyna7PEgs8u9LFfws6ZZAounjxUD5RsiLkw56V-Aqw8ctzxh4YlEBS64vIW8i36-3m2UbP7-J5UaMFTqdHVqsb1gwVPKiZ7-sRWgXWuk3LnJ1r3Z22lGV8-XrEx0hxd2Mkm2klUqc87N_fFfz7yx-vuytLhCZwTtN7mK-vLtPwEKGA8cqzDV_rbCtMx3nDSup49aqp8a1NuUMFETwJXuGplSCb-48XZCTtdLiKaUrd50Falxzo6AHZnjRIistPdk1ZnHpe0nV0z9oXIFM484RB5W6288V0BTcHXOVB3dM1e3KgDCOgbpg6tx3CQKQNAplIvI1S1p8c6Dn-oZU8kwJC5Pmv1zjJOyUi4vIDNiLr9u644eGxynaJ93gPa7IcB8zGvzPZ4vaxjqVmXZpDzZ9TFdEUsRIgQFAH-iBcddyc2QDM8gKGZZQF8TjfGB-SsmhbXB_wwFFyKtT0EpDuiFQo2Z6ySZaAgaY7oS8ZgxY17BChqd6_sKdptOyWfRFy1cSTwVTfCNsa64HFVefcPtYzDW2E5swiMN4DQJiguq-3_JQa0crejAhN3ohdz2Q1Ir_6BJ2zvO1xD7mGW_3u5SUhl3_Xl904UTad9GYjm47RD7uoFfvyZx-OM78",
//    "refresh_token": "def5020043c3307c35d87d258572d1c6f9c6d96e46336a12057d40a6280ab5b9fa80026afb7a2202e093775499395a6a5a72cee9dadff587a3db678c7b5a434c098675e7248b66c61fc824e1c35f937b32ff69244b47c9346457bf7fba793cdd9261829082266a751075b43d2ef5a20b95f20c9c18399bd12fb09f37e81649d45cf2bf2ee0bba8bb71433905beb43e65548ab8a26ba19e77a2c4e411f3afeb72b4d99551aeb52079ae08e1e3ed4adffd6f5a08ef0aebabe910dfdd57504360bd0396aa52f95e47d8b06321bc82ea1d75648f265d17a2c813c3ed8155b490545229aecc154cb3dd064d85f9bbcd976b542c18853310fc7b8dff811457e85df40c7d4518326bfaf7ddbb80fe2a385e1f00d5c4a922d07582556dffde978b73dcf46d7acca766b7bf7bae9ec4a711b4aa9fa3e94fc79575f5e49dbcda59e79b8084b5084b8631d1ef840b2d450e554b8e537dd2bd5f1ce4aa214ebb676e94eb805a40c4"
//}