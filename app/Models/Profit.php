<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanent_id',
        'project_id',
        'payment_id',
        'profit',
        'profit_percentage',
        'note'
    ];

    /**
     * Get the project associated with the profit
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tenant associated with the profit
     */
    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }

    /**
     * Get the payment associated with the profit
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Calculate profit from given data
     */
    public static function calculateProfit($projectBudget, $expertCost)
    {
        $netProfit = $projectBudget - $expertCost;
        $profitPercentage = $projectBudget > 0 ? ($netProfit / $projectBudget) * 100 : 0;

        return [
            'profit' => $netProfit,
            'profit_percentage' => round($profitPercentage, 2)
        ];
    }
    
}
