<?php

namespace App\Database\Seeds;

use App\Models\AdminModel;
use Closure;
use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    private AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel;
    }
    private function createAdminIfNotExists(): bool
    {
        if ($this->adminModel->first())
            return false;

        return $this->adminModel->insert([
            'full_name' => 'Tweb Sol',
            'email' => 'admin@admin.com',
            'phone' => '9898989898',
            'role' => 1,
            'password' => hash_password('aaaaaa')
        ]);
    }



    public function run()
    {
        try {

            transaction(function () {

                $this->createAdminIfNotExists();

            });


        } catch (\Exception $e) {

            throw $e;
        }
    }
}
