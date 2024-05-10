<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class postController extends Controller
{
    private array $rules = [
        'title' => 'required|string|max:100',
        'content' => 'required|string|max:300',
    ];

    private array $errorMessages = [
        'required' => 'El campo :attribute es obligatorio.',
        'string' => 'Caracteres no validos.',
        'max' => 'El campo :attribute no debe ser mayor a :max caracteres.',
    ];

    public function apiIndex()
    {
        $posts = Post::all();
        if ($posts->isEmpty()) {
            $data = [
                'message' => 'No se encontraron publicaciones.',
                'status' => 404
            ];
            Log::info('[postController] apiIndex() - Se ha llamado a esta API y no se han encontrado publicaciones');
            return response()->json($data['message'], $data['status']);
        }
        $cont = $posts->count();
        $data = [
            'posts' => $posts,
            'status' => 200
        ];
        Log::info('[postController] apiIndex() - Se ha llamado a esta API y se han encontrado ' . $cont . ' publicaciones');
        return response()->json($data['posts'], $data['status']);
    }

    public function apiIndexId($id)
    {
        $post = Post::where('id', $id)->first();
        if ($post == null) {
            $data = [
                'message' => 'No se encontró esa publicación.',
                'status' => 404
            ];
            Log::info('[postController] apiIndexId() - Se ha llamado a esta API y no se ha encontrado la publicación con id ' . $id);
            return response()->json($data['message'], $data['status']);
        } else {
            $data = [
                'post' => $post,
                'status' => 200
            ];
            Log::info('[postController] apiIndexId() - Se ha llamado a esta API y se ha encontrado la publicación con id ' . $id);
            return response()->json($data['post'], $data['status']);
        }
    }

    public function apiCreate(Request $request)
    {
        $validated = $request->validate($this->rules);
        if (!$validated) {
            $data = [
                'message' => 'Los datos introducidos no cumplen los requisitos.',
                'status' => 422
            ];
            Log::info('[postController] apiCreate() - Se ha intentado crear una publicación pero no cumple los requisitos ', array($request));
            return response()->json($data['message'], $data['status']);
        } else {
            Post::create([
                'user_id' => 1,
                'title' => $validated['title'],
                'content' => $validated['content'],
            ]);
            Log::info('[postController] apiCreate() - Se ha creado una nueva publicación con id ', array(Post::latest()->first()->id));
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();
        $validated = $request->validate($this->rules);
        if ($post == null) {
            $data = [
                'message' => 'No se encontró esa publicación.',
                'status' => 404
            ];
            Log::info('[postController] apiUpdate() - Se ha llamado a esta API y no se ha encontrado la publicación con id ' . $id);
            return response()->json($data['message'], $data['status']);
        } else {
            if (!$validated) {
                $data = [
                    'message' => 'Los datos introducidos no cumplen los requisitos.',
                    'status' => 422
                ];
                Log::info('[postController] apiUpdate() - Se ha intentado actualizar la publicación ', array($id), ' pero no cumple los requisitos ', array($request));
                return response()->json($data['message'], $data['status']);
            } else {
                $post->update($validated);
                Log::info('[postController] apiUpdate() - Se ha actualizado la publicación con id ' . $id);
            }
        }
    }

    public function apiDelete(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();
        if ($post == null) {
            $data = [
                'message' => 'No se encontró esa publicación.',
                'status' => 404
            ];
            Log::info('[postController] apiDelete() - Se ha llamado a esta API y no se ha encontrado la publicación con id ' . $id);
            return response()->json($data['message'], $data['status']);
        } else {
            $post->delete();
            Log::info('[postController] apiDelete() - Se ha eliminado la publicación con id ' . $id);
        }
    }

    public function index(Request $request): View
    {
        $posts = Post::myPosts($request->filtro)->get();
        return view('post.index', ['posts' => $posts]);
    }

    public function create(): View
    {
        return view('create_edit');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules, $this->errorMessages);

        Post::create([
            'user_id' => auth()->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        $user = auth()?->user();

        session()->flash('message', 'Publicación creada correctamente');

        Log::info('[postController] store - post [' . Post::latest()->first()->id . '] creada por [' . $user->id . '] ' . $user->name);
        return redirect()->route('post.index');
    }

    public function edit(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect(route('post.index'))->with('error', 'No puedes editar una publicación que no es tuya');
        }

        return view('post.create_edit')->with('post', $post);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }

        $validated = $request->validate($this->rules, $this->errorMessages);

        $post->update($validated);

        session()->flash('message', 'Post editado correctamente');

        $usuario = auth()?->user();

        Log::info('[postController] update - Post [' . $post->id . '] editado por [' . $usuario->id . '] ' . $usuario->name);

        return redirect()->route('post.index');
    }

    public function delete(Request $request, Post $post): RedirectResponse
    {
        if ($request->user()->cannot('delete', $post)) {
            return redirect(route('post.index', $post))->with('error', 'No puedes borrar una publicación que no es tuya');
        }

        $id_post = Post::latest()->first()->id;

        $post->delete();

        session()->flash('message', 'Post eliminado correctamente');

        $usuario = auth()?->user();

        Log::info('[postController] delete - Post [' . $id_post . '] eliminado por [' . $usuario->id . '] ' . $usuario->name);

        return redirect()->route('post.index');
    }
}
