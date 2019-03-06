<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/11/18
 * Time: 4:36 PM
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\ExpressCheckout;

/**
 * upload the images to the path passed and create the thumnail if required
 * @param UploadedFile $originalImage
 * @param string $path
 * @param bool $thumbnail_required
 * @param bool $remove_path
 * @return string
 */
function uploadImage(UploadedFile $originalImage, $path = '', $thumbnail_required = false, $remove_path = false)
{

    $time = time();

    $thumbnailImage = Image::make($originalImage);

    $thumbnailPath = "{$path}/thumbnail/";

    $originalPath = $path;

    if ($remove_path) {
        File::deleteDirectory($originalPath);
    }

    if (!file_exists($thumbnailPath)) {
        mkdir($thumbnailPath, 0777, true);
    }

    $thumbnailImage->save($originalPath . $time . $originalImage->getClientOriginalName());

    if ($thumbnail_required) {

        $thumbnailImage->resize(null, 150, function ($constraint) {
            $constraint->aspectRatio();
        });

        $thumbnailImage->save($thumbnailPath . $time . $originalImage->getClientOriginalName());
    }

    $file_name = $time . $originalImage->getClientOriginalName();

    return $file_name;
}

/**
 * returns the path for the users profile pic and if the $return_thumbnail is true than returns the path for the thumbnail image for the user
 * @param null $user_id
 * @param null $profile_pic
 * @param bool $return_thumbnail
 * @return bool|string
 */
function getUserProfileImageSrc($user_id = null, $profile_pic = null, $return_thumbnail = false)
{
    if ($user_id) {
        $path = "user/{$user_id}/profile_pic/";
        if ($return_thumbnail) {
            $path .= 'thumbnail/';
        }

        if ($profile_pic) {
            return asset($path . $profile_pic);
        } else {
            return asset('assets/images/avatars/avatar1.png');
        }

    } else {
        return false;
    }
}

/**
 * returns the ids for the dependent childrens as well
 * since the super_admin can also make the changes to the leases added by his/her sub-users
 * @return array
 */

function getDependentUserIds()
{
    $userIdsWithChildrens = Auth::user()->childrens->pluck('id')->toArray();
    if (auth()->user()->parent_id != 0) {
        $userIdsWithChildrens = array_merge($userIdsWithChildrens, [auth()->user()->parent_id]);
    }
    return array_merge($userIdsWithChildrens, [auth()->user()->id]);
}

/**
 * get the parent user details where required
 * in case the current logged in user is not a parent user than in that case returns the parent user details
 * @return [type] [description]
 */
function getParentDetails()
{
    if (auth()->user()->parent_id == 0) {
        return auth()->user();
    } else {
        return \App\User::query()->where('id', '=', auth()->user()->parent_id)->first();
    }
}

/**
 * updates the credits used by the user while upgrading the account....
 * @param $adjusted_amount
 */
function updateCreditBalanceForParent($adjusted_amount)
{
    $credits = getParentDetails()->credit_balance;
    if($credits >= (-1 * $adjusted_amount['balance'])) {
        $user = getParentDetails();
        $user->credit_balance = $user->credit_balance + $adjusted_amount['balance'];
        $user->save();
    }
}

/**
 * calculate all the payment due dates provided the first payment due date and the last payment due date
 * @param $firt_payment_date Lease Asset First Payment Due Date
 * @param $last_payment_date Lease Asset Last Payment Due Date
 * @param $payment_payout Lease Asset Payment TimeOut
 * @param int $addMonths lease Asset Payment Months interval
 * @return array
 */
