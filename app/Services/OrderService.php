<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Models\{Order, OrderAccessory, OrderAttachment, Zone};
// use Yajra\Datatables\Datatables;
use Yajra\DataTables\Facades\DataTables;
use DB;

class OrderService
{
    use HelperTrait;

    protected $model;
    protected $orderAccessory;
    protected $orderAttachment;

    public function __construct(Order $model, OrderAccessory $orderAccessory, OrderAttachment $orderAttachment)
    {
        $this->model = $model;
        $this->orderAccessory = $orderAccessory;
        $this->orderAttachment = $orderAttachment;
    }

    public function create(array $attributes)
    {
        $attributes['order_number'] = $this->temp_id();
        $order =  $this->model->create($attributes);
        $order_number = $this->generateOrderNumber();
        $isExists = $order->where('order_number', $order_number)->exists();
        //if exists try create new one
        while ($isExists) {
            $order_number = $this->generateOrderNumber();
        }
        $order->order_number = $order_number;
        $order->save();
        return $order;
    }

    public function generateOrderNumber()
    {
        $yearLatter = Order::getYearLatter(now()->format("Y"));
        $monthChar = $this->getMonthCharFromAlphabet((int) now()->month);
        $order_number = "I" . $yearLatter . $monthChar . mt_rand(1000, 9999);
        return $order_number;
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

    public function findById(int $id): Order
    {
        return $this->model->findOrFail($id);
    }

    public function dataTable()
    {
        $model = $this->model
            ->join('offices', 'offices.id', 'orders.office_id')
            ->join('users as office', 'office.id', 'offices.user_id')
            ->leftJoin('agents', 'agents.id', 'orders.agent_id')
            ->leftJoin('users as agent', 'agent.id', 'agents.user_id')
            ->orderBy('updated_at', "DESC")
            ->select('orders.*', 'office.name as office_name', 'agent.name as agent_name');
        $search = strtolower($_GET['search']['value']);
        $status = ["received", "incomplete", "scheduled", "completed"];
        if (!empty($search)) {
            foreach ($status as $s) {
                if (str_starts_with($s, $search)) {
                    $search = $s;
                    return Datatables::eloquent($model)->filter(function ($query) use ($search) {
                        switch ($search) {
                            case "received":
                                $query->where('orders.status', Order::STATUS_RECEIVED);
                                break;
                            case "incomplete":
                                $query->where('orders.status', Order::STATUS_INCOMPLETE);
                                break;
                            case "scheduled":
                                $query->where('orders.status', Order::STATUS_SCHEDULED);
                                break;
                            case "completed":
                                $query->where('orders.status', Order::STATUS_COMPLETED);
                                break;
                        }
                        return $query;
                    })->toJson();
                }
            }
        }
        return $data = Datatables::eloquent($model)->toJson();
    }

    public function deleteAll()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->orderAccessory->truncate();
        $this->orderAttachment->truncate();
        $this->model->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        return true;
    }

    public function repairOrdersDatatable()
    {
        $query = $this->model
            ->join('offices', 'offices.id', 'orders.office_id')
            ->where('status', Order::STATUS_COMPLETED)
            ->orderBy('updated_at', "DESC")
            ->select('orders.*');

        return $data = Datatables::eloquent($query)->toJson();
    }
}
