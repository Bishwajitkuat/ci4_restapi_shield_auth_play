<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use Exception;

class AuthController extends ResourceController
{
    private $user;
    public function __construct()
    {
        $this->user = new UserModel();
    }
    // post
    public function register()
    {
        try {
            $rules = [
                'username' => 'required|is_unique[users.username]',
                'email' => 'required|valid_email|is_unique[auth_identities.secret]',
                'password' => 'required',
            ];
            $data = $this->request->getPost(array_keys($rules));
            // validation
            if (!$this->validateData($data, $rules)) {
                throw new Exception('Failed to validate data.');
            }
            $validData = $this->validator->getValidated();
            // creating user entity
            $userEntity = new User($validData);
            // saving into db
            if ($this->user->save($userEntity)) {
                $response = [
                    'status' => true,
                    'message' => 'user is created successfully',
                    'data' => $validData,
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Failed to create the user',
                    'data' => [],
                ];
            }

        } catch (Exception $err) {
            $response = [
                'status' => false,
                'message' => $err->getMessage(),
                'data' => [],
            ];
        }
        return $this->respondCreated($response);
    }

    // post
    public function login()
    {
        try {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required',
            ];
            $data = $this->request->getPost(array_keys($rules));
            // validation
            if (!$this->validateData($data, $rules)) {
                throw new Exception('Failed to validate data.');
            }
            // getting validated data
            $validData = $this->validator->getValidated();
            /**
             */
            // creating login attempt with auth function
            $loginAttempt = auth()->attempt($validData);
            if (true) {
                // fetching user by id, user id will be retrieve from auth->id() method
                $userData = $this->user->findById(auth()->id());
                // creating token against the user
                /**
                 */
                $token = $userData->generateAccessToken('mysecrete');
                // creating auth token
                $auth_token = $token->raw_token;
                $response = [
                    'status' => true,
                    'message' => 'login is success',
                    'data' => ['token' => $auth_token],
                ];

            } else {
                $response = [
                    'status' => false,
                    'message' => 'Invalid username or password.',
                    'data' => [],
                ];
            }
        } catch (Exception $err) {
            $response = [
                'status' => false,
                'message' => $err->getMessage(),
                'data' => [],
            ];
        }
        return $this->respondCreated($response);
    }

    // get
    public function profile()
    {
        // getting user id from auth
        $userId = auth()->id();
        // fetching user data from db
        $userData = $this->user->findById($userId);
        // profile data of the logged in user
        return $this->respondCreated([
            'status' => true,
            'message' => 'Profile data of the logged in user',
            'data' => ['user' => $userData],
        ]);
    }
    // get
    public function logout()
    {
        // using auth() helper function's logout method to logout current user
        auth()->logout();
        // destroying all access token
        auth()->user()->revokeAllAccessTokens();
        return $this->respondCreated([
            'status' => true,
            'message' => 'Logged out',
            'data' => [],
        ]);
    }

    public function accessDenied()
    {
        $response = [
            'status' => false,
            'message' => 'Invalid access',
            'data' => [],
        ];
        return $this->respondCreated($response);
    }

}