function calculatePaymentDueDates($firt_payment_date, $last_payment_date, $payment_payout, $addMonths = 1)
{
    //check if the payments are going to be monthly
    //have to loop from the first payment start date till the last payment end date
    $start_date = $firt_payment_date;
    $end_date = $last_payment_date;
    $final_payout_dates = [];
    $i = 1;
    while (strtotime($start_date) <= strtotime($end_date)) {
        if ($payment_payout == 1) {
            //means that the payment is going to be made at the start of the interval
            $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
            $month = \Carbon\Carbon::parse($start_date)->format('F');
            $current_year = \Carbon\Carbon::parse($start_date)->format('Y');
            $final_payout_dates[$current_year][$month][$start_date] = $start_date;
            $start_date = \Carbon\Carbon::parse($start_date)->addMonth($addMonths)->format('Y-m-d');

            if (strtotime($start_date) <= strtotime($end_date)) {
                $month = \Carbon\Carbon::parse($end_date)->format('F');
                $current_year = \Carbon\Carbon::parse($end_date)->format('Y');
                $date = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
                $final_payout_dates[$current_year][$month][$date] = $date;
            }

        } else if ($payment_payout == 2) {
            //means that the payment is going to be made at the end of the interval
            if ($i == 1) {
                //will not increase by 1 month as the first payment date should be start_date
                $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
            } else {
                $start_date = \Carbon\Carbon::parse($start_date)->addMonth($addMonths)->format('Y-m-d');
            }

            if (strtotime($start_date) <= strtotime($end_date)) {
                $month = \Carbon\Carbon::parse($start_date)->format('F');
                $current_year = \Carbon\Carbon::parse($start_date)->format('Y');
                $final_payout_dates[$current_year][$month][$start_date] = $start_date;
            } else {
                $month = \Carbon\Carbon::parse($end_date)->format('F');
                $current_year = \Carbon\Carbon::parse($end_date)->format('Y');
                $date = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
                $final_payout_dates[$current_year][$month][$date] = $date;
            }
        }
        $i++;
    }
    return $final_payout_dates;
}

/**
 * calculate the payment due dates for any payment instance
 * @param \App\LeaseAssetPayments $payment
 * @return array
 */
function calculatePaymentDueDatesByPaymentId(\App\LeaseAssetPayments $payment)
{
    $final_payout_dates = [];
    //calculate all the due dates here from the start date till the end date...
    if ($payment->payment_interval == 1) {
        //check if the payments are going to be One-Time
        if ($payment->payout_time == 1) {
            //means that the payment is going to be made at the start of the interval
            $month = \Carbon\Carbon::parse($payment->first_payment_start_date)->format('F');
            $current_year = \Carbon\Carbon::parse($payment->first_payment_start_date)->format('Y');
            $final_payout_dates[$current_year][$month][$payment->first_payment_start_date] = $payment->first_payment_start_date;
        } else if ($payment->payout_time == 2) {
            //means that the payment is going to be made at the end of the interval
            $month = \Carbon\Carbon::parse($payment->last_payment_end_date)->format('F');
            $current_year = \Carbon\Carbon::parse($payment->last_payment_end_date)->format('Y');
            $final_payout_dates[$current_year][$month][$payment->last_payment_end_date] = $payment->last_payment_end_date;
        }
    }

    switch ($payment->payment_interval) {
        case 2:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date, $payment->payout_time, 1);
            break;
        case 3:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date, $payment->payout_time, 3);
            break;
        case 4:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date, $payment->payout_time, 6);
            break;
        case 5:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date, $payment->payout_time, 12);
            break;
    }
    return $final_payout_dates;
}

/**
 * generate the escalations that will be applied through out the year
 * @param array $data
 * @param \App\LeaseAssetPayments $payment
 * @param \App\Lease $lease
 * @param \App\LeaseAssets $asset
 * @return array
 */
