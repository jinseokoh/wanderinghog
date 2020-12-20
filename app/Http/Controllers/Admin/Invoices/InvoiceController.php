<?php

namespace App\Http\Controllers\Admin\Invoices;

use App\Handlers\InvoiceHandler;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * @var InvoiceHandler
     */
    private $invoiceHandler;

    public function __construct(InvoiceHandler $invoiceHandler)
    {
        $this->middleware('auth:admin');
        $this->invoiceHandler = $invoiceHandler;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $invoices = $this->invoiceHandler->fetch($request);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $id, Request $request)
    {
        $order = new InvoiceResource(Invoice::find($id));
        $page = (int) $request->get('page', 1);

        return view('invoices.edit', [
            'page' => $page,
            'order' => json_encode($order)
        ]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $invoice = $this->invoiceHandler->findById($id);

        return view('invoices.show', compact('invoice'));
    }
}
