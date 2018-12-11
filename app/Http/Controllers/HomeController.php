<?php

namespace App\Http\Controllers;

use App\Archive;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archives = Archive::all();
        return view('home')->with("archives", $archives);
    }

    public function create(Request $request)
    {
        $filename = $request->file("file")->getClientOriginalName();

        Archive::create([
            "filename" => $filename,
            "url" => "http://localhost/storage/" . $filename
        ]);

        (new PublicityAMQP())->publisher([
            "Filename" => $filename,
            "Action" => "post",
        ]);

        $mails = User::all()->map(function ($item, $key) {
            return ["FullName" => $item->name, "Email" => $item->email];
        });

        (new MailingAMQP())->publisher([
            'Action' => "post",
            "emails" => $mails->all()
        ]);

        return Redirect::route('home')
            ->with('status', "Archive ".$filename." created");
    }

    public function delete($id)
    {
        $archive = Archive::find($id);

        $archive->delete();

        (new PublicityAMQP())->publisher([
            "Action" => "delete",
            "filename" => $archive->filename,
        ]);

        $mails = User::all()->map(function ($item, $key) {
            return ["FullName" => $item->name, "Email" => $item->email];
        });

        (new MailingAMQP())->publisher([
            'Action' => "delete",
            "emails" => $mails->all()
        ]);

        return Redirect::route('home')
            ->with('status', "Archive ".$archive->filename." deleted");
    }
}
