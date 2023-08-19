<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Document;
use App\Models\Enums\SourcePayment;
use App\Models\Payment;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class PaymentChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentChartController extends ChartController
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
        $this->chart->load(backpack_url('charts/payment'));

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
             $total_payment[] = Payment::whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('price');
             $total_payment_vnpay[] = Payment::where('source', SourcePayment::VNPAY)->whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('price');
             $total_payment_paypal[] = Payment::where('source', SourcePayment::PAYPAL)->whereDate('created_at', '<=', today()->subDays($days_backwards))->sum('price');
         }

         $this->chart->dataset('Total money payment', 'line', $total_payment)
             ->color('rgb(96, 92, 168)')
             ->backgroundColor('rgba(96, 92, 168, 0.4)');

         $this->chart->dataset('Total money payment VNPay', 'line', $total_payment_vnpay)
             ->color('rgb(255, 193, 7)')
             ->backgroundColor('rgba(255, 193, 7, 0.4)');

         $this->chart->dataset('Total money Payment Paypal', 'line', $total_payment_paypal)
             ->color('rgb(155, 200, 155)')
             ->backgroundColor('rgba(155, 200, 155, 0.4)');
     }
}
