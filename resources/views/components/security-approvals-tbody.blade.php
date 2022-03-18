@if($clientForms->total() == 0)
    <tr>
        <td colspan="5">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($clientForms as $clientForm)
    <tr>
        <td>№{{$clientForm->id}} от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</td>
        <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->securityApproval->approval_date))}}</td>
        <td>{{$clientForm->client->fullName}}</td>
        <td>{{$clientForm->loan_cost}}</td>
        <td>{{$clientForm->user->username}} ({{$clientForm->user->full_name}})</td>
        <td>{{$clientForm->securityApproval->approval ? 'Одобрена' : 'Отклонена'}}</td>
        <td>
            <div class="d-flex manage-btns">
                <a class="btn btn-success"
                   href="{{route('securityApproval.show', ['id' => $clientForm->security_approval_id])}}"
                   role="button">
                    <i class="fas fa-eye"></i>
                </a>
                @if(!$clientForm->loan)
                    <form method="POST"
                          action="{{route('securityApproval.destroy', [$clientForm->securityApproval->id])}}">
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
