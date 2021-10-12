<?php

namespace App\Http\Controllers;


use App\Models\Lead;
use App\Models\LeadStatus;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use ApiResponser;


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $attr = $request->validate([
            'hash' => 'required|string|uuid',
            'ip' => 'ip|nullable',
            'area_code' => 'string|nullable',
            'name' => 'string|nullable',
            'email' => 'string|nullable',
            'phone' => 'integer|nullable',
            'sub1' => 'string|nullable',
            'sub2' => 'string|nullable',
            'sub3' => 'string|nullable',
            'sub4' => 'string|nullable',
            'sub5' => 'string|nullable',
            'sub6' => 'string|nullable',
        ]);

        $attr['offer_hash'] = $attr['hash'];
        unset($attr['hash']);
        $lead = Lead::create($attr);

        return $this->success([
            "hash" => $lead->offer_hash,
            "ip" => $lead->ip,
            "area_code" => $lead->area_code,
            "name" => $lead->name,
            "email" => $lead->email,
            "phone" => $lead->phone,
            "sub1" => $lead->sub1,
            "sub2" => $lead->sub2,
            "sub3" => $lead->sub3,
            "sub4" => $lead->sub4,
            "sub5" => $lead->sub5,
            "sub6" => $lead->sub6,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $items = Lead::query();
        if ($request->get('hash')) {
            $items->where('offer_hash', 'ILIKE', '%' . $request->get('hash') . '%');
        }
        if ($request->get('ip')) {
            $items->where('ip', 'ILIKE', $request->get('ip'));
        }
        if ($request->get('area_code')) {
            $items->where('area_code', 'ILIKE', '%' . $request->get('area_code') . '%');
        }
        if ($request->get('email')) {
            $items->where('email', 'ILIKE', '%' . $request->get('email') . '%');
        }
        if ($request->get('phone')) {
            $items->where('phone', 'ILIKE', '%' . $request->get('phone') . '%');
        }
        if ($request->get('sub1')) {
            $items->where('sub1', 'ILIKE', '%' . $request->get('sub1') . '%');
        }
        if ($request->get('sub2')) {
            $items->where('sub2', 'ILIKE', '%' . $request->get('sub2') . '%');
        }
        if ($request->get('sub3')) {
            $items->where('sub3', 'ILIKE', '%' . $request->get('sub3') . '%');
        }
        if ($request->get('sub4')) {
            $items->where('sub4', 'ILIKE', '%' . $request->get('sub4') . '%');
        }
        if ($request->get('sub5')) {
            $items->where('sub5', 'ILIKE', '%' . $request->get('sub5') . '%');
        }
        if ($request->get('sub6')) {
            $items->where('sub6', 'ILIKE', '%' . $request->get('sub6') . '%');
        }
        if ($request->get('status')) {
            $items->whereHas('status', function (Builder $query) use ($request) {
                $arr = explode(',', $request->get('status'));
                foreach ($arr as $key => $val) {

                    if ($key === 0) {
                        $query->Where('status', $val);
                    } else {
                        $query->orWhere('status', $val);
                    }
                }
            });

        }

        $items->orderBy('id', 'desc');

        return $this->collection($items->get());
    }

}
