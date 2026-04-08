<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use App\Models\Project;
use App\Models\Payment;
use App\Http\Controllers\ProjectsController;

class ProfitReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view reports')) {
            abort(403, 'Unauthorized action.');
        }
        $this->calculateProfit();
        $profits = Profit::with(['project', 'project.projectAssigns', 'payment', 'tanent'])
            ->when(auth()->user()->tanent, function ($query) {
                return $query->where('tanent_id', auth()->user()->tanent->id);
            })
            ->orderBy('created_at', 'desc')->get();

        // Add calculated fields to each profit for the view
        foreach ($profits as $profit) {
            $profit->project_budget = $profit->project->budget ?? 0;
            $profit->expert_cost = $profit->project->projectAssigns->sum('budget') ?? 0;
            $profit->payment_amount = $profit->payment->amount ?? 0;
            $profit->net_profit = $profit->profit ?? 0;
            $profit->status = $profit->project->status ?? 'unknown';
        }

        return view('profit_reports.index', compact('profits'));
    }

    /**
     * Calculate profit.
     */
    public function calculateProfit($project = null, $payment = null)
    {
        // If specific project and payment are provided, calculate for that specific record
        if ($project && $payment) {
            $payment = Payment::with(['project', 'project.projectAssigns'])->find($payment);
            if ($payment && $payment->project && $payment->project->id == $project) {
                $this->calculateProfitForPayment($payment);
            }
            return redirect()->back();
        }

        $tanentClients = auth()->user()->tanent->clients->pluck('user_id')->toArray();
        // Get payments with related project and project assignments
        $payments = Payment::with(['project', 'project.projectAssigns'])
            ->whereHas('project', function ($query) {
                $query->where('tanent_id', auth()->user()->tanent->id)
                    ->where('status', 'completed')
                    ->where('approval_status', true);
            })
            ->whereIn('sender_id', $tanentClients)
            // ->where('status', 'received')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($payments as $payment) {
            $this->calculateProfitForPayment($payment);
        }
    }

    /**
     * Calculate profit for a specific payment
     */
    private function calculateProfitForPayment($payment)
    {
        $project = $payment->project;

        // Skip if payment or project id not found
        if (!$payment) {
            return;
        }

        // Skip if project has no assignments
        if ($project->projectAssigns->count() === 0) {
            return;
        }

        try {
            // Get project budget
            $projectBudget = $project->budget;

            // Calculate total expert cost from assignments (0 if no assignments)
            $expertCost = $project->projectAssigns->sum('budget') ?? 0;

            // dd($project->projectAssigns);

            // Use payment amount as the actual received amount
            $paymentAmount = $payment->amount;

            // Calculate profit using the model's method
            // Using payment amount instead of project budget for more accurate calculation
            $profitCalc = Profit::calculateProfit($projectBudget, $expertCost);

            // Create or update profit record
            Profit::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'payment_id' => $payment->id
                ],
                [
                    'tanent_id' => $project->tanent_id,
                    'expert_cost' => $expertCost,
                    'profit' => $profitCalc['profit'],
                    'profit_percentage' => $profitCalc['profit_percentage'],
                ]
            );
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error('Error calculating profit for payment ID ' . $payment->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Display profit details
     */
    public function show(Profit $profit)
    {
        if (!auth()->user()->hasPermissionTo('view reports')) {
            abort(403, 'Unauthorized action.');
        }
        $profit->load(['project', 'project.projectAssigns', 'payment', 'tanent']);

        if (empty($profit)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Profit ID no: ' . $profit->id . ' Not found!');
            return redirect()->back();
        }

        // Add calculated fields to the profit for the view
        $profit->project_budget = $profit->project->budget ?? 0;
        $profit->payment_amount = $profit->payment->amount ?? 0;
        $profit->net_profit = $profit->profit ?? 0;
        $profit->status = $profit->project->status ?? 'unknown';

        return view('profit_reports.show', compact('profit'));
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermissionTo('view reports')) {
            abort(403, 'Unauthorized action.');
        }
        $profit = Profit::find($id);
        if (empty($profit)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Profit ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        try {
            $profit->delete();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Profit ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('profits.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

