<?php

namespace App\Controllers\UserDashboard\Profile;

use App\Controllers\ParentController;
use App\Models\WalletModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\HTTP\Response;

class DownloadController extends ParentController
{
    public function agreement()
    {
        $walletModel = new WalletModel();

        $user = user();
        $ud = user_model()->getUserDetailsFromUserIdPk($user->id);
        $wallet = $walletModel->getWalletFromUserIdPk($user->id, ['investment']);

        if (!$user->status || $wallet->investment <= 0) {
            return redirect()->to(route('user.home'))->with('notif', [
                'title' => 'Investment required!',
                'message' => 'Only investor accounts can download agreement.',
                'type' => 'danger'
            ]);
        }

        // Load the HTML from a view file (app/Views/user_dashboard/pdfs/agreement.php)
        $html = view('user_dashboard/pdfs/agreement', ['user' => $user, 'ud' => $ud, 'wallet' => $wallet]);

        // Initialize Dompdf with options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Get the generated PDF file as a string
        $pdfOutput = $dompdf->output();

        // Set proper headers for PDF download
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="User_Agreement.pdf"')
            ->setHeader('Cache-Control', 'private, must-revalidate, post-check=0, pre-check=0')
            ->setHeader('Pragma', 'public')
            ->setBody($pdfOutput);
    }
}
