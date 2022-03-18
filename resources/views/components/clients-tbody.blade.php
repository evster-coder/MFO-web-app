@if($clients->total() == 0)
    <tr>
        <td colspan="5">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach ($clients as $client)
    <tr>
        <td>{{$client->surname}}</td>
        <td>{{$client->name}}</td>
        <td>{{$client->patronymic}}</td>
        <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($client->birth_date))}}</td>
        <td>
            <div class="d-flex manage-btns">
                <!-- Админские кнопки редактирования и удаления -->
                <a class="btn btn-success"
                   href="{{route('client.show', [$client->id])}}"
                   role="button">
                    <i class="fas fa-eye"></i>
                </a>
                @perm('edit-client')
                <a class="btn btn-info"
                   href="{{route('client.edit', [$client->id])}}"
                   role="button">
                    <i class="fas fa-edit"></i>
                </a>
                @endperm

                @perm('delete-client')
                <form method="POST" action="{{route('client.destroy', [$client->id])}}">
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
            Текущая страница {{$clients->currentPage()}} из {{$clients->lastPage()}}
        </p>
        {{ $clients->links('pagination::bootstrap-4') }}
    </td>
</tr>
