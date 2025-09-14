<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;

class WalletTransactionModel extends ParentModel
{
    protected $table = 'wallet_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['track_id', 'wallet', 'user_id', 'type', 'amount', 'balance', 'details', 'category', 'status', 'created_at', 'updated_at'];

    // constants
    const TRACK_ID_INIT_NUMBER = 1000000;
    const TRACK_ID_PREFIX_WORD = 'TRX';
    // constants

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    //other tables
    private BaseBuilder $p2pTransfersTable;

    private function walletModel(): WalletModel
    {
        return new WalletModel;
    }

    public function p2pTransfersTable(): BaseBuilder
    {
        return $this->p2pTransfersTable ??= $this->db->table('p2p_transfers');
    }







    //setters
    public function updateTransactionDetails(int $txn_pk_id, array $details): bool
    {
        return $this->update($txn_pk_id, ['details' => json_encode($details)]);
    }

    // The save transaction must be called after incrememting/decrementing wallet balance
    public function saveTransaction(
        int $user_id_pk,
        string $amount,
        string $type,
        string $wallet,
        string $category,
        string $status = 'success',
        float $balance = null,
        array $details = null
    ): int {

        // balance not provided, we will calculate here
        if (is_null($balance))
            $balance = $this->walletModel()->getWalletBalanceFromUserIdPk($user_id_pk, $wallet);


        $txn_pk_id = $this->insert([
            'wallet' => $wallet,
            'user_id' => $user_id_pk,
            'type' => $type,
            'amount' => $amount,
            'balance' => $balance,
            'details' => $details ? json_encode($details) : null,
            'category' => $category,
            'status' => $status
        ], returnID: true);

        // now updating with unique track_id
        if ($txn_pk_id) {
            $trackId = self::TRACK_ID_INIT_NUMBER + $txn_pk_id;
            $this->update($txn_pk_id, ['track_id' => self::TRACK_ID_PREFIX_WORD . $trackId]);
        }

        return $txn_pk_id; // returning txn pk id

    }








    /*
     *------------------------------------------------------------------------------------
     * Other Table Actions
     *------------------------------------------------------------------------------------
     */

    public function getUserTotalP2pAmountTransferredFromUserIdPk(int $user_id_pk): string|float
    {
        return $this->p2pTransfersTable()->selectSum('amount')->where('sender_user_id', $user_id_pk)->get()->getRowObject()->amount ?? 0;
    }
    public function getP2PTransferRecordFromPkId(int $p2p_pk_id, string|array $columns = '*'): object|null
    {
        return $this->p2pTransfersTable()->select($columns)->where('id', $p2p_pk_id)->get()->getRowObject();
    }
    // Saving P2P Transfer Record
    public function saveP2PTransfer(string $amount, int $sender_user_id_pk, int $receiver_user_id_pk, string $wallet_field, int $sender_transaction_id_pk, int $receiver_transaction_id_pk, ?string $sender_remarks = null): int
    {
        $this->p2pTransfersTable()->insert([
            'amount' => &$amount,
            'sender_user_id' => &$sender_user_id_pk,
            'receiver_user_id' => &$receiver_user_id_pk,
            'wallet' => &$wallet_field,
            'sender_transaction_id' => &$sender_transaction_id_pk,
            'receiver_transaction_id' => &$receiver_transaction_id_pk,
            'sender_remarks' => &$sender_remarks,
            ...$this->getTimestamps()
        ], escape: true);

        return $this->db->insertID();
    }
}
