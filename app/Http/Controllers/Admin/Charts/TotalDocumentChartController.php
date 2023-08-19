<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Document;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class TotalDocumentChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TotalDocumentChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        for ($days_backwards = 28; $days_backwards >= 0; $days_backwards -= 7) {
            if ($days_backwards == 1) {

            }
            $labels[] = $days_backwards . ' days ago';

        }
        $this->chart->labels($labels);
        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/total-document'));

        // OPTIONAL
         $this->chart->minimalist(false);
         $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
     public function data()
     {
         for ($days_backwards = 28; $days_backwards >= 0; $days_backwards -= 7) {

             $total_document[] = Document::select('id')->whereDate('created_at', '<=', today()->subDays($days_backwards))->count();
             $total_active[] = Document::select('active')->where('active', true)->whereDate('created_at', '<=', today()->subDays($days_backwards))->count();
             $total_public[] = Document::select('is_public')->where('is_public', true)->whereDate('created_at', '<=', today()->subDays($days_backwards))->count();
         }
         $this->chart->dataset('Total document', 'line', $total_document)
             ->color('rgb(96, 92, 168)')
             ->backgroundColor('rgba(96, 92, 168, 0.4)');

         $this->chart->dataset('Total active', 'line', $total_active)
             ->color('rgb(255, 193, 7)')
             ->backgroundColor('rgba(255, 193, 7, 0.4)');

         $this->chart->dataset('Total public', 'line', $total_public)
             ->color('rgb(155, 200, 155)')
             ->backgroundColor('rgba(155, 200, 155, 0.4)');

     }
}
