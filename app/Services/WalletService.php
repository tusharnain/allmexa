<?php

namespace App\Services;

use App\Enums\WalletTransactionCategory as TxnCat;
use App\Models\RoiModel;
use App\Models\TopupModel;
use App\Models\UserIncomeModel;
use App\Models\WalletTransactionModel;
use App\Models\WithdrawalModel;
use App\Twebsol\Plans;

//! if you want to add more wallets, don't change anything in the migration, instead add new wallet in this Service file, and then again run migration
//! Also don't forget to add that new wallet in labels file, and in wallet model

class WalletService
{
    const WALLETS = [ // do not change the indexing, as its being used in validation service with indeces
        1 => 'fund',
        2 => 'income',
        3 => 'investment',
        4 => 'compound_investment',
    ];

    const WALLET_SLUGS = [
        1 => 'fund-wallet',
        2 => 'e-wallet',
        3 => 'investment-wallet',
        4 => 'compound-investment-wallet',
    ];

    const WITHDRAW_FROM_WALLET = 'income';

    private static string $commaSeperatedWalletsString;
    private static string $commaSeperatedIndecesString;
    private static array $keyWalletValueSlugArray;

    private static array $cache = [];

    //models
    private static UserIncomeModel $userIncomeModel;
    private static TopupModel $topupModel;
    private static WalletTransactionModel $walletTransactionModel;
    private static WithdrawalModel $withdrawalModel;
    private static RoiModel $roiModel;

    private static function getUserIncomeModel(): UserIncomeModel
    {
        return self::$userIncomeModel ??= new UserIncomeModel;
    }
    private static function getTopupModel(): TopupModel
    {
        return self::$topupModel ??= new TopupModel;
    }
    private static function getWalletTransactionModel(): WalletTransactionModel
    {
        return self::$walletTransactionModel ??= new WalletTransactionModel;
    }

    private static function getWithdrawalModel(): WithdrawalModel
    {
        return self::$withdrawalModel ??= new WithdrawalModel;
    }
    private static function getRoiModel(): RoiModel
    {
        return self::$roiModel ??= new RoiModel;
    }

    public static function getWalletFieldFromIndex(int $index): string|null
    {
        return self::WALLETS[$index] ?? null;
    }

    public static function getWalletSlug(string $wallet): string|null
    {
        $incomeWalletIndex = array_search($wallet, WalletService::WALLETS);
        return WalletService::WALLET_SLUGS[$incomeWalletIndex] ?? null;
    }

    public static function getCommaSEperatedIndeces(): string
    {
        if (isset(self::$commaSeperatedIndecesString))
            return self::$commaSeperatedIndecesString;

        return self::$commaSeperatedIndecesString = implode(',', self::WALLETS);
    }

    public static function getCommaSeperatedWalletsString(): string
    {
        if (isset(self::$commaSeperatedWalletsString))
            return self::$commaSeperatedWalletsString;

        return self::$commaSeperatedWalletsString = implode(',', self::WALLETS);
    }

    public static function getKeyWalletValueSlugArray(): array
    {
        if (isset(self::$keyWalletValueSlugArray))
            return self::$keyWalletValueSlugArray;

        return self::$keyWalletValueSlugArray = array_combine(self::WALLETS, self::WALLET_SLUGS);
    }

    // search by slug
    public static function searchBySlug(string $slug): string|null
    {
        $index = array_search($slug, self::WALLET_SLUGS);

        return $index ? self::WALLETS[$index] : null;
    }

    //get user wallet balance array with wallet label
    public static function getWalletBalanceWithWalletLabel(object $walletObject, $fAmount = false, $walletSlug = false): array
    {
        // $walletObject must have only wallet fields, all the wallet fields, no extra fields like, id, user_id, etc
        $array = [];

        $i = 0;
        foreach (self::WALLETS as $key => &$wallet) {

            $amount = $walletObject->{$wallet};

            $array[$i] = [
                'wallet' => $wallet,
                'label' => wallet_label($wallet),
                'amount' => $amount
            ];

            if ($fAmount)
                $array[$i]['fAmount'] = wallet_famount(_c($amount), wallet: $wallet);

            if ($walletSlug)
                $array[$i]['slug'] = self::WALLET_SLUGS[$key];

            $i++;
        }

        return $array;
    }



