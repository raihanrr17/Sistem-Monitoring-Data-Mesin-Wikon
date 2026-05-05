<?php

namespace App\Service;

use App\Models\Machine;

class DashboardService
{
    public function getDashboardData($bulan, $tahun)
    {
        $data = Machine::where('bulan', $bulan)
                     ->where('tahun', $tahun)
                     ->get();

        $active = $data->where('loading_time', '>', 0);

        $avg = $active->avg('availability') ?? 0;
        $totalBreakdown = $data->sum('freq_breakdown');

        $plants = ['DC','GC','SB','CNC'];
        $plantStats = [];

        foreach ($plants as $plant) {
            $pData = $data->where('plant', $plant)->where('loading_time', '>', 0);

            if ($pData->count() > 0) {
                $plantStats[$plant] = [
                    'avg' => round($pData->avg('availability'), 2),
                    'max' => $pData->sortByDesc('availability')->first(),
                    'min' => $pData->sortBy('availability')->first()
                ];
            } else {
                $plantStats[$plant] = null;
            }
        }

        return [
            'total_mesin' => $data->count(),
            'avg_availability' => round($avg, 2),
            'total_breakdown' => $totalBreakdown,
            'plants' => $plantStats
        ];
    }
}