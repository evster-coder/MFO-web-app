<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\InterestRate;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Response;

class InterestRateController extends Controller
{
    public function index()
    {
        $rates = InterestRate::orderBy('percent_value')
            ->paginate(config('app.admin.page_size', 20));

        return view('dictfields.interestrate.index', compact('rates'));
    }

    /**
     * @param Request $req
     * @return JsonResponse
     */
    public function axiosRates(Request $req): JsonResponse
    {
        $query = $req->get('query', '');

        $terms = InterestRate::where('percent_value', 'like', "%$query%")
            ->orderBy('percent_value')->get();

        return Response::json($terms);
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getRates(Request $req): string
    {
        if ($req->ajax()) {
            $query = str_replace(" ", "%", $req->get('query', ''));

            $rates = InterestRate::where('percent_value', 'like', "%$query%")
                ->orderBy('percent_value')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.interestrates-tbody', compact('rates'))->render();
        }

        return '';
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'percent_value' => 'required|numeric|between:0,1000000.999',
        ]);

        $rateId = $request->get('dataId');
        InterestRate::updateOrCreate(['id' => $rateId], ['percent_value' => $request->get('percent_value')]);

        if (empty($rateId)) {
            $msg = trans('message.model.created.success');
        } else {
            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('interestrate.index')->with(['status' => $msg]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return Response::json(InterestRate::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = InterestRate::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('interestrate.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('interestrate.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail', [
                        'error' => ' в InterestRateController::destroy',
                    ]),
                ]);
        }

    }
}