function generateEsclationChart($data = [], \App\LeaseAssetPayments $payment, \App\Lease $lease, \App\LeaseAssets $asset)
{
    $effective_date = \Carbon\Carbon::parse($data['effective_from']);

    $escalation_applicable = $data['is_escalation_applicable'];
    $escalation_basis = isset($data['escalation_basis']) ? $data['escalation_basis'] : null;
    $escalation_applied_consistently_annually = isset($data['is_escalation_applied_annually_consistently']) ? $data['is_escalation_applied_annually_consistently'] : null;

    $start_date = $asset->accural_period; //start date with the free period
    $end_date = $asset->getLeaseEndDate($asset); //end date based upon all the conditions

    $escalation_start_date = ($asset->using_lease_payment == '1') ? \Carbon\Carbon::create(2019, 01, 01) : \Carbon\Carbon::parse($start_date);
    $escalation_end_date = \Carbon\Carbon::parse($end_date);

    $years = [];
    $start_year = $escalation_start_date->format('Y');
    $end_year = $escalation_end_date->format('Y');

    if ($start_year == $end_year) {
        $years[] = $end_year;
    } else if ($end_year > $start_year) {
        $years = range($start_year, $end_year);
    }

    $months = [];
    for ($m = 1; $m <= 12; ++$m) {
        $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
    }

    $escalations = [];
    $escalation_date = "";
    $escalation_percentage_or_amount = 0;
    $amount_to_consider = 0;

    while ($start_year <= $end_year) {
        //escalation date
        if ($escalation_date == "") {
            $escalation_date = firstEscalationDate($effective_date, $escalation_end_date, $payment);
        }
        $start_year = $start_year + 1; //for each year
    }

    $start_year = $escalation_start_date->format('Y'); //reset start_year

    $current_class = 'info';

    //consistently applied annually and for percentage basis and amount based
    if ($escalation_applicable == "yes" && (($escalation_basis == '1' && $escalation_applied_consistently_annually == 'yes') || ($escalation_basis == '2' && $escalation_applied_consistently_annually == 'yes'))) {
        while ($start_year <= $end_year) {
            foreach ($months as $key => $month) {
                $k_m = sprintf("%02d", $key);
                $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                    ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                    ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                    ->count();
                if ($payments_in_this_year_month > 0) {
                    $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                        ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                        ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                        ->first();
                    if($payments_in_this_year_month->total_payment_amount > 0){
                        //yes the user is paying on this month of this year
                        //the condition is applying the escalations only on the escalation date
                        //however it should apply the escalation on the next year as well
                        $condition = ((int)$escalation_date->format('Y') == $start_year && $k_m == (int)$escalation_date->format('m'))
                            || ($start_year >= (int)$escalation_date->format('Y') && $k_m >= (int)$escalation_date->format('m'));

                        if ($condition) {

                            if ($amount_to_consider == 0) {
                                //$amount_to_consider = $payment->payment_per_interval_per_unit;
                                $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                            }

                            if ($escalation_basis == '1') {
                                $escalation_percentage_or_amount = $data['total_escalation_rate'];
                                $amount_to_consider += ($amount_to_consider * $escalation_percentage_or_amount) / 100;
                            } else {
                                $escalation_percentage_or_amount = $data['escalated_amount'];
                                $amount_to_consider += $data['escalated_amount'];
                            }

                            if ($current_class == "info") {
                                $current_class = 'warning';
                            } elseif ($current_class == 'warning') {
                                $current_class = 'success';
                            } else {
                                $current_class = 'info';
                            }

                            $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];
                            $escalation_date->addYear(1); //applied annually

                        } else {
                            //escalation is not applied however the user needs to pay for this month and year
                            if ($amount_to_consider == 0) {
                                //$amount_to_consider = $payment->payment_per_interval_per_unit;
                                $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                            }
                            $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];
                        }

                    } else {

                        //no the user is not paying on this month of this year
                        $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                    }

                } else {
                    //no the user is not paying on this month of this year
                    $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                }
            }
            $start_year = $start_year + 1; //for each year
        }
    }


    //code to compute the escalations when applied inconsistently...
    if ($escalation_applicable == "yes" && (($escalation_basis == '1' && $escalation_applied_consistently_annually == 'no') || ($escalation_basis == '2' && $escalation_applied_consistently_annually == 'no'))) {
        if ($escalation_basis == '1') {
            $all_escalation_dates = array_column($data['inconsistent_effective_date'], '0');
            $all_escalation_dates_final = [];
            foreach ($all_escalation_dates as $all_escalation_date) {
                $all_escalation_dates_final[date('Y', strtotime($all_escalation_date))] = $all_escalation_date;
            }
        }

        while ($start_year <= $end_year) {
            foreach ($months as $key => $month) {

                $k_m = sprintf("%02d", $key);
                $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                    ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                    ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                    ->count();
                if ($payments_in_this_year_month > 0) {
                    $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                        ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                        ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                        ->first();
                    if($payments_in_this_year_month->total_payment_amount > 0){
                        $first_date_of_month = \Carbon\Carbon::parse("first day of {$month} {$start_year}");
                        $last_date_of_month = \Carbon\Carbon::parse("last day of {$month} {$start_year}");
                        if (isset($data['inconsistent_effective_date'][$start_year])) {
                            foreach ($data['inconsistent_effective_date'][$start_year] as $key => $escalation_date) {
                                $escalation_date_parsed = \Carbon\Carbon::parse($escalation_date);
                                if ($escalation_date_parsed->between($first_date_of_month, $last_date_of_month)) {
                                    if ($amount_to_consider == 0) {
                                        //$amount_to_consider = $payment->payment_per_interval_per_unit;
                                        $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                                    }

                                    if ($escalation_basis == '1') {

                                        $current_escalation_date = \Carbon\Carbon::parse($all_escalation_dates_final[$start_year]);

                                        $next_escalation_date = \Carbon\Carbon::parse(next($all_escalation_dates_final));

                                        if ($next_escalation_date == "") {
                                            $next_escalation_date = \Carbon\Carbon::create($start_year, 1, 1)->lastOfYear();
                                        }

                                        $diff_in_days = $next_escalation_date->diffInDays($current_escalation_date);

                                        if ($diff_in_days > 365) {
                                            $next_escalation_date = \Carbon\Carbon::create($start_year, 1, 1)->lastOfYear();
                                            $diff_in_days = $next_escalation_date->diffInDays($current_escalation_date);
                                        }

                                        $days_in_current_year = \Carbon\Carbon::create($start_year, 1, 1)->firstOfYear()->diffInDays(\Carbon\Carbon::create($start_year, 1, 1)->lastOfYear());

                                        $escalation_percentage_or_amount = $data['inconsistent_total_escalation_rate'][$start_year][$key];

                                        $amount_to_consider = $amount_to_consider * (1 + (($escalation_percentage_or_amount / 100) / $days_in_current_year) * $diff_in_days);

                                    } else {
                                        $escalation_percentage_or_amount = $data['inconsistent_escalated_amount'][$start_year][$key];
                                        $amount_to_consider += $data['inconsistent_escalated_amount'][$start_year][$key];
                                    }

                                    if ($current_class == "info") {
                                        $current_class = 'warning';
                                    } elseif ($current_class == 'warning') {
                                        $current_class = 'success';
                                    } else {
                                        $current_class = 'info';
                                    }

                                    $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];

                                } else {
                                    //escalation is not applied however the user needs to pay for this month and year
                                    if ($amount_to_consider == 0) {
                                        //$amount_to_consider = $payment->payment_per_interval_per_unit;
                                        $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                                    }
                                    $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];
                                }
                            }
                        } else {
                            //escalation is not applied however the user needs to pay for this month and year
                            if ($amount_to_consider == 0) {
                                //$amount_to_consider = $payment->payment_per_interval_per_unit;
                                $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                            }
                            $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];
                        }
                    } else {
                        $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                    }


                } else {
                    //no the user is not paying on this month of this year
                    $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                }
            }
            $start_year = $start_year + 1; //for each year
        }
    }

    //code to generate the escalation chart even when the escalations are not applied so that the total undiscounted amount can be calculated
    if ($escalation_applicable == "no") {
        while ($start_year <= $end_year) {
            foreach ($months as $key => $month) {

                $k_m = sprintf("%02d", $key);
                $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                    ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                    ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                    ->count();
                if ($payments_in_this_year_month > 0) {

                    $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()
                        ->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$start_year}'")
                        ->where('date','<=', \Carbon\Carbon::parse($end_date)->format('Y-m-d'))
                        ->first();
                    if($payments_in_this_year_month->total_payment_amount > 0){
                        //escalation is not applied however the user needs to pay for this month and year
                        if ($amount_to_consider == 0) {
                            //$amount_to_consider = $payment->payment_per_interval_per_unit;
                            $amount_to_consider = $payments_in_this_year_month->total_payment_amount;
                        }
                        $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => $amount_to_consider, 'current_class' => $current_class];
                    } else {
                        $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                    }

                } else {
                    //no the user is not paying on this month of this year
                    $escalations[$start_year][$month] = ['percentage' => $escalation_percentage_or_amount, 'amount' => 0, 'current_class' => $current_class];
                }
            }
            $start_year = $start_year + 1; //for each year
        }
    }

    return ['years' => $years, 'months' => $months, 'escalations' => $escalations];
}

