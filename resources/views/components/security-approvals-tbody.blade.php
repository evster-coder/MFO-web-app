@if($clientforms->total() == 0)
    <tr>
        <td colspan="5">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($clientforms as $clientform)
    <tr>
        <td>№{{$clientform->id}} от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->loanDate))}}</td>
        <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->SecurityApproval->approvalDate))}}</td>
        <td>{{$clientform->Client->fullName}}</td>
        <td>{{$clientform->loanCost}}</td>
        <td>{{$clientform->User->username}} ({{$clientform->User->FIO}})</td>
        <td>{{$clientform->SecurityApproval->approval ? 'Одобрена' : 'Отклонена'}}</td>
        <td>
            <div class="d-flex manage-btns">
                <a class="btn btn-success"
                   href="{{route('securityApproval.show', ['id' => $clientform->security_approval_id])}}"
                   role="button">
                    <i class="fas fa-eye"></i>
                </a>
                @if(!$clientform->Loan)
                    <form method="POST"
                          action="{{route('securityApproval.destroy', [$clientform->SecurityApproval->id])}}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger"
                                type="submit"
                                onclick="return confirm('Вы действительно хотите удалить запись?');">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach
