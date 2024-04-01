<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\SubAdmin;
use App\Models\MembershipPlan;
use App\Models\DiscountOffer;
use App\Models\MembershipRegistration;
use App\Models\Trainer;
use App\Models\MembershipRenewal;
use Carbon\Carbon;

class GymManagementController extends Controller
{
    // Logic for creating a new sub admin
    public function createSubAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:sub_admins,email',
            'password' => 'required|string|min:8',
            'super_admin_id' => 'required|exists:super_admins,id',
        ]);

        $subAdmin = SubAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'super_admin_id' => $request->super_admin_id,
        ]);

        return response()->json(['message' => 'Sub admin created successfully', 'data' => $subAdmin], 201);
    }

    // Logic for deleting a super admin
    public function deleteSuperAdmin($id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);
        $superAdmin->delete();
        return response()->json(['message' => 'Super admin deleted successfully'], 200);
    }

    // Logic for updating a membership plan
    public function updateMembershipPlan(Request $request, $id)
    {
        $membershipPlan = MembershipPlan::findOrFail($id);

        $request->validate([
            'type' => 'string',
            'membership_period' => 'string',
            'price' => 'numeric',
            'gym_sessions' => 'string',
            'swim_period' => 'string',
            'swim_sessions' => 'string',
        ]);

        $membershipPlan->update($request->all());

        return response()->json(['message' => 'Membership plan updated successfully', 'data' => $membershipPlan], 200);
    }

    // Logic for deleting a discount offer
    public function deleteDiscountOffer($id)
    {
        $discountOffer = DiscountOffer::findOrFail($id);
        $discountOffer->delete();
        return response()->json(['message' => 'Discount offer deleted successfully'], 200);
    }

    // Logic for updating a trainer's membership plan by sub admin
    public function updateTrainerMembershipPlan(Request $request, $trainerId)
    {
        $trainer = Trainer::findOrFail($trainerId);

        $request->validate([
            'membership_plan_id' => 'required|exists:membership_plans,id',
        ]);

        $trainer->membership_plan_id = $request->membership_plan_id;
        $trainer->save();

        return response()->json(['message' => 'Trainer membership plan updated successfully', 'data' => $trainer], 200);
    }

    // Logic for checking membership renewal and sending reminders
    public function checkMembershipRenewalAndSendReminders()
    {
        $memberships = MembershipRenewal::all();

        foreach ($memberships as $membership) {
            $expiryDate = Carbon::parse($membership->expiry_date);
            $threeDaysBeforeExpiry = $expiryDate->subDays(3);

            if (now()->isSameDay($threeDaysBeforeExpiry)) {
                // Implement logic to send membership renewal reminder
            }
        }

        return response()->json(['message' => 'Membership renewal reminders sent successfully'], 200);
    }
}
