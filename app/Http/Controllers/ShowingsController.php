<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Showing;
use \App\Repositories\ShowingRepository;

class ShowingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $showingRepository;

    public function __construct(ShowingRepository $showingRepository){
        $this->showingRepository = $showingRepository;
    }
    public function index()
    {
        $showings = Showing::all();
        $showings->each(function($showing){
            $movie = $showing->Movie()->value('title');
            $showing->movieTitle = $movie;
        });

        return $showings;
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
        $showingTime = $request->get('showingTime');
        $showingEndTime = $request->get('showingEndTime');
        $showing = null;
        if(count($this->showingRepository->findShowingsbyDate($showingTime, $showingEndTime))==0){
            $showing = Showing::create($request->all());
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Date already taken!',
            ], 400);
        }

        if($showing){
            return response()->json([
                'success' => true,
                'message' => 'Showing created!',
                'data' => $showing
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $showing = Showing::find($id);
        $reservations = $showing->reservations;
        $movie = $showing->Movie()->value('title');
        $showing->movieTitle = $movie;

        if($showing){
            return response()->json([
                'success' => true,
                'message' => 'Showing has found!',
                'data' => $showing,
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Showing not found!',
            ], 404);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $showing = Showing::destory($id);

        if($showing){
            return response()->json([
                'success' => true,
                'message' => 'Showing has deleted',
                'data' => $showing
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Showing has not deleted',
            ], 400);
        }
    }
}
