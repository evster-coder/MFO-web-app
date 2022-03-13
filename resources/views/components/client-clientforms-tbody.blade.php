@if($clientforms->count() == 0)
    <tr>
        <td>
            Отсутствуют заявки
        </td>
    </tr>
@endif

@foreach($clientforms as $clientform)
    <tr>
        <td>
            <a class="btn btn-link"
               href="{{route('clientform.show', ['id' => $clientform->id])}}">
                <strong>Анкета №{{$clientform->id}}
                    от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->loanDate))}}</strong>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </td>
    </tr>
@endforeach
