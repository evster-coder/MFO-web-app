<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\DirectorApproval;
use App\Models\ClientForm;
use App\Models\OrgUnit;
use App\Models\Loan;

use DateTime;
class DirectorApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientforms = ClientForm::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))
                        ->whereNotNull('director_approval_id')
                        ->paginate(20);
        return view('clientforms.directorApprovals.index', [
                'clientforms' => $clientforms
                ]);

    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {
        
    }
    
    //ожидающие одобрения
    public function taskList()
    {
        $clientforms = ClientForm::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))
                        ->whereNull('director_approval_id')
                        ->paginate(20);
        return view('clientforms.directorApprovals.tasks', [
                'clientforms' => $clientforms
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $clientform = ClientForm::find($id);
        return view('clientforms.directorApprovals.create', 
            ['clientform' => $clientform]
        );

    }


    //одобрение
    public function accept(Request $req)
    {
        $approval = new DirectorApproval();
        $approval->user_id = Auth::user()->id;
        $approval->approval= true;
        $approval->approvalDate = new DateTime('NOW');

        if($req->get('comment') != null)
            $approval->comment = $req->get('comment');

        $approval->save();

        if(!$approval)
            return back()->withErrors(['msg' => 'Ошибка создания одобрения']);

        $setApprovalForm = ClientForm::find($req->get('clientform_id'));
        if(!$setApprovalForm)
            return back()->withErrors(['msg' => 'Ошибка, анкета не найдена!']);
        $setApprovalForm->director_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('directorApproval.tasks')
                        ->with(['status' => 'Заявка на займ успешно одобрена']);
    }


    //отказ
    public function reject(Request $req)
    {
        $approval = new DirectorApproval();
        $approval->user_id = Auth::user()->id;
        $approval->approval= false;
        $approval->approvalDate = new DateTime('NOW');

        if($req->get('comment') != null)
            $approval->comment = $req->get('comment');

        $approval->save();

        if(!$approval)
            return back()->withErrors(['msg' => 'Ошибка создания одобрения']);

        $setApprovalForm = ClientForm::find($req->get('clientform_id'));
        if(!$setApprovalForm)
            return back()->withErrors(['msg' => 'Ошибка, анкета не найдена!']);
        $setApprovalForm->director_approval_id = $approval->id;
        $setApprovalForm->save();

        return redirect()->route('directorApproval.tasks')
                        ->with(['status' => 'Заявка на займ успешно отклонена']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $approval = DirectorApproval::find($id);
        $clientform = ClientForm::where('director_approval_id', $id)->first();
        return view('clientforms.directorApprovals.show',
            ['approval' => $approval,
            'clientform' => $clientform]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $approval = DirectorApproval::find($id);
        $clientform = ClientForm::where('director_approval_id', $approval->id)->first();

        if($approval != null 
            && Loan::where('clientform_id', $clientform->id)->first() == null)
        {
            $approval->delete();
            return redirect()->route('directorApproval.index')
                        ->with(['status' => 'Успешно удалено!']);
        }
        else
            return back()->withErrors(['msg' => 'Невозможно удалить одобрение, к нему привязан займ']);

    }
}
