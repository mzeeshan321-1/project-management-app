<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expert;
use App\Models\Project;
use App\Models\Tanent;
use App\Models\Task;
use App\Models\Payment;
use App\Models\Profit;
use App\Models\File;
use App\Models\ProjectAssign;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tanentId = null;
        if ($user->tanent) {
            $tanentId = $user->tanent->id;
        } elseif($user->expert) {
            $tanentId = $user->expert->tanent_id;
            $expertId = $user->expert->id;
        } elseif ($user->client) {
            $tanentId = $user->client->tanent_id;
            $clientId = $user->client->id;
        }

        // Initialize all counts to zero
        $tanentsCount = 0;
        $expertsCount = 0;
        $clientsCount = 0;
        $projectsCount = 0;
        $tasksCount = 0;
        $paymentsCount = 0;
        $profitsCount = 0;
        $filesCount = 0;
        $projectAssignmentsCount = 0;
        
        // Initialize collections
        $recentProjects = collect();
        $pendingTasks = collect();
        $recentPayments = collect();
        
        // Load data based on user permissions
        if ($user->can('manage experts') || $user->can('view experts')) {
            $expertsCount = $tanentId ? Expert::where('tanent_id', $tanentId)->count() : 0;
        }
        
        if ($user->can('manage clients') || $user->can('view clients')) {
            $clientsCount = $tanentId ? Client::where('tanent_id', $tanentId)->count() : 0;
        }

        if ($user->can('manage middleman')) {
            if ($user->hasRole('super-admin')) {
                $tanentsCount = Tanent::all()->count();
            }
        }
        
        if ($user->can('manage projects') || $user->can('view projects') || $user->can('request new projects')) {
            if ($user->hasRole('expert')) {
                $projectsCount = Project::with('projectAssigns')
                ->where('tanent_id', $tanentId)
                ->whereHas('projectAssigns', function ($query) use ($expertId) {
                    $query->where('expert_id', $expertId);
                })->count();
            } elseif ($user->hasRole('client')) {
                $projectsCount = Project::with('client', 'tanent')
                ->where('tanent_id', $tanentId)
                ->where('client_id', $clientId)->count();
            } elseif ($user->hasRole('middleman')) {
                $projectsCount = Project::where('tanent_id', $tanentId)->count();
            } else {
                $projectsCount = 0;
            }

            // Load recent projects for users with project permissions
            $recentProjects = $tanentId ? Project::where('tanent_id', $tanentId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get() : collect();
        }
        
        if ($user->can('manage tasks') || $user->can('view tasks')) {
            if ($user->hasRole('middleman')) {
                $tasksCount = $tanentId ? Task::where('tanent_id', $tanentId)->count() : 0;
            } elseif ($user->hasRole('expert')) {
                $tasksCount = $tanentId ? Task::where('tanent_id', $tanentId)
                    ->whereHas('project', function ($query) use ($expertId) {
                        $query->whereHas('projectAssigns', function ($query) use ($expertId) {
                            $query->where('expert_id', $expertId);
                        });
                    })->count() : 0;
            } else {
                $tasksCount = 0;
            }
            // Load pending tasks for users with task permissions
            $pendingTasks = $tanentId ? Task::where('tanent_id', $tanentId)
                ->where('status', '!=', 'completed')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get() : collect();
        }
        
        if ($user->can('manage payments') || $user->can('view payments') || $user->can('create payments')) {
            if ($user->hasRole('middleman')) {
                $paymentsCount = $tanentId ? Payment::whereHas('project', function($query) use ($tanentId) {
                    $query->where('tanent_id', $tanentId);
                })->count() : 0;
            } 
            elseif ($user->hasRole('expert')) {
                $paymentsCount = $tanentId ? Payment::whereHas('project', function($query) use ($tanentId) {
                    $query->where('tanent_id', $tanentId);
                })
                    ->whereHas('receiver', function($query) use ($user) {
                        $query->where('reciever_id', $user->id);
                    })
                    ->count() : 0;
            } elseif ($user->hasRole('client')) {
                $paymentsCount = $tanentId ? Payment::whereHas('project', function($query) use ($tanentId) {
                    $query->where('tanent_id', $tanentId);
                })
                    ->whereHas('sender', function($query) use ($user) {
                        $query->where('sender_id', $user->id);
                    })
                    ->count() : 0;
            }
            
            // Load recent payments for users with payment permissions
            $recentPayments = $tanentId ? Payment::whereHas('project', function($query) use ($tanentId) {
                $query->where('tanent_id', $tanentId);
            })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get() : collect();
        }
        
        if ($user->can('manage reports') || $user->can('view reports')) {
            // For profits, we need to get them through projects since there's no direct tanent_id column
            $profitsCount = $tanentId ? Profit::whereHas('project', function($query) use ($tanentId) {
                $query->where('tanent_id', $tanentId);
            })->count() : 0;
        }
        
        if ($user->can('manage project deliverables') || $user->can('upload project deliverables') ||
            $user->can('update project deliverables')) {
            if ($user->hasRole('middleman')) {
                $filesCount = File::where('tanent_id', $tanentId)->count();
            } elseif ($user->hasRole('expert')) {
                $filesCount = File::whereHas('project', function($query) use ($tanentId) {
                    $query->where('tanent_id', $tanentId);
                })
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('id', $user->id);
                    })
                    ->count();
            } else {
                $filesCount = 0;
            }
        }
        
        if ($user->can('assign projects')) {
            $projectAssignmentsCount = $tanentId ? ProjectAssign::where('tanent_id', $tanentId)->count() : 0;
        }
        
        return view('dashboard', compact(
            'tanentsCount',
            'expertsCount',
            'clientsCount',
            'projectsCount',
            'tasksCount',
            'paymentsCount',
            'profitsCount',
            'filesCount',
            'projectAssignmentsCount',
            'recentProjects',
            'pendingTasks',
            'recentPayments'
        ));
    }
}
