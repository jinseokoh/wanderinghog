<?php

namespace App\Handlers;

use App\Http\Dtos\InvoiceStoreDto;
use App\Models\Invoice;
use App\Models\Quote;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class InvoiceHandler
{
    /**
     * find a model
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id)
    {
        return Invoice::with(['receipts'])
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * fetch all relevant products
     *
     * @param int $id
     * @return LengthAwarePaginator
     */
    public function fetch(Request $request): LengthAwarePaginator
    {
        return Invoice::with(['receipts'])
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    /**
     * 관리자가 인보이스 생성
     * (Invoice = 견적서 = /확약서, 내용이 확정적이다는 것일뿐 동일한 모델 사용)
     * @param int $quoteId
     * @param InvoiceStoreDto $dto
     */
    public function create(int $quoteId, InvoiceStoreDto $dto)
    {
        $invoice = Invoice::firstOrNew([ 'quote_id' => $quoteId ]);

        $invoice->pax = $dto->getPax();
        $invoice->total = $dto->getTotal();
        $invoice->costs = $dto->getCosts();
        $invoice->flights = $dto->getFlights();
        $invoice->accommodations = $dto->getAccommodations();
        $invoice->opening = $dto->getOpening();
        $invoice->ending = null;
        $invoice->deposit = $dto->getTotal() * 0.1;
        $invoice->save();
    }

    /**
     * 관리자가 확약서 생성
     * (Invoice = 계약서 = 확약서, 내용이 확정적이다는 것일뿐 동일한 모델 사용)
     * @param int $quoteId
     * @param InvoiceStoreDto $dto
     */
    public function update(int $quoteId, InvoiceStoreDto $dto)
    {
        $quote = Quote::with(['invoice'])->findOrFail($quoteId);

        $quote->invoice->update([
            'pax' => $dto->getPax(),
            'total' => $dto->getTotal(),
            'costs' => $dto->getCosts(),
            'flights' => $dto->getFlights(),
            'accommodations' => $dto->getAccommodations(),
            'opening' => $dto->getOpening(),
            'ending' => $dto->getEnding(),
            'deposit' => $dto->getTotal() * 0.1
        ]);
    }
}
