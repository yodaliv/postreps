<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PaymentService
{
    protected $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllOrderBy(string $orderBy, string $sortOrder = 'asc'): EloquentCollection
    {
        $getAllOrderBy = $this->model;

        if ($orderBy) {
            $getAllOrderBy = $getAllOrderBy->orderBy($orderBy, $sortOrder);
        }

        $getAllOrderBy = $getAllOrderBy->get();

        return $getAllOrderBy;
    }

    public function findById(int $id): Payment
    {
        return $this->model->findOrFail($id);
    }
    public function getCustomerCards($customer_id)
    {
        $cards = Payment::where('customer_id', $customer_id)->get()->map(function ($p) {
            $cardLastNumber = decrypt($p->card_last_four_digits);
            $p->lastNumber = $cardLastNumber;
            $p->cardNumber = "XXXX-XXXX-XXXX-$cardLastNumber exp$p->expdate";
            return $p;
        })
            // ->groupBy('lastNumber')
        ;
        return $cards;
    }
}
