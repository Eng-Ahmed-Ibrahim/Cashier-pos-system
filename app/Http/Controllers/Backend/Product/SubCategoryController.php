<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Category;
use App\Trait\FileHandler;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // abort_if(!auth()->user()->can('sub_category_view'), 403);
        if ($request->ajax()) {
            $subCategories = SubCategory::latest()->get();
            return DataTables::of($subCategories)
                ->addIndexColumn()
                ->addColumn('image', fn($data) => '<img src="' . asset('storage/' . $data->image) . '" loading="lazy" alt="' . $data->name . '" class="img-thumb img-fluid" onerror="this.onerror=null; this.src=\'' . asset('assets/images/no-image.png') . '\';" height="80" width="60" />')
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('category', fn($data) => $data->category->name ?? '-')
                ->addColumn('status', fn($data) => $data->status
                    ? '<span class="badge bg-primary">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>')
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                    <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
                    <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="' . route('backend.admin.sub_categories.edit', $data->id) . '" ' . ' >
                    <i class="fas fa-edit"></i> Edit
                </a> <div class="dropdown-divider"></div>
<form action="' . route('backend.admin.sub_categories.destroy', $data->id) . '"method="POST" style="display:inline;">
                   ' . csrf_field() . '
                    ' . method_field("DELETE") . '
<button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure ?\')"><i class="fas fa-trash"></i> Delete</button>
                  </form>
                  </div>';
                })
                ->rawColumns(['image', 'name', 'status', 'action'])
                ->toJson();
        }
        return view('backend.sub_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->can('sub_category_create'), 403);
        $categories = Category::where('status', true)->get();
        return view('backend.sub_categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('sub_category_create'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sub_category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);
        $subCategory = SubCategory::create($request->except('sub_category_image'));
        if ($request->hasFile("sub_category_image")) {
            $subCategory->image = $this->fileHandler->fileUploadAndGetPath($request->file("sub_category_image"), "/public/media/sub_categories");
            $subCategory->save();
        }

        return redirect()->route('backend.admin.sub_categories.index')->with('success', 'Sub Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(!auth()->user()->can('sub_category_update'), 403);

        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::where('status', true)->get();

        return view('backend.sub_categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->can('sub_category_update'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sub_category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);
        $subCategory = SubCategory::findOrFail($id);
        $oldImage = $subCategory->image;
        $subCategory->update($request->except('sub_category_image'));
        if ($request->hasFile("sub_category_image")) {
            $subCategory->image = $this->fileHandler->fileUploadAndGetPath($request->file("sub_category_image"), "/public/media/sub_categories");
            $subCategory->save();
            $this->fileHandler->secureUnlink($oldImage);
        }

        return redirect()->route('backend.admin.sub_categories.index')->with('success', 'Sub Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(!auth()->user()->can('sub_category_delete'), 403);
        $subCategory = SubCategory::findOrFail($id);
        if ($subCategory->image != '') {
            $this->fileHandler->secureUnlink($subCategory->image);
        }
        $subCategory->delete();
        return redirect()->back()->with('success', 'Sub Category Deleted Successfully');
    }
}
