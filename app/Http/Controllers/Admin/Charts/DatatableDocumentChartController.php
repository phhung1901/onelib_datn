<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Document;
use App\Models\Enums\Calculate;
use App\Models\Enums\TypeInfo;
use App\Models\Report;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

/**
 * Class DatatableDocumentChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DatatableDocumentChartController extends ChartController
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
        $this->chart->load(backpack_url('charts/datatable-document'));

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

            $viewed_count[] = Document::whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('viewed_count');
            $downloaded_count[] = Document::whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('downloaded_count');
            $helpful_count[] = Document::whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('helpful_count');
            $unhelpful_count[] = Document::whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('unhelpful_count');
            $total_report[] = Report::select('id')->whereDate('created_at', '<=', today()->subDays($days_backwards))->count();

        }
        $this->chart->dataset('View count', 'line', $viewed_count)
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');

        $this->chart->dataset('Download count', 'line', $downloaded_count)
            ->color('rgb(255, 193, 7)')
            ->backgroundColor('rgba(255, 193, 7, 0.4)');

        $this->chart->dataset('Helpful count', 'line', $helpful_count)
            ->color('rgb(155, 200, 155)')
            ->backgroundColor('rgba(155, 200, 155, 0.4)');

        $this->chart->dataset('Unhelpful count', 'line', $unhelpful_count)
            ->color('rgb(55, 255, 155)')
            ->backgroundColor('rgba(55, 255, 155, 0.4)');

        $this->chart->dataset('Report count', 'line', $total_report)
            ->color('rgb(145, 123, 200)')
            ->backgroundColor('rgba(145, 123, 200, 0.4)');
    }
}
