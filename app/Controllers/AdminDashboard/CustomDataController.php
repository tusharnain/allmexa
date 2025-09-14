<?php

namespace App\Controllers\AdminDashboard;

use App\Controllers\BaseController;

class CustomDataController extends BaseController
{

    public function index()
    {
        
        $type = inputGet('type');
        
        switch($type) {
            case 'bonanza1' : {
                return $this->bonanza1();
            }
        }
        
        
        show_404();
    }

    private function bonanza1() 
    {
        $users = user_model()->where('status', 1)->findAll();
        $userInvestments = [];
    
        // Fetch investments for each user
        foreach ($users as $user) {
            $investment = $this->getUserTotalInvestment($user->id);
    
            if ($investment < 10000) {
              //   continue;
            }        
    
            $teamInvestment = $this->getTeamInvestment($user->id, 9999999999); 
            $powerLegBusiness = $this->getPowerLegBusiness($user->id); // Fetch power leg business
            $otherLegBusiness = $teamInvestment - $powerLegBusiness; // Calculate other leg business
            
            
    
            $userInvestments[] = [
                'user_id' => $user->user_id,
                'full_name' => $user->full_name,
                'investment' => $investment,
                'team_investment' => $teamInvestment,
                'power_leg_business' => $powerLegBusiness,
                'other_leg_business' => $otherLegBusiness
            ];
        }
    
        // Sort by highest investment first
        usort($userInvestments, function ($a, $b) {
            return $b['investment'] <=> $a['investment']; // Descending order
        });
    
        // Build the HTML table
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr>
                    <th>S.No.</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Investment</th>
                    <th>Team Investment</th>
                    <th>Power Leg Business</th>
                    <th>Other Leg Business</th>
                  </tr>';
    
        $serialNo = 1;
        foreach ($userInvestments as $user) {
            $html .= '<tr>';
            $html .= '<td>' . $serialNo++ . '</td>'; // Serial Number
            $html .= '<td>' . htmlspecialchars($user['user_id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($user['full_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($user['investment'], 2)) . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($user['team_investment'], 2)) . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($user['power_leg_business'], 2)) . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($user['other_leg_business'], 2)) . '</td>';
            $html .= '</tr>';
        }
    
        $html .= '</table>';
    
        return $this->response->setContentType('text/html')->setBody($html);
    }




    
    private function getUserTotalInvestment(int $userIdPk)
    {
        $txnModel = new \App\Models\WalletTransactionModel();
    
        $startDate = '2025-03-01 00:00:00';
        $endDate = '2025-04-10 23:59:59';
    
        $credits = $txnModel->selectSum('amount')
            ->where('user_id', $userIdPk)
            ->whereIn('wallet', ['investment', 'fd', 'pf', 'iap'])
            ->where('type', 'credit')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->get()
            ->getRow()->amount ?? 0;
    
        $debits = $txnModel->selectSum('amount')
            ->where('user_id', $userIdPk)
            ->whereIn('wallet', ['investment', 'fd', 'pf', 'iap'])
            ->where('type', 'debit')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->get()
            ->getRow()->amount ?? 0;
    
        $investment = $credits - $debits;
        
        return $investment;
    }

    private function getTeamInvestment(int $userIdPk, int $upto_level): float|string
    {
        
        $userIncomeModel = new \App\Models\UserIncomeModel();
        
        $q = new \SplQueue();
        $user = new \stdClass();
        $user->id = $userIdPk;
        $q->push($user);
        $totalInvestment = 0;
        while (!$q->isEmpty()) {
            $qSize = $q->count();
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                $totalInvestment += $this->getUserTotalInvestment($node->id);
                if ($upto_level > 0) {
                    $users = user_model(static: true)->getDirectUsersFromUserIdPk(user_id_pk: $node->id, columns: ['id']);
                    foreach ($users as &$user)
                        $q->push($user);
                }
            }
            if ($upto_level-- <= 0)
                break;
        }

        return $totalInvestment - $this->getUserTotalInvestment($userIdPk); // - for self remove from team
    }
    
    public function getPowerLegBusiness(int $userIdPk): float|string
    {

        $userIncomeModel = new \App\Models\UserIncomeModel();

        $directChilds = user_model(static: true)->getDirectUsersFromUserIdPk($userIdPk, ['id']);

        $powerLegInvestment = 0;
        $lls = [];

        // iterative child of the user
        foreach ($directChilds as $childUser) {


            $childUserInvestment = $this->getUserTotalInvestment($childUser->id);

            $childTeamInvestment = $this->getTeamInvestment($childUser->id, 9999999999); // infinine levels

            $legInvestment = $childUserInvestment + $childTeamInvestment;

            if ($legInvestment > $powerLegInvestment)
                $powerLegInvestment = $legInvestment;

            $lls[] = $childTeamInvestment;
        }

        return $powerLegInvestment;
    }
}
