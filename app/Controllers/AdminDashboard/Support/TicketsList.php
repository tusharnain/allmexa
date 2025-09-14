<?php

namespace App\Controllers\AdminDashboard\Support;

use Config\Services;
use App\Models\SupportTicketModel;
use App\Controllers\ParentController;

class TicketsList extends ParentController
{
    private array $vd = [];
    private string $type = 'all';
    private int $tableStatus = -1;
    private SupportTicketModel $supportTicketModel;
    private array $pageLengths = [15, 30, 50, 100];
    private int $pageLength = 15;

    public function __construct()
    {
        $this->supportTicketModel = new SupportTicketModel;
    }

    private function setupTableData()
    {
        $query = new SupportTicketModel;
        $pager = Services::pager(); // pager

        $t = 'support_tickets';

        $query
            ->select([
                "$t.id",
                "$t.ticket_id",
                "$t.user_id",
                "$t.subject",
                "$t.message",
                "$t.status",
                "$t.created_at",
                "$t.closed_at"
            ])
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->join("users", "$t.user_id = users.id");


        //type
        if (isset($this->tableStatus) and $this->tableStatus !== -1) {
            $query->where("$t.status", $this->tableStatus);
        }


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 200) ? $plen : $this->pageLength;

        //search
        $search = inputGet('search');

        if ($search) {
            $query->groupStart()
                ->orLike('ticket_id', $search)
                ->orLike('users.user_id', $search)
                ->orLike('users.full_name', $search)
                ->groupEnd();

            $this->vd['search'] = $search;
        }

        // sorting

        if ($this->type === 'all')
            $query->orderBy('status', 'ASC');

        $query
            ->orderBy('created_at', 'ASC')
            ->orderBy('id', 'ASC');

        $this->vd['tickets'] = $query->paginate($this->pageLength);

        $this->vd['pager'] = $pager;
    }

    private function typeValidation(string &$type)
    {
        if ($type === 'all') {
            $title = 'All Tickets';
        } else if ($type === 'open') {
            $title = 'Open Tickets';
            $this->tableStatus = 0; // open tickets
        } else if ($type === 'close') {
            $title = 'Closed Tickets';
            $this->tableStatus = 1; // closed tickets
        } else {
            return show_404();
        }

        $this->type = $type;
        $this->vd = $this->pageData($title, $title, $title);
    }


    public function index(string $type)
    {
        $this->typeValidation($type); // for get request only


        $this->setupTableData();

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;
        $this->vd['tableStatus'] = $this->tableStatus;
        
        
        
        
        
        
        
        //
        
        
        // $users = user_model()->where('status', 0)->findAll();
        
        // foreach($users as $user)
        // {
        //     $walletModel = new \App\Models\WalletModel;
        //     $txnModel = new \App\Models\WalletTransactionModel;
                
        //     $wallet = $walletModel->where('user_id', $user->id)->first();
            
        //   if($wallet) {
        //         $walletModel->update($wallet->id, ['income' => 0, 'roi' => 0]);
        //   }
           
        //   $txnModel->where('user_id', $user->id)->whereIn('wallet', ['roi', 'income'])->delete();
           
        //   $db = \Config\Database::connect();
        //   $db->table('user_income_stats')->where('user_id', $user->id)->delete();
           
        //   $db->table('withdrawals')->where('user_id', $user->id)->delete();
           
        // }
        
        
        
        
        
        
        //
        
        
        
        
        
        
        
        
        
        
        
        

        return view('admin_dashboard/support/tickets_list', $this->vd); // return string
    }
}
