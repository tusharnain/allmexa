<?php

namespace App\Controllers\UserDashboard\P2PTransfer;


use App\Controllers\ParentController;
use App\Libraries\MyLib;
use App\Models\WalletTransactionModel;
use Config\Services;


class P2PTransferLogs extends ParentController
{
    private array $vd = [];
    private WalletTransactionModel $walletTransactionModel;

    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];
    public function __construct()
    {
        $this->walletTransactionModel = new WalletTransactionModel;
    }


    private function setupTable()
    {
        // ! Performing a little bit of manual pagination, because of using builder class not model.

        $t = 'p2p_transfers';
        $user_id_pk = user('id');

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen >= 1 and $plen <= 100) ? $plen : $this->pageLength;


        $builder = $this->walletTransactionModel->p2pTransfersTable()
            ->select("$t.*")
            ->select([
                // Receiver Details
                'receiver.user_id as receiverUserId',
                'receiver.full_name as receiverUserFullName',

                // Sender Transaction Details
                'wallet_transactions.track_id as senderTransactionTrackId',
            ])
            // receiver user
            ->join('users as receiver', "$t.receiver_user_id = receiver.id")
            // sender transaction details
            ->join('wallet_transactions', "$t.sender_transaction_id = wallet_transactions.id")
            ->where("$t.sender_user_id", $user_id_pk)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');



        // track id search
        $search = inputGet('search');
        if ($search) {
            $builder->groupStart()
                ->orLike("receiver.user_id", $search)
                ->orLike("receiver.full_name", $search)
                ->orLike("wallet_transactions.track_id", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }

        $pagination = MyLib::builderPagination(builder: $builder, per_page: $this->pageLength, pager_tempalte: 'user_dashboard');

        $this->vd['p2ps'] = $pagination->data;
        $this->vd['pager'] = $pagination->pager;
    }



    public function index()
    {

        $title = 'P2P Transfer Logs';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();

        $user_id = user('id');

        $totalAmountTransferred = $this->walletTransactionModel->getUserTotalP2pAmountTransferredFromUserIdPk($user_id);

        $this->vd['totalAmountTransferred'] = &$totalAmountTransferred;

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/p2p_transfer/p2p_transfer_logs', $this->vd); // return string
    }
}
