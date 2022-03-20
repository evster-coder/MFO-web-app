<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\MaritalStatus;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Response;

class MaritalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $statuses = MaritalStatus::orderBy('name')
            ->paginate(config('app.admin.page_size', 20));

        return view('dictfields.maritalstatus.index', compact('statuses'));
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getStatuses(Request $req): string
    {
        if ($req->ajax()) {
            $query = str_replace(" ", "%", $req->get('query', ''));

            $statuses = MaritalStatus::where('name', 'like', "%$query%")
                ->orderBy('name')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.maritalstatuses-tbody', compact('statuses'))->render();
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

        $statusId = $request->get('dataId');
        MaritalStatus::updateOrCreate(['id' => $statusId], ['name' => $request->get('name')]);
        if (empty($statusId)) {
            $msg = trans('message.model.created.success');
        } else {
            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('maritalstatus.index')->with(['status' => $msg]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return Response::json(MaritalStatus::find($id));

    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = MaritalStatus::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('maritalstatus.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('maritalstatus.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail', [
                        'error' => ' в MaritalStatusController::destroy',
                    ]),
                ]);
        }
    }
}
