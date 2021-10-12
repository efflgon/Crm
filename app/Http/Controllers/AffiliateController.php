<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Integration;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    use ApiResponser;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $items = Affiliate::query();
        if ($request->get('name')) {
            $items->where('name', 'ILIKE', '%' . $request->get('name') . '%');
        }
        $items->where('user_id', auth()->user()->id);
        $items->orderBy('id', 'asc');
        return $this->collection($items->get());
    }

    public function get(Request $request, int $id): JsonResponse
    {
        $items = Affiliate::query();
        $items->where('id', $id);
        $items->where('user_id', auth()->user()->id);
        $res = $items->get();
        if (count($res) === 0) {
            return $this->error('', 404);

        }
        $items->orderBy('id', 'asc');
        return $this->success($items->get()[0]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'integration_id' => 'required|integer',
            'params' => 'required|array',
        ]);

        $integration = Integration::find($attr['integration_id']);

        if ($integration === null) {
            return $this->error('integration_id not found', 500);
        }

        // var_dump($integration->affiliateParams);

        $validatorParams = [];
        foreach ($integration->affiliateParams as $data) {
            $validatorParams[$data['name']] = $data['validate'];
        }

        Validator::make($attr['params'], $validatorParams)->validate();

        $res = Affiliate::create([
            'name' => $attr['name'],
            'integration_id' => $attr['integration_id'],
            'params' => $attr['params'],
        ]);

        return $this->success($res);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $aff = Affiliate::find($id);
        if ($aff === null) {
            return $this->error('Affiliate not found', 500);
        }
        $aff->update($request->all());
        return $this->success($aff);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $aff = Affiliate::find($id);
        if ($aff === null) {
            return $this->error('Affiliate not found', 500);
        }
        $aff->delete();
        return $this->success(['status' => 'Success']);
    }
}
