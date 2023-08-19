<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaymentRequest;
use App\Models\Enums\PaymentStatus;
use App\Models\Enums\SourcePayment;
use App\Models\Payment;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Payment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment');
        $this->crud->setEntityNameStrings('payment', 'payments');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => '#',
            ],
            [
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'closure',
                'function' => function ($entry) {
                    $user = Payment::with('user')->where('id', $entry->id)->first()->user;
                    return "[{$user->id}] {$user->name}";
                }
            ],
            [
                'name' => 'status',
                'label' => 'status',
                'type' => 'closure',
                'orderable' => false,
                'function' => function ($entry) {
                    $value = $entry->status;
                    return PaymentStatus::getIcon($value);
                }
            ],
            [
                'name' => 'source',
                'label' => 'Source',
                'type' => 'closure',
                'orderable' => false,
                'function' => function ($entry) {
                    $value = $entry->source;
                    return SourcePayment::getIcon($value);
                }
            ],
            [
                'name' => 'price',
                'label' => 'Price',
            ],
            [
                'name' => 'trading_code',
                'label' => "Trading code"
            ],
            [
                'name' => 'transaction_id',
                'label' => "Transaction id"
            ]
        ]);

        $this->crud->addFilter([
            'name' => 'payment_status',
            'type' => 'dropdown',
            'label' => 'Payment status'
        ], PaymentStatus::toOptions(), function ($value) { // if the filter is active
            $this->crud->addClause('where', 'status', $value);
        });

        $this->crud->addFilter([
            'name' => 'source',
            'type' => 'dropdown',
            'label' => 'Source'
        ], SourcePayment::toOptions(), function ($value) { // if the filter is active
            $this->crud->addClause('where', 'source', $value);
        });

        $this->crud->addFilter([
            'name' => 'price',
            'type' => 'range',
            'label_from' => 'Min price',
            'label_to' => 'Max price'
        ], false, function ($value) {
            $range = json_decode($value);
            if ($range->from) {
                $this->crud->addClause('where', 'price', '>=', (float)$range->from);
            }
            if ($range->to) {
                $this->crud->addClause('where', 'price', '<=', (float)$range->to);
            }
        });

        $this->crud->addFilter([
            'name' => 'filter_user_id',
            'type' => 'select2_ajax',
            'label' => 'User',
            'placeholder' => 'Pick a user'
        ],
            url('admin/ajax-user-options'), // the ajax route
            function ($value) {
                if ($value) { //Bug's backpack
                    $this->crud->with('user')->when($value, function (Builder $query, $value) {
                        return $query->whereHas('user', function (Builder $query) use ($value) {
                            $query->where('user_id', $value);
                        });
                    });
                }
            });
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(PaymentRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }
}
