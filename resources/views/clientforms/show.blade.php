@extends('layouts.user')

@section('title')
    Заявка на займ №{{$clientform->id}} от {{$clientform->loanDate}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
    <a class="btn btn-default"
       href="{{route('clientform.index')}}">
        < К списку
    </a>
    <h1>Заявка на займ №{{$clientform->id}} от {{$clientform->loanDate}}</h1>
    <x-auth-session-status class="mb-4" :status="session('status')"/>
    <x-auth-validation-errors class="mb-4" :errors="$errors"/>

    <div class="block-content">
        <div class="d-flex block-padding">
            <p class="me-3">Статус:
                {{$clientform->status}}
            </p>
            @if($clientform->Loan)
                <a class="btn btn-primary me-3"
                   href="{{route('loan.show', ['id' => $clientform->Loan->id])}}">
                    Перейти к договору
                </a>
            @else
                @perm('delete-clientform')
                <form method="POST" action="{{route('clientform.destroy', $clientform->id)}}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"
                            type="submit"
                            onclick="return confirm('Вы действительно хотите удалить запись?');">
                        Удалить
                    </button>
                </form>
                @endperm

                @if($clientform->status == 'Одобрена')
                    @perm('create-loan')
                    <form method="POST" action="{{route('loan.store')}}">
                        @csrf
                        <input type="hidden" name="clientform_id" value="{{$clientform->id}}">
                        <button class="btn btn-primary me-3" type="submit">
                            Заключить договор
                        </button>
                    </form>
                    @endperm
                @endif
            @endif
        </div>

        <hr>
        @if($clientform->SecurityApproval)
            <p>Служба безопасности
                {{$clientform->SecurityApproval->approval ? "(Одобр.)" : "(Откл.)"}})
                : {{$clientform->SecurityApproval->comment}}</p>
        @endif
        @if($clientform->DirectorApproval)
            <p>Директор:
                {{$clientform->DirectorApproval->approval ? "(Одобр.)" : "(Откл.)"}})
                : {{$clientform->DirectorApproval->comment}}</p>
        @endif
        <hr>
        <x-clientform-info :clientform="$clientform"/>
    </div>
@endsection