/**
 * finds the first escalation date for the payment based upon the effective date, escalation end date which is the lease end date, and payment
 * @param $effective_date
 * @param $escalation_end_date
 * @param \App\LeaseAssetPayments $payment
 * @return \Carbon\Carbon|string
 */
function firstEscalationDate($effective_date, $escalation_end_date, \App\LeaseAssetPayments $payment)
{
    $return_date = "";
    while ($effective_date->lessThanOrEqualTo($escalation_end_date)) {
        $k_m = sprintf("%02d", $effective_date->format('m'));
        $year = sprintf("%02d", $effective_date->format('Y'));
        $payments_in_this_year_month = \App\LeaseAssetPaymenetDueDate::query()->whereRaw("`payment_id` = '{$payment->id}' AND DATE_FORMAT(`date`,'%m') = '{$k_m}' and DATE_FORMAT(`date`,'%Y') = '{$year}'")->count();
        if ($payments_in_this_year_month > 0) {
            $return_date = \Carbon\Carbon::create($year, $k_m, 1);
            break;
        }
        $effective_date = $effective_date->addMonth(1);
    }

    return $return_date;
}

/**
 * get total undiscounted lease payment total for the NL10
 * @param $asset_id
 * @return float|int|mixed
 */
function getUndiscountedTotalLeasePayment($asset_id)
{
    if ($asset_id) {
        $asset = \App\LeaseAssets::query()->findOrFail($asset_id);
        $total = 0;
        foreach ($asset->payments as $payment) {
            if ((isset($payment->paymentEscalationSingle) && $payment->paymentEscalationSingle->is_escalation_applicable == 'no') || !isset($payment->paymentEscalationSingle)) {
                //need to fetch the total of all the payments in payment annexure...
                //$payments_total = \App\LeaseAssetPaymenetDueDate::query()->where('payment_id', '=', $payment->id)->count();
                $payments_total = \App\LeaseAssetPaymenetDueDate::query()->where('payment_id', '=', $payment->id)->sum('total_payment_amount');
                //$payments_total = $payments_total * $payment->total_amount_per_interval;
                $total = $total + $payments_total;
            } else {
                //need to fetch the total of all the payments in payment escalation dates....
                $payments_total = \App\PaymentEscalationDates::query()->where('payment_id', '=', $payment->id)->sum('total_amount_payable');
                $total = $total + $payments_total;
            }
        }

        return $total;
    }


}


