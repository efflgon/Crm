<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferResource;
use App\Models\Affiliate;
use App\Models\Offer;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    use ApiResponser;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function gelAll(Request $request): JsonResponse
    {
        $items = Offer::query();
        if ($request->get('name')) {
            $items->where('name', 'ILIKE', '%' . $request->get('name') . '%');
        }
        if ($request->get('affiliate_id')) {
            $items->where('affiliate_id', 'ILIKE', '%' . $request->get('affiliate_id') . '%');
        }
        $items->orderBy('id', 'asc');
        return $this->collection($items->get());
    }

    public function get(Request $request, int $id): JsonResponse
    {
        $items = Offer::query();
        $items->where('id', $id);
        $items->where('user_id', auth()->user()->id);
        $items->orderBy('id', 'asc');
        return $this->success($items->get()[0]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'affiliate_id' => 'required|integer',
            'params' => 'array',
        ]);

        if (Affiliate::find($request->get('affiliate_id')) === null) {
            return $this->error('affiliate_id not found', 500);
        }

        $res = Offer::create([
            "name" => $request->get('name'),
            "affiliate_id" => $request->get('affiliate_id'),
            "params" => $request->get('params'),
        ]);

        return $this->collection($res);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $offer = Offer::find($id);
        if ($offer === null) {
            return $this->error('Offer not found', 500);
        }
        $offer->update($request->all());
        return $this->success($offer);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $aff = Offer::find($id);
        if ($aff === null) {
            return $this->error('Affiliate not found', 500);
        }
        $aff->delete();
        return $this->success(['status' => 'Success']);
    }

}
