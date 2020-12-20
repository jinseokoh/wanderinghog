<?php

namespace App\Http\Controllers\Admin\Api\Quotes;

use App\Handlers\QuoteHandler;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
    /**
     * @var QuoteHandler
     */
    private $quoteHandler;

    public function __construct(QuoteHandler $quoteHandler)
    {
        $this->middleware('auth:admin');
        $this->quoteHandler = $quoteHandler;
    }

    /**
     * not being used at this time
     *
     * @param int $id
     * @param Request $request
     * @return QuoteResource
     */
    public function update(int $id, Request $request)
    {
        $quote = Quote::find($id);

        return new QuoteResource($quote);
    }
}
