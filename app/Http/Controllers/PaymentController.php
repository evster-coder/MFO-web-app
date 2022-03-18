<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Payment;

use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return Loan::find($id)->payments;
    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = Auth::user();
        $loan = Loan::find($id);
        return view('payments.create',
            ['loan' => $loan,
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $payment = new Payment();
        $payment->payment_date = $req->get('payment_date');
        $payment->user_id = Auth::user()->id;
        $payment->org_unit_id = session('org_unit');
        $payment->payment_sum = $req->get('payment_sum');
        $payment->loan_id = $req->get('loan_id');

        if($payment->save())
            return redirect()->route('payment.show', $payment->id)
                        ->with(['status' => 'Платеж создан']);
        else
            return back()->withErrors(['msg' => 'Ошибка создания платежа']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);
        return view('payments.show', ['payment' => $payment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        $loan = $payment->loan_id;

        if($payment)
        {
            $payment->delete();
            return redirect()->route('loan.show', ['id' => $loan])->with(['status' => 'Платеж успешно удален']);
        }
        else
        {
            return redirect()->route('loan.show', ['id' => $loan])
                ->withErrors(['msg' => 'Ошибка удаления в PaymentController::destroy']);

        }

    }
}
