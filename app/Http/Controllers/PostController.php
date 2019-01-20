<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("posts.index");

    }
    public function getPosts()
    {
     $posts = Post::select('id', 'title', 'content');
     return Datatables::of($posts)
            ->addColumn('action', function($post){
                return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$post->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a><a href="#" class="btn btn-xs btn-danger delete" id="'.$post->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            })
            ->make(true);
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
        $validation = Validator::make($request->all(), [
        'title' => 'required|min:4',
        'content' => 'required|string'
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $post = new Post();
                $post->title = $request->input('title');
                $post->content = $request->input('content');
                $post->save();
                $success_output = '<div class="alert alert-success">Post Inserted</div>';
            }
            if($request->get('button_action') == 'update')
            {
                $post = Post::find($request->get('post_id'));
                $post->title = $request->get('title');
                $post->content = $request->get('content');
                $post->save();
                $success_output = '<div class="alert alert-success">Post Updated</div>';
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    public function getPost(Request $request)
    {
        //
        $id = $request->input('id');
        $post = Post::find($id);
        $output = array(
            'title'    =>  $post->title,
            'content'     =>  $post->content
        );
        echo json_encode($output);
    }


    public function delete(Request $request)
    {
        //
        $post = Post::find($request->input('id'));
        if($post->delete())
        {
            echo 'Post Deleted';
        }
    }
}
