<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityApproval;
use App\Models\ClientForm;
use App\Models\OrgUnit;
use App\Models\Loan;
use App\Exports\SecurityApprovalsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SecurityApprovalController extends Controller
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
            ->whereNotNull('security_approval_id')
            ->paginate(config('app.admin.page_size', 20));

        return view('clientforms.securityApprovals.index', compact('clientForms'));
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
                ->whereNotNull('security_approval_id')
                ->join('security_approvals', 'security_approvals.id', '=', 'client_forms.security_approval_id')
                ->whereBetween('security_approvals.approval_date', [$startDate, $endDate])
                ->orderBy('security_approvals.approval_date')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.security-approvals-tbody', compact('clientForms'))->render();
        }

        return '';
    }


    /**
     * @param Request $req
     * @return BinaryFileResponse
     */
    public function export(Request $req): BinaryFileResponse
    {
        $filename = 'sec-approvals' . date('ymd') . '.xlsx';
        $startDate = $req->get('dateFrom', Carbon::createFromTimestamp(0)->format('y-m-d'));
        $endDate = $req->get('dateTo', Carbon::now()->addYear()->format('y-m-d'));

        return (new SecurityApprovalsExport($startDate, $endDate))->download($filename);
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
            ->whereNull('security_approval_id')
            ->paginate(config('app.admin.page_size', 20));

        return view('clientforms.securityApprovals.tasks', [
            'clientForms' => $clientForms,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return View
     */
    public function create(int $id): View
    {
        return view('clientforms.securityApprovals.create',
            ['clientForm' => ClientForm::find($id)]
        );
    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function accept(Request $req): RedirectResponse
    {
        $approval = new SecurityApproval();
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

        $setApprovalForm->security_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('securityApproval.tasks')
            ->with(['status' => 'Заявка на займ успешно одобрена']);
    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function reject(Request $req): RedirectResponse
    {
        $approval = new SecurityApproval();
        $approval->user_id = Auth::user()->id;
        $approval->approval = false;
        $approval->approval_date = new DateTime();

        if ($req->get('comment') != null) {
            $approval->comment = $req->get('comment');
        }

        if (!$approval->save()) {
            return back()->withErrors(['msg' => trans('message.model.created.fail')]);
        }

        $setApprovalForm = ClientForm::find($req->get('client_form_id'));

        if (!$setApprovalForm) {
            return back()->withErrors(['msg' => 'Ошибка, анкета не найдена!']);
        }

        $setApprovalForm->security_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('securityApproval.tasks')
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
        $approval = SecurityApproval::find($id);
        $clientForm = ClientForm::where('security_approval_id', $id)->first();

        return view('clientforms.securityApprovals.show', compact(['approval', 'clientForm']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $approval = SecurityApproval::find($id);
        $clientForm = ClientForm::where('security_approval_id', $approval->id)->first();

        if ($approval != null
            && Loan::where('client_form_id', $clientForm->id)->first() == null) {
            $approval->delete();

            return redirect()->route('securityApproval.index')
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
