<?php

namespace App\Http\Controllers\Api;

use App\Models\API\Todo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Todo::latest()->get();
        return $this->generateResult(
            $data,
            1,
            200
        );
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
        $todo = Todo::create([
            'user_id' => auth()->id(),
            'title' => $request->json('title'),
            'descriptions' => $request->json('descriptions'),
        ]);
        $response = $this->generateResult(
            $todo,
            1,
            201
        );
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::find($id);
        if (is_null($todo)) return $this->generateResult(
            "data not found",
            2,
            404
        );
        return $this->generateResult(
            $todo,
            1,
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        $todo = Todo::find($id);
        if (is_null($todo)) return $this->generateResult(
            "data not found",
            2,
            404
        );
        $todo->title = $request->json('title');
        $todo->descriptions = $request->json('descriptions');
        $todo->save();
        return $this->generateResult(
            $todo,
            1,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $todo = Todo::find($id);
        if (is_null($todo)) return $this->generateResult(
            null,
            2,
            404
        );
        $todo->title = $request->json('title');
        $todo->descriptions = $request->json('descriptions');
        $todo->save();
        return $this->generateResult(
            $todo,
            1,
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $todo = Todo::find($id);
        if (is_null($todo)) return $this->generateResult(
            null,
            2,
            404
        );
        $todo->delete();
        return $this->generateResult(
            null,
            1,
            200
        );
    }
}
