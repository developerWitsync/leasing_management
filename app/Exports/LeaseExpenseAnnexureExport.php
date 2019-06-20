<?php
/**
 * Created by PhpStorm.
 * User: flexsin
 * Date: 20/6/19
 * Time: 6:57 PM
 */

namespace App\Exports;
use App\LeaseExpenseAnnexure;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeaseExpenseAnnexureExport implements FromView
{
    private $asset_id;

    public function __construct($id)
    {
        $this->asset_id = $id;
    }

    /**
     * generates the excel for the interest and depreciation...
     * @return View
     */
    public function view(): View
    {

        $lease_expense_annexure = LeaseExpenseAnnexure::query()->where('asset_id', '=', $this->asset_id)->get();
        $lease_payments = LeaseExpenseAnnexure::query()->where('asset_id', '=', $this->asset_id)->first();

        return view('leasevaluation.partials._lease_expense_annexure', compact(
            'lease_expense_annexure',
            'lease_payments'
        ));
    }
}