@if($clientForms->total() == 0)
    <tr>
        <td colspan="5">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($clientForms as $clientForm)
    <tr>
        <td>{{$clientForm->id}}</td>
        <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</td>
        <td>{{$clientForm->client->surname}} {{$clientForm->client->name}} {{$clientForm->client->patronymic}}
            ({{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->client->birth_date))}})
        </td>
        <td>{{$clientForm->status}}</td>
        <td>
            <div class="d-flex manage-btns">
                <a class="btn btn-success"
                   href="{{route('clientForm.show', $clientForm->id)}}"
                   role="button">
                    <i class="fas fa-eye"></i>
                </a>

                @perm('edit-clientform')
                <a class="btn btn-info"
                   href="{{route('clientForm.edit', $clientForm->id)}}"
                   role="button">
                    <i class="fas fa-edit"></i>
                </a>
                @endperm

                @perm('delete-clientform')
                <form method="POST" action="{{route('clientForm.destroy', $clientForm->id)}}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"
                            type="submit"
                            onclick="return confirm('Вы действительно хотите удалить запись?');">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                @endperm

            </div>
        </td>
    </tr>
@endforeach

<tr class="pagination-tr">
    <td colspan="6" align="center">
        <p class="pagination-p">
            Текущая страница {{$clientForms->currentPage()}} из {{$clientForms->lastPage()}}
        </p>
        {{ $clientForms->links('pagination::bootstrap-4') }}
    </td>
</tr>
