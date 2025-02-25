<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Category;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::with('post')->get();
        return view('topics.listTopics', compact('topics'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('topics.createTopic', ['categories' => $categories, 'tags' => $tags]);

    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif',
            'status' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'tags' => 'nullable|array', 
            'tags.*' => 'exists:tags,id', 
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = uniqid() . '-' . $file->getClientOriginalName();
            $imagePath = $file->storeAs('uploads', $fileName);
        }

        $topic = Topic::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'category_id' => $request->category,
        ]);

        $topic->post()->create([
            'user_id' => $userId,
            'image' => $imagePath ?? null,
        ]);

        if ($request->has('tags')) {
            $topic->tags()->attach($request->tags); 
        }

        return redirect()->route('viewTopic')->with('success', 'Tópico criado com sucesso!');
    }


    public function edit($id)
    {
        $topic = Topic::with('post')->findOrFail($id);
        $categories = Category::all();
        return view('topics.editTopic', compact('topic', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);


        $topic->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'category_id' => $request->category,
        ]);

        if ($request->has('tags')) {
            $topic->tags()->sync($request->tags); 
        }

        return redirect()->route('viewTopic')->with('success', 'Tópico atualizada com sucesso!');
    }


    public function destroy($id)
    {
        $topic = Topic::with('post')->findOrFail($id);

        if ($topic->post->image && $topic->post->image !== 'uploads/defaultPhoto.jpg') {
            Storage::delete($topic->post->image);
        }

        $topic->comments()->delete();
        $topic->post()->delete();
        $topic->delete();

        return redirect()->route('viewTopic')->with('success', 'Tópico deletado com sucesso!');
    }
    public function welcome()
    {
        $topics = Topic::with(['post.user', 'comments.user', 'tags', 'category'])->latest()->get();
        return view('welcome', compact('topics'));
    }
}