/**
 * confirm if the steps are present for any lease
 * @param $lease_id
 * @param $complete_step
 * @return mixed
 */
function confirmSteps($lease_id, $complete_step)
{
    if ($lease_id) {
        $data['lease_id'] = $lease_id;
        $data['completed_step'] = $complete_step;
        \App\LeaseCompletedSteps::query()->where('lease_id', '=', $lease_id)->where('completed_step', '=', $complete_step)->delete();
        $confrim_steps = \App\LeaseCompletedSteps::create($data);
    }
    return $confrim_steps;
}


/**
 * check and confirm if a particular step have the values
 * @param $lease_id
 * @param $complete_step
 * @return bool
 */
function checkPreviousSteps($lease_id, $complete_step)
{
    if ($lease_id) {
        $confrim_steps = \App\LeaseCompletedSteps::query()->where('lease_id', '=', $lease_id)->where('completed_step', $complete_step)->get();
        return (count($confrim_steps) > 0);
    }
    return false;
}

/**
 * Create a ula code LA001/001/2019 when lease is created
 * @return string
 */
function createUlaCode()
{

    $current_year = date("Y");
    $first_param = \App\Lease::query()->count();
    $second_param = \App\Lease::query()->whereRaw("YEAR(created_at) =  '$current_year'")->count();

    $string = "LA" . str_pad($first_param, 3, 0, STR_PAD_LEFT) . '/' . str_pad($second_param, 3, 0, STR_PAD_LEFT) . '/' . $current_year;
    return $string;
}

/* fetch the currency exchange rates as from the server end
 * @param null $date
 * @param $source
 * @param $target
 * @return int
 */
function fetchCurrencyExchangeRate($date = null, $source, $target)
{
    // set API Endpoint and access key (and any options of your choice)
    $endpoint = 'live';
    $access_key = env('CURRENCY_API_ACCESS_KEY');

    $url = 'http://apilayer.net/api/' . $endpoint . '?access_key=' . $access_key . '&source=' . $source . '&currencies=' . $target;
    if ($date) {
        $url .= '&date=' . $date;
    }

    // Initialize CURL:
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response:
    $exchangeRates = json_decode($json, true);

    if ($exchangeRates['success']) {
        $index = $source . $target;
        return $exchangeRates['quotes'][$index];
    } else {
        return 1; // will return 1 in case the currency rate is not found.
    }
}

/**
 * genreate the account id for the user based upon the user id..
 * @param $user
 * @return string
 */
function generateWitsyncAccountID($user)
{
    return 'WSUID' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
}

/**
 * genrate the Paypal redirect link and returns the string
 * @param \App\SubscriptionPlans $package
 * @param \App\UserSubscription $subscription
 * @return mixed
 * @throws Exception
 */
