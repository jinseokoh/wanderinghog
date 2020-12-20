<?php

namespace App\Http\Controllers\Admin\Inquiries;

use App\Http\Controllers\Controller;
use App\Handlers\AnswerHandler;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * @var InqueryHandler
     */
    private $inquiryHandler;

    public function __construct(
        InqueryHandler $inquiryHandler
    ) {
        $this->middleware('auth:admin');
        $this->inquiryHandler = $inquiryHandler;
    }

    /**
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $inquiries = $this->inquiryHandler->fetch($request);

        return view('inquiries.index', compact('inquiries'));
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $inquiry = $this->inquiryHandler->findById($id);

        return view('inquiries.show', compact('inquiry'));
    }
}
