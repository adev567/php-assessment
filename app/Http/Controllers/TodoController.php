<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $getSearchvalue = $request->search;
        if($getSearchvalue){
            $todoByUser = Todo::where('user_id',Auth::user()->id)->where('name','LIKE','%'.$getSearchvalue."%")->paginate(10);   // Todo list based on name 10 per page
        }
        else{
            $todoByUser = Todo::where('user_id',Auth::user()->id)->paginate(10);  // Todo list 10 per page
        }
        return response()->json(['todo' => $todoByUser]);
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
            //store todo data in storage
            $validator = Validator::make($request->all(), [ 
                'name' => 'required', 
                'description' => 'required', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 500);            
            }
            $createTodo = Todo::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            if($createTodo){
                return response()->json(['success'=> 'Todo created successully'],200);
            }
            else{
                return response()->json(['error'=> 'Failed to create todo'],500);
            }        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //show specific todo information
        $getTodo = Todo::find($id);
        return response()->json(['todo' => $getTodo]);
        
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
        //find specific todo and update resource in storage
        $updateTodo = Todo::find($id);
        $updateTodo->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        if($updateTodo){
            return response()->json(['success'=> 'Todo updated successully'],200);
        }
        else{
            return response()->json(['error'=> 'Failed to update todo'],500);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find specific todo and remove resource from storage
        $deleteTodo = Todo::find($id);
        $deleteTodo->delete();
        if($deleteTodo){
            return response()->json(['success'=> 'Todo deleted successully'],200);
        }
        else{
            return response()->json(['error'=> 'Failed to delete todo'],500);
        }        
    }
}