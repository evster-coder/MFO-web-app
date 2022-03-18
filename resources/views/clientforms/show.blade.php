@extends('layouts.user')

@section('title')
    Заявка на займ №{{$clientForm->id}} от {{$clientForm->loan_date}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
    <a class="btn btn-default"
       href="{{route('clientForm.index')}}">
        < К списку
    </a>
    <h1>Заявка на займ №{{$clientForm->id}} от {{$clientForm->loan_date}}</h1>
    <x-auth-session-status class="mb-4" :status="session('status')"/>
    <x-auth-validation-errors class="mb-4" :errors="$errors"/>

    <div class="block-content">
        <div class="d-flex block-padding">
            <p class="me-3">Статус:
                {{$clientForm->status}}
            </p>
            @if($clientForm->loan)
                <a class="btn btn-primary me-3"
                   href="{{route('loan.show', ['id' => $clientForm->loan->id])}}">
                    Перейти к договору
                </a>
            @else
                @perm('delete-clientform')
                <form method="POST" action="{{route('clientForm.destroy', $clientForm->id)}}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"
                            type="submit"
                            onclick="return confirm('Вы действительно хотите удалить запись?');">
                        Удалить
                    </button>
                </form>
                @endperm

                @if($clientForm->status == 'Одобрена')
                    @perm('create-loan')
                    <form method="POST" action="{{route('loan.store')}}">
                        @csrf
                        <input type="hidden" name="client_form_id" value="{{$clientForm->id}}">
                        <button class="btn btn-primary me-3" type="submit">
                            Заключить договор
                        </button>
                    </form>
                    @endperm
                @endif
            @endif
        </div>

        <hr>
        @if($clientForm->securityApproval)
            <p>Служба безопасности
                {{$clientForm->securityApproval->approval ? "(Одобр.)" : "(Откл.)"}})
                : {{$clientForm->securityApproval->comment}}</p>
        @endif
        @if($clientForm->directorApproval)
            <p>Директор:
                {{$clientForm->directorApproval->approval ? "(Одобр.)" : "(Откл.)"}})
                : {{$clientForm->directorApproval->comment}}</p>
        @endif
        <hr>
        <x-clientform-info :clientForm="$clientForm"/>
    </div>
@endsection
