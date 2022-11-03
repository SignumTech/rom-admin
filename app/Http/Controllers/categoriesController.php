<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Image;
class categoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "cat_name" => "required | string",
            "cat_type" => "required | string",
            "parent_id" => "required | integer"
        ]);

        $category = new Category;
        $category->cat_name = $request->cat_name;
        $category->cat_type = $request->cat_type;
        if($request->cat_type == "PARENT")
        {
            $category->parent_id = 0;
        }
        else
        {
            $category->parent_id = $request->parent_id;
            $category->cat_image = 'placeholder.jpg';
        }
        $category->save();

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "cat_name" => "required | string",
            "cat_type" => "required | string",
            "parent_id" => "required | integer"
        ]);

        $category = Category::find($id);
        $category->cat_name = $request->cat_name;
        $category->cat_type = $request->cat_type;
        if($request->cat_type == "PARENT")
        {
            $category->parent_id = 0;
        }
        else
        {
            $category->parent_id = $request->parent_id;
            $category->cat_image = 'placeholder.jpg';
        }
        $category->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getMainCategories(){

        $categories = Category::where('cat_type', 'PARENT')->get();
        foreach($categories as $cat){
            $cat->items = $this->categoryItemCount($cat->id);
        }
        return $categories;
    }

    public function getSubCategories(){

        $categories = Category::where('cat_type', 'CHILD')->get();
        
        foreach($categories as $cat){
            $count = Product::where('cat_id', $cat->id)->count();
            $cat->items = $count + $this->categoryItemCount($cat->id);
            $cat->parent_name = Category::where('id', $cat->parent_id)->select('cat_name')->first()->cat_name;
        }
        return $categories;
    }

    public function categoryItemCount($id){
        $count = 0;
        $category = Category::where('parent_id', $id)->get();
        if(count($category)>0){
            foreach($category as $cat){
                $count = $count + Product::where('cat_id', $cat->id)->count();
                $this->categoryItemCount($cat->id); 
            }
        }
        return $count;
    }

    public function showSubCategories($id){
        $categories = Category::where('parent_id', $id)->get();
        return $categories;
    }

    public function uploadSubPic(Request $request){
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2084',
            'cat_id' => 'required'
        ]);
        //dd($request->cat_id);
        if($request->hasFile('photo')){
            
            //Get filename with the extention
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $thumbnailImage = Image::make($request->file('photo'));
            
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('photo')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload Image
            //$thumbnailPath = public_path().'\storage\settings\\';
            //$thumbnailPath = 'C:\wamp64\www\roadmap\public\storage\settings\\';
            $thumbnailPath = '/home/signumdevcom/rom/storage/app/public/settings/';
            /*$thumbnailImage->resize(null, 320, function ($constraint){
                $constraint->aspectRatio();
            });*/
            $thumbnailImage->save($thumbnailPath.$fileNameToStore);

            $subCat = Category::find($request->cat_id);
            $subCat->cat_image = $fileNameToStore;
            $subCat->save();

            return $subCat; 
        }
        else{
            return response(422, "No file");
        }

    }

    public function chooseSubCategories(){

        $data = [];
        $index = 0;
        $parents = Category::where('cat_type', 'PARENT')->get();
        foreach($parents as $parent){
            $data[$index]['parent'] = $parent->cat_name;
            $data[$index]['children'] = Category::where('parent_id', $parent->id)->get();
            $index++;
        }

        return $data;
    }

    public function getCatByName($name){
        $cat = Category::where("cat_name", $name)->first();
        return $cat;
    }

}
