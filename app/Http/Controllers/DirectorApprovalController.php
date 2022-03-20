<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectorApproval;
use App\Models\ClientForm;
use App\Models\OrgUnit;
use App\Models\Loan;
use App\Exports\DirectorApprovalsExport;
use DateTime;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DirectorApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $clientForms = ClientForm::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))
            ->pluck('id'))
            ->whereNotNull('director_approval_id')
            ->paginate(config('app.admin.page_size', 20));

        return view('clientforms.directorApprovals.index', compact('clientForms'));

    }

    /**
     * @param Request $req
     * @return string
     */
    public function getApprs(Request $req): string
    {
        if ($req->ajax()) {
            $startDate = $req->get('dateFrom', Carbon::createFromTimestamp(0)->format('y-m-d'));
            $endDate = $req->get('dateTo', Carbon::now()->addYear()->format('y-m-d'));

            $clientForms = ClientForm::whereIn('org_unit_id',
                OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
                ->whereNotNull('director_approval_id')
                ->join('director_approvals', 'director_approvals.id', '=', 'client_forms.director_approval_id')
                ->whereBetween('director_approvals.approval_date', [$startDate, $endDate])
                ->orderBy('director_approvals.approval_date')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.director-approvals-tbody', compact('clientForms'))->render();
        }

        return '';
    }

    /**
     * @param Request $req
     * @return BinaryFileResponse
     */
    public function export(Request $req): BinaryFileResponse
    {
        $filename = 'director-approvals' . now('ymd') . '.xlsx';
        $startDate = $req->get('dateFrom', Carbon::createFromTimestamp(0)->format('y-m-d'));
        $endDate = $req->get('dateTo', Carbon::now()->addYear()->format('y-m-d'));

        return (new DirectorApprovalsExport($startDate, $endDate))->download($filename);
    }

    /**
     * Ожидающие одобрения
     *
     * @return View
     */
    public function taskList(): View
    {
        $clientForms = ClientForm::whereIn('org_unit_id',
            OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
            ->whereNull('director_approval_id')
            ->paginate(config('app.admin.page_size', 20));

        return view('clientforms.directorApprovals.tasks', compact('clientForms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return View
     */
    public function create(int $id): View
    {
        return view('clientforms.directorApprovals.create',
            ['clientForm' => ClientForm::find($id)]
        );

    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function accept(Request $req): RedirectResponse
    {
        $approval = new DirectorApproval();
        $approval->user_id = Auth::user()->id;
        $approval->approval = true;
        $approval->approval_date = new DateTime();

        if ($req->get('comment') != null) {
            $approval->comment = $req->get('comment');
        }

        if (!$approval->save()) {
            return back()->withErrors(['msg' => 'Ошибка создания одобрения']);
        }

        $setApprovalForm = ClientForm::find($req->get('client_form_id'));

        if (!$setApprovalForm) {
            return back()->withErrors(['msg' => 'Ошибка, анкета не найдена!']);
        }

        $setApprovalForm->director_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('directorApproval.tasks')
            ->with(['status' => 'Заявка на займ успешно одобрена']);
    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function reject(Request $req): RedirectResponse
    {
        $approval = new DirectorApproval();
        $approval->user_id = Auth::user()->id;
        $approval->approval = false;
        $approval->approval_date = new DateTime();

        if ($req->get('comment') != null) {
            $approval->comment = $req->get('comment');
        }

        if (!$approval->save()) {
            return back()->withErrors(['msg' => 'Ошибка создания одобрения']);
        }

        $setApprovalForm = ClientForm::find($req->get('client_form_id'));

        if (!$setApprovalForm) {
            return back()->withErrors(['msg' => 'Ошибка, анкета не найдена!']);
        }

        $setApprovalForm->director_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('directorApproval.tasks')
            ->with(['status' => 'Заявка на займ успешно отклонена']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $approval = DirectorApproval::find($id);
        $clientForm = ClientForm::where('director_approval_id', $id)->first();

        return view('clientforms.directorApprovals.show', compact(['approval', 'clientForm']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $approval = DirectorApproval::find($id);
        $clientForm = ClientForm::where('director_approval_id', $approval->id)->first();

        if ($approval != null
            && Loan::where('client_form_id', $clientForm->id)->first() == null) {
            $approval->delete();

            return redirect()->route('directorApproval.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return back()->withErrors([
                'msg' =>
                    trans('message.model.deleted.fail', [
                        'error' => ' к нему привязан займ',
                    ]),
            ]);
        }
    }
}
