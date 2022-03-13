@if($loans->count() == 0)
    <tr>
        <td>
            Отсутствуют займы
        </td>
    </tr>
@endif

@foreach($loans as $loan)
    <tr>
        <td>
            <a class="btn btn-link"
               href="{{route('loan.show', $loan->id)}}">
                <strong>
                    Договор № {{$loan->loanNumber}}
                    от {{date(config('app.date_format', 'd-m-Y'), strtotime($loan->loanConclusionDate))}}
                </strong>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </td>
    </tr>
@endforeach
