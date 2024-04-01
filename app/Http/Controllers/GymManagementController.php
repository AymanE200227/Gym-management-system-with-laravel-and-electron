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
    // Logic for creating a new super admin
    public function createSuperAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:super_admins,email',
            'password' => 'required|string|min:8',
        ]);

        $superAdmin = SuperAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'Super admin created successfully', 'data' => $superAdmin], 201);
    }

    // Logic for deleting a sub admin
    public function deleteSubAdmin($id)
    {
        $subAdmin = SubAdmin::findOrFail($id);
        $subAdmin->delete();
        return response()->json(['message' => 'Sub admin deleted successfully'], 200);
    }

    // Logic for creating a new membership plan
    public function createMembershipPlan(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'membership_period' => 'required|string',
            'price' => 'required|numeric',
            'gym_sessions' => 'required|string',
            'swim_period' => 'required|string',
            'swim_sessions' => 'required|string',
        ]);

        $membershipPlan = MembershipPlan::create($request->all());

        return response()->json(['message' => 'Membership plan created successfully', 'data' => $membershipPlan], 201);
    }

    // Logic for updating a discount offer
    public function updateDiscountOffer(Request $request, $id)
    {
        $discountOffer = DiscountOffer::findOrFail($id);
        
        $request->validate([
            'name' => 'string',
            'description' => 'string',
            'discount_percentage' => 'numeric',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ]);

        $discountOffer->update($request->all());

        return response()->json(['message' => 'Discount offer updated successfully', 'data' => $discountOffer], 200);
    }

    // Logic for retrieving dashboard statistics
    public function getDashboardStatistics()
    {
        // Logic to fetch and calculate dashboard statistics
        // For example, calculating total registrations and total income for each month and year
        $registrationsByMonthYear = MembershipRegistration::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as registrations')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $incomeByMonthYear = MembershipRegistration::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, sum(price) as total_income')
            ->join('membership_plans', 'membership_registrations.membership_plan_id', '=', 'membership_plans.id')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return response()->json([
            'registrations_by_month_year' => $registrationsByMonthYear,
            'income_by_month_year' => $incomeByMonthYear,
        ]);
    }

    // Logic for adding a new trainer by sub admin
    public function addTrainer(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'tel' => 'required|unique:trainers,tel',
            'sub_admin_id' => 'required|exists:sub_admins,id',
        ]);

        $trainer = Trainer::create($request->all());

        return response()->json(['message' => 'Trainer added successfully', 'data' => $trainer], 201);
    }

    // Logic for checking in a trainer by sub admin
    public function checkInTrainer(Request $request, $trainerId)
    {
        $request->validate([
            'swim' => 'boolean',
            'checkin_date' => 'required|date',
        ]);

        $trainer = Trainer::findOrFail($trainerId);
        // Implement logic for checking trainer's swim sessions and gym sessions
        // For example, updating trainer's last check-in date, tracking swim sessions, gym sessions, etc.
        
        return response()->json(['message' => 'Trainer checked in successfully'], 200);
    }

    // Logic for deleting a membership plan by sub admin
    public function deleteMembershipPlan($id)
    {
        $membershipPlan = MembershipPlan::findOrFail($id);
        $membershipPlan->delete();
        return response()->json(['message' => 'Membership plan deleted successfully'], 200);
    }

    // Logic for sending payment reminders to trainers
    public function sendPaymentReminders()
    {
        $trainers = Trainer::all();

        foreach ($trainers as $trainer) {
            $expiryDate = Carbon::parse($trainer->expiry_date);
            $threeDaysBeforeExpiry = $expiryDate->subDays(3);

            if (now()->isSameDay($threeDaysBeforeExpiry)) {
                // Implement logic to send payment reminder to the trainer
            }
        }

        return response()->json(['message' => 'Payment reminders sent successfully'], 200);
    }
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


