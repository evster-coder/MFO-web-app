@if($clients && $clients->count())
    <div class="block-section">
        <h4>Имеются похожие записи:</h4>
        <table class="table">
            <thead>
            <tr>
                <th>ФИО, дата рождения</th>
                <th>Паспорт</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>
                        <a class="btn btn-link"
                           target="_blank"
                           href="{{route('client.show', $client->id)}}">
                            {{$client->surname}} {{$client->name}} {{$client->patronymic}}
                            , {{date(config('app.date_format', 'd-m-Y'), strtotime($client->birth_date))}}
                        </a>
                    </td>
                    <td>
                        @if($client->clientForms->count())
                            @php
                                $lastClientForm = $client->clientForms->last()
                            @endphp
                            {{$lastClientForm->passport_series}} {{$lastClientForm->passport_number}}
                            от {{$lastClientForm->passport_date_issue}}. Выдан: {{$lastClientForm->passport_issued_by}}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
