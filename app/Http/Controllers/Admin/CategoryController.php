<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index()
    {
        Session::put('page', 'categories');
        $categories = Category::with('organization','parent')->get();

        // Set Admin/Subadmins Permissions
        $categoriesModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])->count();
        $categoriesModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $categoriesModule['view_access'] = 1;
            $categoriesModule['edit_access'] = 1;
            $categoriesModule['full_access'] = 1;
        } elseif ($categoriesModuleCount == 0) {
            $message = 'This Featu is retriced for you!';

            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $categoriesModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])->first()->toArray();
        }

        return view('admin.categories.indexExpandable', compact('categories', 'categoriesModule'));
    }

     public function create()
    {
        $departments = Organization::all();
        $parents = Category::all();
        return view('admin.categories.form', compact('departments','parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'=>'required|string|max:255',
            'organization_id'=>'required|exists:organizations,id',
            'parent_id'=>'nullable|exists:categories,id',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success','Category created successfully.');
    }

    public function edit(Category $category)
    {
        $departments = Organization::all();
        $parents = Category::where('id','!=',$category->id)->get();
        return view('admin.categories.edit', compact('category','departments','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'organization_id'=>'required|exists:organizations,id',
            'parent_id'=>'nullable|exists:categories,id',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success','Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted successfully.');
    }

    /**
     * Show the form for add and editing the specified resource.
     */
    public function edits(Request $request, $id = null)
    {
        // dd($id);
        $getCategories = Category::getCategories();
        if ($id == '') {
            $title = 'Tambah Kategori';
            $category = new Category;
            $message = 'Kategori berhasil ditambahkan';
        } else {
            $title = 'Ubah Kategori';
            $category = Category::find($id);
            $message = 'Kategori berhasil diperbaharui';
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

            if ($id == '') {
                $categoryCount = Category::where('category_name', $data['category_name'])->count();
                if ($categoryCount > 0) {
                    return redirect()->back()->with('error_message', 'Kategori '.$data['category_name'].' sudah ada !');
                }
            }
            if ($id == '') {
                $rules = [
                    'category_name' => 'required|string|max:255',
                    'organization_id'=>'required|exists:organizations,id',
                    'parent_id'=>'nullable|exists:categories,id',
                ];
            } else {
                $rules = [
                    'category_name' => 'required|string|max:255',
                    'organization_id'=>'required|exists:organizations,id',
                    'parent_id'=>'nullable|exists:categories,id',
                ];
            }
            $customMessages = [
                'category_name.required' => 'Judul harus diisi',
                'organization_id.required' => 'Organisasi harus dipilih',
                'organization_id.exists' => 'Organisasi tidak valid',
                'parent_id.exists' => 'Kategori induk tidak valid',
            ];

            $this->validate($request, $rules, $customMessages);

            if ($request->hasFile('category_image')) {
                $file = $request->file('category_image');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = $filename.'-'.date('his').'.'.$extension;

                $destinationPath = 'front/images/categories'.'/';
                $file->move($destinationPath, $fileName);
                $data['category_image'] = $fileName;
            } elseif (! empty($data['current_image'])) {
                $data['category_image'] = $data['current_image'];
            } else {
                $data['category_image'] = '';
            }
            $category->category_name = $data['category_name'];
            $category->category_image = $data['category_image'] ?? null;
            $category->url = $data['url'];
            $category->parent_id = $data['parent_id'] ?? null;
            $category->status = 1;
            $category->save();

            return redirect('admin/categories')->with('success_message', $message);
        }

        return view('admin.categories.add_edit_category')->with(compact('title', 'category', 'getCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, Category $category)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Category::where('id', $data['category_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
        }
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Kategori Berhasil dihapus');
    }

    public function deleteCategoryImage($id)
    {
        $categoryImage = Category::select('category_image')->where('id', $id)->first();
        $category_image_path = 'front/images/categories/';
        if (file_exists($category_image_path.$categoryImage->category_image)) {
            unlink($category_image_path.$categoryImage->category_image);
        }
        Category::where('id', $id)->update(['category_image' => '']);

        return redirect()->back()->with('success_message','Foto Kategori  Berhasil dihapus');
    }
}
