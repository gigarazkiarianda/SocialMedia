<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;


class DashboardController extends Controller
{
    /**
     * Display the dashboard with user biodata.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $followingIds = Auth::user()->following()->pluck('following_id');
        $biodata = Biodata::where('user_id', $user->id)->first();
        $posts = Post::whereIn('user_id', $followingIds)->orWhere('user_id', Auth::id())->latest()->get();

        return view('dashboard', compact('biodata', 'posts', ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('biodata.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'birth_place' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $biodata = new Biodata();
    $biodata->user_id = Auth::id();
    $biodata->full_name = $request->input('full_name');
    $biodata->birth_date = $request->input('birth_date');
    $biodata->birth_place = $request->input('birth_place');

    if ($request->hasFile('photo')) {
        $fileName = time().'_'.$request->file('photo')->getClientOriginalName();
        $filePath = $request->file('photo')->storeAs('uploads', $fileName, 'public');
        $biodata->photo = $filePath;
    }

    $biodata->save();

    return redirect()->route('dashboard')->with('success', 'Biodata has been added successfully.');
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $biodata = Biodata::where('user_id', Auth::id())->findOrFail($id);
        return view('biodata.edit', compact('biodata'));
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
        $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $biodata = Biodata::where('user_id', Auth::id())->findOrFail($id);
        $biodata->full_name = $request->input('full_name');
        $biodata->birth_date = $request->input('birth_date');
        $biodata->birth_place = $request->input('birth_place');

        if ($request->hasFile('photo')) {
            $fileName = time().'_'.$request->file('photo')->getClientOriginalName();
            $filePath = $request->file('photo')->storeAs('uploads', $fileName, 'public');
            $biodata->photo = $filePath;
        }

        $biodata->save();

        return redirect()->route('dashboard')->with('success', 'Biodata updated successfully.');
    }
}