function generatePaypalExpressCheckoutLink(\App\SubscriptionPlans $package, \App\UserSubscription $subscription, $return_url = null, $cancel_url = null, $notify_url = null, $adjusted_amount = null, $quantity = 12)
{

    $provider = new ExpressCheckout();
    $data = [];
    $data['items'] = [
        [
            'name' => $package->title,
            'price' => $package->price,
            'qty' => $quantity // quantity is said to be 12 as the plan can only be purchased for 1 year
        ]
    ];

    $subtotal = 0;
    foreach ($data['items'] as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }

    if(!empty($adjusted_amount)) {
        if($adjusted_amount['balance'] < 0 && $adjusted_amount['adjusted_amount'] > 0){
            $data['items'][] = [
                'name' => 'Adjustable amount as per the previous subscription',
                'price' => -1 * $adjusted_amount['adjusted_amount'],
                'qty' => 1
            ];
        }
    }

    $used_credits = 0;
    if(auth()->check()){
        $credits = getParentDetails()->credit_balance;

        if($credits > 0){
            $data['items'][] = [
                'name' => 'Adjusted available credits.',
                'price' => -1 * $credits,
                'qty' => 1
            ];

            $used_credits = $credits;
            //need to send the custom data to the paypal so that it will return the amount of credits that needs to be deducted..
            $data['custom'] = "$credits";
        }
    }

    $data['invoice_id'] = $subscription->id;
    $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
    $data['return_url'] = ($return_url) ? $return_url : url('/payment/success');
    $data['cancel_url'] = ($cancel_url) ? $cancel_url : url('/payment/cancel');
    $data['notify_url'] = ($notify_url) ? $notify_url : url('/payment/notify');

    $total = 0;
    foreach ($data['items'] as $item) {
        $total += $item['price'] * $item['qty'];
    }

    $data['total'] = $total;

    //give a discount of 10% of the order amount
    $discounted_amount = 0;
    if ($package->annual_discount > 0) {
        $discounted_percentage = ($quantity / 12 - 1) * $package->annual_discount;
        if($discounted_percentage > 0){
            $discounted_amount = round(($package->annual_discount / 100) * $subtotal, 2);
            $data['shipping_discount'] = $discounted_amount;
        }
    }

    //save all the details to the user_subscription for the details so that these can be used for the doExpressCheckout
    $subscription->purchased_items = json_encode($data);
    $subscription->paid_amount = $data['total'];
    $subscription->adjusted_amount = (isset($adjusted_amount['adjusted_amount']))?$adjusted_amount['adjusted_amount']: 0;
    $subscription->credits_used = $used_credits;
    $subscription->discounted_amount = $discounted_amount;
    $subscription->save();
    $response = $provider->setExpressCheckout($data);
    return $response['paypal_link'];
}

/**
 * get the parent user get lease lock year when will stored in settings
 * render the lease lock year when user add in user settings
 * @return [type] [description]
 */
function getLockYearDetails()
{
    return \App\LeaseLockYear::query()->where('business_account_id', '=', auth()->user()->id)->where('status', '1')->first();
}

/**
 * return the information pages from the database...
 * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
 */
function getInformationPage()
{
    return \App\Cms::query()->where('status', '=', '1')->get();
}

/**
 * returns the yearRange as per the settings...
 * @return string
 */
function getYearRanage(){
    $settings = \App\GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
    return "yearRange: '{$settings->min_previous_first_lease_start_year}:{$settings->max_lease_end_year}',";
}

/**
 * calculate the adjustments during the subscription upgrade or downgrade as per the plan selected by the user...
 * @param \App\SubscriptionPlans $package
 * @param int $months
 * @return array
 */
