@extends(backpack_view('blank'))

@php
    Widget::add()->to('before_content')->type('div')->class('row')->content([
            // notice we use Widget::make() to add widgets as content (not in a group)
            Widget::make()
                ->type('progress')
                ->class('card border-0 text-white bg-primary')
                ->progressClass('progress-bar')
                ->value(App\Models\Document::select('id')->count())
                ->description('Total document.')
                ->progress(100),
              Widget::make()
                ->type('progress')
                ->class('card border-0 text-white bg-info')
                ->progressClass('progress-bar')
                ->value(App\Models\Payment::sum('price')." $")
                ->description('Total payment count.')
                ->progress(100),

              Widget::make()
                ->type('progress')
                ->class('card border-0 text-white bg-warning')
                ->value(App\Models\Category::select('id')->count())
                ->progressClass('progress-bar')
                ->description('Total category.')
                ->progress(100),

	          Widget::make()
                ->type('progress')
                ->class('card border-0 text-white bg-danger')
                ->progressClass('progress-bar')
                ->value(App\Models\Report::select('id')->count())
                ->description('Total report.')
                ->progress(100),
        ]);

     $widgets['before_content'][] = [
             'type' => 'div',
             'class' => 'row',
             'content' => [ // widgets
                     [
                       'type' => 'chart',
                       'wrapperClass' => 'col-md-6',
                       'controller' => \App\Http\Controllers\Admin\Charts\DatatableDocumentChartController::class,
                       'content' => [
                           'header' => 'Data count', // optional
                            'body' => 'This chart show count of data in document table', // optional
                       ]
                   ],
                       [
                       'type' => 'chart',
                       'wrapperClass' => 'col-md-6',
                       'controller' => \App\Http\Controllers\Admin\Charts\TotalDocumentChartController::class,
                       'content' => [
                           'header' => 'Total Document', // optional
                            'body' => 'This chart count total, is active, is public of document table', // optional
                       ]
                   ],
                            [
                       'type' => 'chart',
                       'wrapperClass' => 'col-md-6',
                       'controller' => \App\Http\Controllers\Admin\Charts\PaymentChartController::class,
                       'content' => [
                           'header' => 'Total money payment ($)', // optional
                            'body' => 'This chart count total money payment in website by week', // optional
                       ]
                   ],
               ]
           ];
@endphp

@section('content')
@endsection
