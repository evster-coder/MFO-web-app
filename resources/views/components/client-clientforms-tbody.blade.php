@if($clientForms->count() == 0)
    <tr>
        <td>
            Отсутствуют заявки
        </td>
    </tr>
@endif

@foreach($clientForms as $clientForm)
    <tr>
        <td>
            <a class="btn btn-link"
               href="{{route('clientForm.show', ['id' => $clientForm->id])}}">
                <strong>Анкета №{{$clientForm->id}}
                    от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</strong>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </td>
    </tr>
@endforeach