function calculateAdjustedAmountForUpgradeDowngrade(\App\SubscriptionPlans $package, $months = 12){
    $existing_plan = \App\UserSubscription::query()
        ->whereIn('user_id', getDependentUserIds())
        ->orderBy('id', 'desc')
        ->where('payment_status', '=', 'Completed')->first();
    $final_payment_amount = [];
    if ($existing_plan && \Carbon\Carbon::today()->lessThanOrEqualTo(\Carbon\Carbon::parse($existing_plan->subscription_expire_at))) {
        //previous plan has not expired yet...
        if ($existing_plan->subscriptionPackage->price_plan_type == '1' || $package->is_custom == '1') {

            if ($package->price_plan_type == "1" && is_null($package->price)) {
                return ['status' => false, 'message' => 'You are not allowed to downgrade to a trial plan.'];
            }

            if (is_null($existing_plan->subscriptionPackage->price)) {
                //this means that the user previous plan was a trial plan and he will have to pay in full for the plan..
                $final_payment_amount['status'] = true;

                $final_payment_amount['adjusted_amount'] = 0;

                $new_plan_price = $package->price * $months;

                $discounted_percentage = 0;
                if ($package->annual_discount > 0) {
                    $discounted_percentage = ($months / 12 - 1) * $package->annual_discount;
                    if($discounted_percentage > 0){
                        $discounted_amount = round(($package->annual_discount / 100) * $new_plan_price, 2);
                        $new_plan_price = $new_plan_price - $discounted_amount;
                    }
                }

                $final_payment_amount['balance' ]  = round(-1 * $new_plan_price, 2);

                $final_payment_amount['gross_value_of_new_plan'] = round($new_plan_price, 2);

                $final_payment_amount['discounted_percentage'] = $discounted_percentage;

            } else {
                //need to do the calculations for calculating the price that the user might have to pay...
                $old_amount_paid = ($existing_plan->subscriptionPackage->price * ($existing_plan->subscription_years * 12)) - $existing_plan->discounted_amount;

                $old_plan_per_day_rate = $old_amount_paid / ($existing_plan->subscription_years * 365);

                $old_plan_effective_until = \Carbon\Carbon::yesterday()->format('Y-m-d');

                if (date('Y-m-d', strtotime($existing_plan->created_at)) == date('Y-m-d')) {
                    $old_plan_subscription_days = 0;
                } else {
                    $old_plan_subscription_days = \Carbon\Carbon::parse($existing_plan->created_at)->diffInDays(\Carbon\Carbon::parse($old_plan_effective_until));
                }

                $old_plan_price_levied = $old_plan_subscription_days * $old_plan_per_day_rate;

                $new_plan_price = $package->price * $months;

                $discounted_percentage = 0;
                if ($package->annual_discount > 0) {
                    $discounted_percentage = ($months / 12 - 1) * $package->annual_discount;
                    if($discounted_percentage > 0){
                        $new_plan_price = $new_plan_price - round(($package->annual_discount / 100) * $new_plan_price, 2);
                    }
                }

                $total_subscription_days = ($months/12) * 365;

                $new_plan_per_day_rate = $new_plan_price / $total_subscription_days;

                $new_plan_subscription_days = \Carbon\Carbon::parse($old_plan_effective_until)->addYear($months/12)->diffInDays(\Carbon\Carbon::today());

                $new_plan_price_levied = round($new_plan_subscription_days * $new_plan_per_day_rate);

                $total_payable = $new_plan_price_levied + $old_plan_price_levied;

                $final_payment_amount['status'] = true;

                $final_payment_amount['adjusted_amount'] = round($old_amount_paid - $old_plan_price_levied, 2);

                $final_payment_amount['balance' ]  = round($old_amount_paid - $total_payable, 2);

                $final_payment_amount['gross_value_of_new_plan'] = round($new_plan_price, 2);

                $final_payment_amount['discounted_percentage'] = $discounted_percentage;

            }

        } else {
            return [
                'status' => false,
                'message' => 'You are not allowed to downgrade from the enterprise plan.'
            ];
        }
    } else {
        //previous plan has expired and user will now have to pay in full....
        $final_payment_amount['status'] = true;

        $final_payment_amount['adjusted_amount'] = 0;

        $new_plan_price = $package->price * $months;

        $discounted_percentage = 0;
        if ($package->annual_discount > 0) {
            $discounted_percentage = ($months / 12 - 1) * $package->annual_discount;
            if($discounted_percentage > 0){
                $new_plan_price = $new_plan_price - round(($package->annual_discount / 100) * $new_plan_price, 2);
            }
        }

        $final_payment_amount['balance' ]  = round(-1 * $new_plan_price, 2);

        $final_payment_amount['gross_value_of_new_plan'] = round($new_plan_price, 2);

        $final_payment_amount['discounted_percentage'] = $discounted_percentage;
    }

    return $final_payment_amount;
}

/**
 * fetch the subscription plans to be listed on the pop up for the registration
 * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
 */
function getPaidSubscriptionPlans(){
    return \App\SubscriptionPlans::query()
        ->where('price_plan_type', '=', '1')
        ->whereNotNull('price')->get();
}