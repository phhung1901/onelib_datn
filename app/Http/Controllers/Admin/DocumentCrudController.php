<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DocumentRequest;
use App\Libs\CountriesHelper\Countries;
use App\Libs\CountriesHelper\Languages;
use App\Models\Document;
use App\Models\Enums\TypeDocument;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
use Illuminate\Http\Request;

/**
 * Class DocumentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DocumentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use BulkDeleteOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Document');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/document');
        $this->crud->setEntityNameStrings('document', 'documents');
        $this->crud->enableDetailsRow();
        $this->crud->enableBulkActions();

    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => '#',
            ],
            [
                'name' => 'active',
                'label' => "Active",
                'type' => 'check',
                'orderable' => false,
            ],
            [
                'name' => 'title',
                'label' => 'title',
                'type' => 'preview_file',
            ],
            [
                'name' => 'page_number',
                'label' => 'Page number'
            ],
            [
                'name' => 'price',
                'label' => 'Price',
                'attributes' => ["step" => "any"], // allow decimals
                'suffix' => ".00",
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'closure',
                'function' => function ($entry) {
                    $value = $entry->type;
                    return TypeDocument::getIcon($value);
                }
            ],
            [
                'name' => 'rating_count',
                'label' => 'Total rating'
            ],
            [
                'name' => 'viewed_count',
                'label' => 'Total view'
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DocumentRequest::class);

        $this->crud->addField([
            'name' => 'active',
            'label' => "Active",
            'type' => 'checkbox'
        ]);

        $this->crud->addField([
            'name' => 'title',
            'label' => "Title",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'source_url',
            'label' => "Source",
            'type' => 'upload',
            'upload' => true,
//            'disk' => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
//            'path' => 'storage/pdftest',
//            'temporary' => 10, // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
//            'filename' => function ($file) {
//                return md5(uniqid()) . '.' . $file->getClientOriginalExtension();
//            },/college/
//            'prefix'    => 'pdftest/',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => "Description",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'page_number',
            'label' => "Page",
            'type' => 'number',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'price',
            'label' => "Price",
            'type' => 'number',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'type',
            'label' => "Type",
            'type' => 'select_from_array',
            'options' => TypeDocument::toOptions(),
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'full_text',
            'label' => "Full text",
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'language',
            'label' => "Language",
            'type' => 'select2_from_array',
            'options' => Languages::getOptions(),
            'allows_null' => false,
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'country',
            'label' => "Country",
            'type' => 'select2_from_array',
            'options' => Countries::getOptions(),
            'allows_null' => false,
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function showDetailsRow($id)
    {
        dump(Document::findOrFail($id)->toArray());
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
