<?php

namespace App\Controllers\UserDashboard\Profile;

use App\Models\UserKycModel;
use App\Models\UserModel;
use App\Controllers\ParentController;
use CodeIgniter\HTTP\ResponseInterface;

class Kyc extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    private UserKycModel $userKycModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->userKycModel = new UserKycModel;
    }


    public function handlePost()
    {
        // Set validation rules for both fields
        $validationRules = [
            'aadhar' => 'uploaded[aadhar]|max_size[aadhar,2048]|mime_in[aadhar,image/png,image/jpg,image/jpeg,application/pdf]',
            'aadhar_back' => 'uploaded[aadhar_back]|max_size[aadhar_back,2048]|mime_in[aadhar_back,image/png,image/jpg,image/jpeg,application/pdf]',
            'pan' => 'uploaded[pan]|max_size[pan,2048]|mime_in[pan,image/png,image/jpg,image/jpeg,application/pdf]'
        ];

        if (!$this->validate($validationRules)) {
            // Return validation errors
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }


        $aadharFile = $this->request->getFile('aadhar');
        $aadharBackFile = $this->request->getFile('aadhar_back');
        $panFile = $this->request->getFile('pan');


        if (
            $aadharFile->isValid() && !$aadharFile->hasMoved() &&
            $aadharBackFile->isValid() && !$aadharBackFile->hasMoved() &&
            $panFile->isValid() && !$panFile->hasMoved()
        ) {
            $aadharName = 'aadhar_' . time() . '_' . $aadharFile->getRandomName();
            $aadharBackName = 'aadhar_back_' . time() . '_' . $aadharBackFile->getRandomName();
            $panName = 'pan_' . time() . '_' . $panFile->getRandomName();

            // Move files to public/uploads
            $aadharFile->move(FCPATH . 'uploads/kyc/', $aadharName);
            $aadharBackFile->move(FCPATH . 'uploads/kyc/', $aadharBackName);
            $panFile->move(FCPATH . 'uploads/kyc/', $panName);

            $update = [
                'user_id' => user('id'),
                'aadhar' => $aadharName,
                'aadhar_back' => $aadharBackName,
                'pan' => $panName,
                'status' => 'pending'
            ];

            $kyc = $this->userKycModel->select('id')->where('user_id', user('id'))->first();

            if ($kyc?->id) {
                $update['status_updated_at'] = $this->userKycModel->dbDate();
                $this->userKycModel->update($kyc->id, $update);
            } else {
                $this->userKycModel->insert($update);
            }

            return redirect()->back()->with('success', 'KYC Submitted!');
        }

    }

    public function index()
    {

        if ($this->request->is('post')) {
            return $this->handlePost();
        }

        $title = 'KYC';
        $this->vd = $this->pageData($title, $title, $title);

        $kyc = $this->userKycModel->where('user_id', user('id'))->first();

        $this->vd['kyc'] = $kyc;

        return view('user_dashboard/profile/kyc', $this->vd);
    }
}
