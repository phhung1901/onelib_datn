<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Document;
use App\Models\Download;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class CommentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Comment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comment');
        $this->crud->setEntityNameStrings('comment', 'comments');
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
                    $document = Comment::with('documents')->where('id', $entry->id)->first()->documents;
                    $title = substr($document->title, 0, 100);
                    return "[{$document->id}] {$title}";
                },
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('document_id', $searchTerm);
                }
            ],
            [
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'closure',
                'function' => function ($entry) {
                    $user = Comment::with('users')->where('id', $entry->id)->first()->users;
                    return "[{$user->id}] {$user->name}";
                },
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('user_id', $searchTerm);
                }
            ],
            [
                'name' => 'content',
                'label' => 'Content',
                'type' => 'closure',
                'function' => function ($entry) {
                    return substr($entry->content, 0, 100);
                },
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('content', 'like', '%' . $searchTerm . '%');
                }
            ]
        ]);

        $this->crud->addFilter([
            'name' => 'filter_user_id',
            'type' => 'select2_ajax',
            'label' => 'User',
            'placeholder' => 'Pick a user'
        ],
            url('admin/comment/ajax-user-options'), // the ajax route
            function ($value) {
                if ($value) { //Bug's backpack
                    $this->crud->with('users')->when($value, function (Builder $query, $value) {
                        return $query->whereHas('users', function (Builder $query) use ($value) {
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
                    $this->crud->with('documents')->when($value, function (Builder $query, $value) {
                        return $query->whereHas('documents', function (Builder $query) use ($value) {
                            $query->where('document_id', $value);
                        });
                    });
                }
            });
    }


    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(CommentRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    public function userOptions(Request $request)
    {
        $term = $request->input('term');
        return User::where('name', 'like', '%' . $term . '%')->get()->pluck('name', 'id');
    }
    public function documentOptions(Request $request)
    {
        $term = $request->input('term');
        return Document::where('title', 'like', '%' . $term . '%')->get()->pluck('title', 'id');
    }
}
