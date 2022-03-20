<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\LoanTerm;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Response;

class LoanTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $terms = LoanTerm::orderBy('days_amount')
            ->paginate(config('app.admin.page_size', 20));

        return view('dictfields.loanterm.index', compact('terms'));
    }

    /**
     * @param Request $req
     * @return JsonResponse
     */
    public function axiosTerms(Request $req): JsonResponse
    {
        $query = $req->get('query');

        $terms = LoanTerm::where('days_amount', 'like', "%$query%")
            ->orderBy('days_amount')
            ->get();

        return Response::json($terms);
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getTerms(Request $req): string
    {
        if ($req->ajax()) {
            $query = str_replace(" ", "%", $req->get('query', ''));

            $terms = LoanTerm::where('days_amount', 'like', "%$query%")
                ->orderBy('days_amount')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.loanterms-tbody', compact('terms'))->render();
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
            'days_amount' => 'required|numeric|between:1,1000',
        ]);

        $termId = $request->get('dataId');
        LoanTerm::updateOrCreate(['id' => $termId], ['days_amount' => $request->get('days_amount')]);
        if (empty($rateId)) {
            $msg = trans('message.model.created.success');
        } else {
            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('loanterm.index')->with(['status' => $msg]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return Response::json(LoanTerm::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = LoanTerm::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('loanterm.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('loanterm.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail', [
                        'error' => ' в LoanTermController::destroy',
                    ]),
                ]);
        }
    }
}