    // wallet transaction details json parser
    public static function parseTransationDetailsJSON(object &$txn, string $color = 'primary'): string
    {
        $details = json_decode($txn->details);

        $res = '';

        $title = isset($details->title) ? $details->title : strtoupper(str_replace('_', ' ', $txn->category));

        //title
        $res .= "<h6 class=\"text-$color mb-2 fw-bold\"><i class=\"fa fa-circle me-2\"></i>{$title}</h6>";

        if (!$details)
            return $res;

        // details by cateogry
        $res .= self::parseTxnDetailsByCategory($txn->type, $txn->category, $color, $details);


        return $res;
    }


    // Transaction Details Parser By Categories
    private static function parseTxnDetailsByCategory(string $txnType, string $category, string $color, object &$details): string
    {
        $user_id_pk = user('id');

        $userLabel = label('user');
        $userIdLabel = label('user_id');
        $userNameLabel = label('user_name');
        $planLabel = label('plan');

        $str = '';
        switch ($category) {

            //* Admin
            case TxnCat::ADMIN: {
                if (isset($details->remarks)) {
                    $_remarks = escape($details->remarks);
                    $str .= "<p class=\"bg-light-$color text-ld border border-$color fit-content ps-0 p-1 mt-1\"><span class=\"fw-bold\">Remarks</span> : {$_remarks}</p>";
                }
                break;
            }

            //* SPONSOR LEVEL INCOME
            case TxnCat::SPONSOR_LEVEL_INCOME: {
                // mandatory fields -> sl_id_pk
                if (isset($details->sli_id_pk)) {

                    $sli = self::getUserIncomeModel()->getSponsorLevelIncomeRecord($details->sli_id_pk, ['id', 'level_user_id', 'level']);

                    $user = get_user($sli->level_user_id, ['user_id', 'full_name']);
                    $showName = $user ? "{$user->user_id} ({$user->full_name})" : '<span class="text-secondary">Deleted Member</span>';
                    $str .= "<p  data-tooltip-track-id=\"{$sli->id}\" data-tooltip-action=\"track_sli\" role=\"button\" class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base\">
                            Level :<span class=\"fw-bold\"> {$sli->level}</span><br/>
                            $userLabel :<span class=\"fw-bold\">{$showName}</span><br/>
                        </p>";
                }
                break;
            }

            //* ROI Income
            case TxnCat::ROI: {
                // mandatory fields -> percent, bv
                if (isset($details->percent) && isset($details->bv)) {

                    $fBv = f_amount($details->bv);

                    $_str = "<span class=\"fw-bold\">{$details->percent}%</span> of";
                    $_str .= "<span class=\"fw-bold\"> {$fBv}</span><br/>";

                    $str .= "<p class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base\">
                            $_str
                        </p>";
                }
                break;
            }

            //* ROI Income
            case TxnCat::SPONSOR_ROI_LEVEL_INCOME: {
                // mandatory fields -> srli_id_pk
                if (isset($details->srli_id_pk)) {

                    $srli = self::getUserIncomeModel()->getSponsorRoiLevelIncomeRecord($details->srli_id_pk, columns: ['id', 'level', 'level_user_id']);


                    $user = get_user($srli->level_user_id, ['user_id', 'full_name']);


                    $str .= "<p role=\"button\" data-tooltip-track-id=\"{$srli->id}\" data-tooltip-action=\"track_srli\" class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base\">
                            Level : <span class=\"fw-bold\"> {$srli->level}</span><br/>
                            $userLabel : <span class=\"fw-bold\"> {$user->user_id} ({$user->full_name})</span><br/>
                        </p>";
                }
                break;
            }

            //* TOPUP
            case TxnCat::TOPUP: {
                // mandatory fields -> topup_id

                if (isset($details->topup_id)) {

                    $topup = self::getTopupModel()->getTopupFromTopupIdPk($details->topup_id, ['id', 'track_id', 'user_id']);
                    $user = get_user($topup->user_id, ['user_id', 'full_name']);

                    $userFullName = escape($user->full_name);
                    $topupHistoryUrl = route('user.topup.logs') . "?search={$topup->track_id}";

                    $str .= "<p data-tooltip-track-id=\"{$topup->id}\" data-tooltip-action=\"track_topup\" role=\"button\" class=\"bg-light-$color text-ld fit-content border border-$color px-3 p-1 mt-0 lh-base \">
                                $userIdLabel : <span class=\"fw-bold\">{$user->user_id} ({$userFullName})</span><br/>
                                Topup Track Id : <a href=\"$topupHistoryUrl\"><span class=\"fw-bold text-decoration-underline\">{$topup->track_id}</span></a><br/>
                            </p>";
                }

                break;
            }

            //* P2P Transfer
            case TxnCat::P2P_TRANSFER: {
                // mandatory fields -> p2p_id

                if (isset($details->p2p_id)) {

                    $p2p = self::getWalletTransactionModel()->getP2PTransferRecordFromPkId($details->p2p_id);

                    $_str = '';
                    if ($txnType === 'debit') {
                        $user = get_user($p2p->receiver_user_id, ['user_id', 'full_name']);
                        $_str = "Sent to : <span class=\"fw-bold\">{$user->user_id} ({$user->full_name})</span><br/>";
                    } else {
                        $user = get_user($p2p->sender_user_id, ['user_id', 'full_name']);
                        $_str = "Received from : <span class=\"fw-bold\">{$user->user_id} ({$user->full_name})</span><br/>";
                    }

                    $_remarkLable = ($txnType === 'debit' ? "" : "Sender's") . ' Remarks';

                    $remarks = $p2p->sender_remarks ? " <span class=\"fw-bold\">$_remarkLable</span> :{$p2p->sender_remarks}<br/>" : '';

                    $str .= "<p  class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base \">
                        {$_str}
                        {$remarks}
                        </p>";
                }

                break;
            }


            //* Withdrawal
            case TxnCat::WITHDRAWAL: {
                // mandatory fields -> wd_id

                if (isset($details->wd_id)) {

                    $wd_id = $details->wd_id;

                    $wd = self::getWithdrawalModel()->getWithdrawalFromWithdrawalIdPk($wd_id, ['track_id', 'remarks']);

                    $_str = "Withdrawal Track Id : <span class=\"fw-bold\">{$wd->track_id}</span><br/>";
                    $remarks = $wd->remarks ? "<span class=\"fw-bold\">Remarks</span> : {$wd->remarks}<br/>" : '';
                    $str .= "<p data-tooltip-track-id=\"{$wd_id}\" data-tooltip-action=\"track_wd\" role=\"button\" class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base \">
                    {$_str}
                    {$remarks}
                    </p>";
                }

                break;
            }


            //* Wallet Transfer

            case TxnCat::WALLET_TRANSFER: {

                // mandatory fields -> from/to, from_txn_id/to_txn_id
                if (isset($details)) {
                    $ttype = ($txnType === 'debit') ? 'to' : 'from';
                    $walletStrPrefix = ($txnType === 'debit') ? 'Transferred to' : 'Received from';

                    if (isset($details->$ttype) and isset($details->{"{$ttype}_txn_id"})) {
                        $walletName = wallet_label($details->$ttype) ?? $details->$ttype;
                        $txn = self::getWalletTransactionModel()->select('track_id')->find($details->{"{$ttype}_txn_id"});

                        $incomeWalletUrl = self::$cache['wallet_url_' . $details->$ttype] ??= route('user.wallet.transactions', WalletService::getWalletSlug($details->$ttype));

                        $str .= "<p class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base \">
                                      {$walletStrPrefix} <span class=\"fw-bold\">{$walletName}</span><br/>
                                      {$walletName} Txn Id : <a href=\"{$incomeWalletUrl}?track_id={$txn->track_id}\"><span class=\"fw-bold\">{$txn->track_id}</span></a><br/>
                                    </p>";
                    }
                }

            }


            //* Withdrawal Refund
            case TxnCat::WITHDRAWAL_REFUND: {

                // mandatory fields -> wd_id

                if (isset($details->wd_id)) {

                    $wd_id = $details->wd_id;

                    $wd = self::getWithdrawalModel()->getWithdrawalFromWithdrawalIdPk($wd_id, ['track_id', 'remarks']);

                    $_str = "Withdrawal Track Id : <span class=\"fw-bold\">{$wd->track_id}</span><br/>";
                    $str .= "<p data-tooltip-track-id=\"{$wd_id}\" data-tooltip-action=\"track_wd\" role=\"button\" class=\"bg-light-$color text-ld border border-$color fit-content px-3 p-1 mt-0 lh-base \">
                    {$_str}
                    </p>";
                }

                break;
            }
        }



        return $str;
    }
}
