<?php

namespace App\Http\Controllers\Admin\Questions;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $slug = $request->input('slug') ?: 'daily';

        $questions =
            Question::with('parent')
                ->withCount('answers')
                ->where('depth', 2)
                ->whereIn('parent_id', function ($query) use ($slug) {
                    $query->select('id')
                        ->from('questions')
                        ->where('slug', $slug);
                })
                ->paginate();

        return view('questions.index', compact('questions'));
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $question = Question::with([
            'parent',
            'answers.user'
        ])
            ->where('id', $id)
            ->first();

        return view('questions.show', compact('question'));
    }

    /**
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('questions.create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $id)
    {
        $question = Question::with('parent')
            ->where('id', $id)
            ->first();

        return view('questions.edit', compact('question'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'name' => 'required|string',
        ]);

        $slug = $request->input('slug');
        $name = $request->input('name');
        $parent = Question::where('slug', $slug)->firstOrFail();

        $node = Question::make([
            'name' => $name,
            'depth' => 2
        ]);
        $parent->appendNode($node);

        return redirect("/admin/questions?slug={$slug}")->with('flash', 'success');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(int $id, Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'name' => 'required|string',
        ]);

        $slug = $request->input('slug');
        $name = $request->input('name');
        $parent= Question::where('slug', $slug)->firstOrFail();

        $node = Question::find($id);
        $node->update([
            'name' => $name,
            'parent_id' => $parent->id,
        ]);

        return redirect("/admin/questions?slug={$slug}")->with('flash', 'success');
    }

    public function destroy($id)
    {
        //
    }
}
