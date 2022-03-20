<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\Seniority;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Response;

class SeniorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $senioritis = Seniority::orderBy('name')
            ->paginate(config('app.admin.page_size', 20));

        return view('dictfields.seniority.index', compact('senioritis'));
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getSenioritis(Request $req)
    {
        if ($req->ajax()) {
            $query = str_replace(" ", "%", $req->get('query', ''));

            $senioritis = Seniority::where('name', 'like', "%$query%")
                ->orderBy('name')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.senioritis-tbody', compact('senioritis'))->render();
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
            'name' => 'required|string|min:1|max:100',
        ]);

        $seniorityId = $request->get('dataId');
        Seniority::updateOrCreate(['id' => $seniorityId], ['name' => $request->get('name')]);
        if (empty($request->dataId)) {
            $msg = trans('message.model.created.success');
        } else {
            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('seniority.index')->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return Response::json(Seniority::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = Seniority::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('seniority.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('seniority.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail', [
                        'error' => ' в MaritalStatusController::destroy',
                    ]),
                ]);
        }

    }
}
