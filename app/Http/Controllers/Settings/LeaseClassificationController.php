<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:07 AM
 */

namespace App\Http\Controllers\Settings;


use App\ContractClassifications;
use App\Http\Controllers\Controller;
use App\LeasePaymentComponents;
use App\RateTypes;
use App\UseOfLeaseAsset;

class LeaseClassificationController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.leaseclassification'),
                'title' => 'Lease Classification Settings'
            ]
        ];

        $rates = RateTypes::query()->where('status', '=', '1')->get();
        $contract_classifications = ContractClassifications::query()->where('status', '=', '1')->get();
        $lease_asset_use = UseOfLeaseAsset::query()->where('status', '=', '1')->get();
        $lease_payment_component = LeasePaymentComponents::query()->where('status', '=', '1')->get();
        return view('settings.classification.index', compact('breadcrumbs', 'rates', 'contract_classifications', 'lease_asset_use', 'lease_payment_component'));
    }

}