<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use App\Models\Project;
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
        $projectsController = new ProjectsController();
        $projectsController->calculateProfit();

        $profits = Profit::with(['project', 'payment', 'tanent'])
            ->when(auth()->user()->tanent, function ($query) {
                return $query->where('tanent_id', auth()->user()->tanent->id);
            })
            ->orderBy('created_at', 'desc')->get();

        return view('profit_reports.index', compact('profits'));
    }

    /**
     * Display profit details
     */
    public function show(Profit $profit)
    {
        if (!auth()->user()->hasPermissionTo('view reports')) {
            abort(403, 'Unauthorized action.');
        }

        $profit->load(['project', 'payment', 'tanent']);
        return view('profit_reports.show', compact('profit'));
    }

    public function destroy(string $id)
    {
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

