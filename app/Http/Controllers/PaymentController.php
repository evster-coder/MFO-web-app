<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return Payment[]|Collection
     */
    public function index($id)
    {
        return Loan::find($id)->payments;
    }

    public function export(Request $req)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return View
     */
    public function create(int $id): View
    {
        $user = Auth::user();
        $loan = Loan::find($id);

        return view('payments.create', compact(['loan', 'user']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $req
     * @return RedirectResponse
     */
    public function store(Request $req): RedirectResponse
    {
        $payment = new Payment();
        $payment->payment_date = $req->get('payment_date');
        $payment->user_id = Auth::user()->id;
        $payment->org_unit_id = session('org_unit');
        $payment->payment_sum = $req->get('payment_sum');
        $payment->loan_id = $req->get('loan_id');

        if ($payment->save()) {
            return redirect()->route('payment.show', $payment->id)
                ->with(['status' => trans('message.model.created.success')]);
        } else {
            return back()->withErrors(['msg' => trans('message.model.created.fail')]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $payment = Payment::find($id);

        return view('payments.show', compact('payment'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $payment = Payment::find($id);
        $loan = $payment->loan_id;

        if ($payment && $payment->$payment->delete()) {
            return redirect()->route('loan.show', ['id' => $loan])
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('loan.show', ['id' => $loan])
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail', [
                        'error' => ' в PaymentController::destroy',
                    ]),
                ]);
        }
    }
}
