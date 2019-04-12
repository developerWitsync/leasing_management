<?php

namespace App\Http\Middleware;

use App\Lease;
use App\User;
use App\UserSubscription;
use Carbon\Carbon;
use Closure;

class CheckSubscription
{
    //Handle an incoming request.
    public function handle($request, Closure $next, $function = null, $param_type = null, $param = null)
    {
        //check if the logged in user have purchased a plan or not
        $subscription = UserSubscription::query()->whereIn('user_id', getDependentUserIds())
            ->where('payment_status', '<>', 'pending')
            ->where('payment_status', '<>', 'Cancelled')
            ->orderBy('id', 'desc')
            ->first();
        if($subscription){
            //need to check if the purchase and active subscription have expired

            if(!Carbon::today()->lessThanOrEqualTo(Carbon::parse($subscription->subscription_expire_at))){
                //subscription has expired need to redirect to the upgrade plan page...
                return redirect(route('plan.index'))->with('error', "Your subscription plan has expired. Please purchase any subscription plan.");
            }

            //check if the limit for adding leases has reached...
            if($function == "add_lease"){
                $submitted_leases = Lease::query()->whereIn('business_account_id', getDependentUserIds());

                if ($param_type == 'asset_id') {
                    $asset_id = $request->route($param);
                    $asset = \App\LeaseAssets::query()->where('id', '=', $asset_id)->first();
                    $lease_id = $asset->lease_id;
                } else {
                    $lease_id = $request->route($param);
                }

                if($lease_id){
                    //check the lease status and is_completed_status
                    $lease = Lease::query()->findOrFail($lease_id);
                    if($lease->status == "0" && $lease->is_completed == 1) {
                        //check if the lease is under modification
                        $underModification = $lease->modifyLeaseApplication->last();
                        if($underModification){
                            //Don't include under counting...
                            $submitted_leases = $submitted_leases->where('id', '<>', $lease_id);
                        } else {
                            //Include under counting...
                            //$submitted_leases = $submitted_leases->where('id', '<>', $lease_id);
                        }
                    } else if($lease->status == "0" && $lease->is_completed == 0) {
                        //don't include under counting..
                        $submitted_leases = $submitted_leases->where('id', '<>', $lease_id);
                    }
                }

                $submitted_leases = $submitted_leases->count();

                $allowed_lease = $subscription->subscriptionPackage->available_leases;
                if($allowed_lease <= $submitted_leases){
                    //not allowed to add new lease and needs to redirect to the upgrade plan page..
                    return redirect(route('plan.index'))->with('error', "You have utilised the allowed number of leases as per your plan. Please upgrade your subscription plan.");
                }
            }

            //need to check for the allowed sub-users for the business account subscription when $function = add_sub_user from the routes...
            if($function == "add_sub_users"){
                $sub_users = User::query()->whereIn('parent_id', getDependentUserIds())->count();
                $allowed_sub_users = $subscription->subscriptionPackage->available_users;
                if(($allowed_sub_users - $sub_users) == 0){
                    return redirect(route('plan.index'))->with('error', "You have utilised the allowed number of sub users as per your plan. Please upgrade your subscription plan.");
                }
            }

        } else {
            //the logged in users company doesn't have purchased an subscription plan
            return redirect(route('plan.index'))->with('error', "You don't have any active subscription plans, please purchase any plan to use the software.");
        }
        return $next($request);
    }
}
