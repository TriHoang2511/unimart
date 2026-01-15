<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
 
    public function index(Request $request)
    {
        $list_status = [
        'all' => 'Tất cả',
        'publish' => 'Công khai',
        'pending' => 'Chờ duyệt',
        'trash' => 'Thùng rác'
     ];

    $status = $request->status ?? 'all';
    $keyword = $request->keyword;

    $query = Post::with('category','author');

    // FILTER STATUS
    if ($status == 'trash') {
        $query->where('status', 'trash');
    } elseif ($status != 'all') {
        $query->where('status', $status);
    }

    // SEARCH
    if ($keyword) {
        $query->where('title', 'LIKE', "%$keyword%");
    }

    // COUNTS
    $count = [
        'all' => Post::count(),
        'publish' => Post::where('status','publish')->count(),
        'pending' => Post::where('status','pending')->count(),
        'trash' => Post::where('status','trash')->count(),
    ];

    $posts = $query->orderBy('id','desc')->paginate(10);

    return view('admin.post.index', compact(
        'posts',
        'list_status',
        'count'
    ));
    }

    public function create()
    {
     $categories = PostCategory::all();
     return view('admin.post.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required',
        'status'  => 'required',
        'post_category_id' => 'required|exists:post_categories,id'
        ]);

        Post::create([
        'title'   => $request->title,
        'excerpt' => $request->excerpt,
        'content' => $request->content,
        'status'  => $request->status,
        'user_id' => Auth::id(),
        'post_category_id' => $request->post_category_id
        ]);

     return redirect('/admin/post')->with('success', 'Đã đăng bài viết');
    }


    public function edit($id)
    {
     $post = Post::findOrFail($id);
     $categories = PostCategory::all();

     return view('admin.post.edit', compact('post','categories'));
    }

        public function update(Request $request, $id)
    {
        $request->validate([
           'title' => 'required|string|max:255',
         'content' => 'required',
         'status' => 'required',
         'post_category_id' => 'required|exists:post_categories,id'
        ]);

        $post = Post::findOrFail($id);

        $post->update([
          'title' => $request->title,
          'excerpt' => $request->excerpt,
          'content' => $request->content,
           'status' => $request->status,
          'post_category_id' => $request->post_category_id
        ]);

         return redirect('/admin/post')->with('success','Cập nhật thành công');
    }


    public function destroy($id)
    {
     $post = Post::findOrFail($id);
     $post->update(['status' => 'trash']);

        return redirect()->back()->with('success','Đã chuyển vào thùng rác');
    }


}
