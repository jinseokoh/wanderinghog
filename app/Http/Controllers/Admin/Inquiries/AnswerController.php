<?php

namespace App\Http\Controllers\Admin\Inquiries;

use App\Handlers\AnswerHandler;
use App\Http\Controllers\Controller;
use App\Handlers\AnswerHandler;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnswerController extends Controller
{
    private $answerHandler;
    private $inquiryHandler;

    public function __construct(
        AnswerHandler $answerHandler,
        AnswerHandler $inquiryHandler
    ) {
        $this->middleware('auth:admin');

        $this->inquiryHandler = $inquiryHandler;
        $this->answerHandler = $answerHandler;
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function edit(int $id, int $aid)
    {
        $inquiry = $this->inquiryHandler->findById($id);
        $answer = $this->answerHandler->findById($aid);

        return view('inquiries.edit', compact('inquiry', 'answer'));
    }

    public function store(int $id, Request $request)
    {
        $body = $request->get('body');
        $admin = $request->user('admin');
        $adminId = $admin->id;

        $this->answerHandler->store($id, $adminId, $body);

        return redirect('/admin/inquiries/'.$id)->with('flash', 'success');
    }

    public function update(int $id, int $aid, Request $request)
    {
        $body = $request->get('body');

        $this->answerHandler->update($id, $aid, $body);

        return redirect('/admin/inquiries/'.$id)->with('flash', 'success');
    }
}
