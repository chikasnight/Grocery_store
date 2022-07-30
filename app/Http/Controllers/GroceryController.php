<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\UploadImage;
use App\Models\Grocery;
use App\Http\Resources\GroceryResource;

class GroceryController extends Controller
{
    public function createGrocery(Request $request){
        //validate request body
        $request->validate([
            'name'=>['required'],
            'price'=>['required'],
            'category'=>['required'],
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048','required'],
            

          
        ]);

        //get the image
        $image = $request->file('image');
        //$image_path = $image->getPathName();
 
        // get original file name and replace any spaces with _
        // example: ofiice card.png = timestamp()_office_card.pnp
        $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
 
        // move image to temp location (tmp disk)
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');
 
 
        //create a blog post
        $newGrocery = Grocery::create([
            'user_id'=>auth()->id(),
            'name'=> $request->name,
            'price'=> $request->price,
            'category'=> $request->category,
            'image'=> $filename,
            'disk'=> config('site.upload_disk'),
           
            
        ]);

        //dispacth job to handle image manipulation
        $this->dispatch(new UploadImage($newGrocery));

        //return cuccess response

        return response()->json([
            'success'=> true,
            'message'=>'successfully created a Grocery',
            'data' =>$newGrocery
        ]);
    }
    public function getGrocery(Request $request, $groceryId){
        $grocery = Grocery::find($groceryId);
        if(!$grocery) {
            return response() ->json([
                'success' => false,
                'message' => 'grocery not found'
            ]);
        }

        return response() ->json([
            'success'=> true,
            'message'  => 'grocery found',
            'data'   => [
                'grocery'=> new GroceryResource($grocery),
                
            ]
        ]);
    }
    public function editGrocery(Request $request, $groceryId){
        $request->validate([
            'name'=>['required'],
            'price'=>['required'],
            'category'=>['required'],
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048'],
            

        ]);
        
        $grocery = Grocery::find($groceryId);
        if(!$grocery) {
            return response() ->json([
                'success' => false,
                'message' => 'grocery not found'
            ]);

        }
        $this->authorize('update',$grocery);


        $grocery->name = $request->name;
        $grocery->price = $request->price;
        $grocery->category = $request->category;
        $grocery->image = $request->image;
        $grocery->save();
        return response() ->json([
            'success' => true,
            'message' => 'grocery updated'
        ]);
    }
    public function deleteGrocery( $groceryId){

        $grocery = Grocery::find($groceryId);
        if(!$grocery) {
            return response() ->json([
                'success' => false,
                'message' => 'grocery not found'
            ]);
        }

        

        $this->authorize('delete',$grocery);
        //delete grocery
        $grocery-> delete();

        return response() ->json([
            'success' => true,
            'message' => 'grocery deleted'
            ]); 
    } 
    public function search(Request $request){
        $task =new Grocery();
        $query =$task-> newQuery();

        if($request->has('name')){
            $query= $query->where('name', $request->name);
        
        }

        if($request->has('price')){
            $query= $query->where('price', $request->price);
        }
        if($request->has('category')){
            $query= $query->where('category', $request->category);
        }
        

        
        return response()->json([
            'success'=> true,
            'message'=>'search results found',
            'data'=> $query->get()

            
        ]);
        
    }
}
