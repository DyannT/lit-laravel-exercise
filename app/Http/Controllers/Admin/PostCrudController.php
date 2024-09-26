<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use App\Repository\User\APIPostRepositoryInterface;
use App\Traits\ImageUploadTrait;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    protected APIPostRepositoryInterface $apiPostRepository;

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\CrudPermissionTrait;
    use ImageUploadTrait;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */

    public function __construct(APIPostRepositoryInterface $apiPostRepository)
    {
        parent::__construct();
        $this->apiPostRepository = $apiPostRepository;
    }

    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');

        parent::setup();
        $this->setAccessUsingPermissions();
        $this->crud->addColumns($this->getFieldsData(TRUE));
    }

    private function getFieldsData($show = FALSE) {
        return [
            [
                'name'=> 'title',
                'label' => 'Title',
                'type'=> 'text'
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
            ],
            [
                'name' => "image",
                'label' => "Image",
                'type' => ($show ? 'view' : 'upload'),
                'view' => 'partials/image',
                'upload' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select_from_array',
                'options' => [
                    1 => 'Enable',
                    0 => 'Disable',
                ],
                'allows_null' => false,
                'default' => 1,
            ],
        ];
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->set('show.setFromDb', false);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        $this->crud->addFields($this->getFieldsData());
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function update(Request $request)
    {
        CRUD::setValidation(PostRequest::class);

        $dataUpdate = [
            'title' => request()->title,
            'description' => request()->description,
            'status' => request()->status,
        ];

        if ($request->hasFile('image')) {
            $dataUpdate['image'] = $this->uploadImage($request->file('image'));
        }

        $this->apiPostRepository->update(request()->id, $dataUpdate);

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction();
    }
}
