<?php

namespace App\Models;



class AdminModel extends ParentModel {
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['full_name', 'email', 'phone', 'password', 'role', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    public function inputLoginValues(): array {
        return [
            'email' => inputPost('email'),
            'password' => request()->getPost('password')
        ];
    }

    private function loginInputAttributes(): array {
        return [
            'email' => 'Email Address',
            'password' => 'Password'
        ];
    }
    public function login(): array|object {
        $data = $this->inputLoginValues();


        $validationErrors = validate($data, [
            'email' => ['required', 'string', 'max_length[250]', 'valid_email'],
            'password' => ['required', 'string', 'no_trailing_spaces'],
        ]);

        if($validationErrors) {

            $loginInputAttribs = $this->loginInputAttributes();

            foreach($validationErrors as &$error)
                $error = str_replace(array_keys($loginInputAttribs), $loginInputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }

        $admin = $this->findAdminByEmail($data['email']);


        if(!($admin and password_verify($data['password'], $admin->password))) {
            return ['error' => 'Invalid Email or Password!'];
        }

        return $admin;
    }


    public function findAdminByEmail(string $email, array $columns = ['*']): null|object {
        return $this->select($columns)->where('email', $email)->get()->getRow();
    }


}