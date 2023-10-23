<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\Category;
use Yajra\DataTables\DataTables;
use DB;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function getData(Request $request)
    {
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
        
            $tasks = Task::with('category')
                ->select('tasks.id', 'tasks.name', 'tasks.category_id', 'categories.category_name')
                ->join('categories', 'tasks.category_id', '=', 'categories.id')
                ->where('categories.category_name', 'like',  $searchValue . '%')
                ->get();

                
        } else {
            $tasks = Task::with('category')
                ->select('tasks.id', 'tasks.name', 'tasks.category_id', 'categories.category_name')
                ->join('categories', 'tasks.category_id', '=', 'categories.id')
                ->get();
        }
     
        return DataTables::of($tasks)
        ->skipAutoFilter()
        ->make(true);
             
       


    
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'category_id' => 'required'
            ]);


            Task::create($validatedData);

            return response()->json(['message' => 'Task Added Successfully', 'status' => 'success', 'title' => 'Added']);

        }
        catch(\Exception $e){
            return response()->json(['message' => 'Error While Updating','status' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        if (!$task) {
            return redirect()->route('index')->with('error', 'Error Occured while fetching data.');
        } else {
            return response()->json(['task' => $task, 'status' => 'success']);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'category_id' => 'required'
            ]);

            $task = Task::findOrFail($id);
            $task->update($validatedData);
            return response()->json(['message' => 'Task Updated Successfully', 'status' => 'success', 'title' => 'Updated']);

        }
        catch(\Exception $e){
            return response()->json(['message' => 'Error While Updating','status' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete(); // Then, delete the user record
            return response()->json(['message' => 'task deleted successfully', 'status' => 'success']);
        } else {
            return response()->json(['message' => 'Task not found', 'status' => 'error']);
        }
    }
}