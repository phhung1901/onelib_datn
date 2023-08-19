<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DownloadRequest;
use App\Models\Download;
use App\Models\FacebookUser;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class DownloadCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DownloadCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Download');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/download');
        $this->crud->setEntityNameStrings('download', 'downloads');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => '#',
            ],
            [
                'name' => 'document_id',
                'label' => 'Document',
                'type' => 'closure',
                'function' => function ($entry) {
                    $document = Download::with('document')->where('id', $entry->id)->first()->document;
                    $title = substr($document->title, 0, 100);
                    return "[{$document->id}] {$title}";
                }
            ],
            [
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'closure',
                'function' => function ($entry) {
                    $user = Download::with('user')->where('id', $entry->id)->first()->user;
                    return "[{$user->id}] {$user->name}";
                }
            ],
            [
                'name' => 'payload',
                'label' => 'Price {$}',
                'type' => 'closure',
//                'escaped' => false,
                'function' => function ($entry) {
                    return $entry->payload['price'] . " $";
                }
            ]
        ]);

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

        $this->crud->addFilter([
            'name' => 'filter_document_id',
            'type' => 'select2_ajax',
            'label' => 'Document',
            'placeholder' => 'Pick a document'
        ],
            url('admin/ajax-document-options'), // the ajax route
            function ($value) {
                if ($value) { //Bug's backpack
                    $this->crud->with('document')->when($value, function (Builder $query, $value) {
                        return $query->whereHas('document', function (Builder $query) use ($value) {
                            $query->where('document_id', $value);
                        });
                    });
                }
            });

        $this->crud->addFilter([
            'name' => 'price',
            'type' => 'range',
            'label_from' => 'Min price',
            'label_to' => 'Max price'
        ], false, function ($value) {
            $range = json_decode($value);
            if ($range->from) {
                $this->crud->addClause('where', "payload->price", '>=', (float)$range->from);
            }
            if ($range->to) {
                $this->crud->addClause('where', "payload->price", '<=', (float)$range->to);
            }
        });
    }

    public function userOptions(Request $request)
    {
        $term = $request->input('term');
        return User::where('name', 'like', '%' . $term . '%')->get()->pluck('name', 'id');
    }
}
