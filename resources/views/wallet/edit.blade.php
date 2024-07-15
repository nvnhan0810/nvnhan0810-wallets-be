@extends('layouts.default')

@section('styles')
<style>
    .main-content {
        max-width: 600px;
        margin: auto;
    }
</style>
@endsection

@section('content')
<main class="container d-flex m-4 bg-white rounded-4 py-4 mx-md-auto">
    <div class="main-content">
        <h1 class="text-center">Update Wallet</h1>

        @if($errors->any())
        <div class="alert alert-danger mb-4 ps-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="wallet-form" class="w-100" action="{{ route('wallets.update', $wallet) }}" method="POST">
            @csrf()
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ $wallet->name }}" />
            </div>

            <div id="type-groups" class="mb-4">
                <label class="form-label">Accounts</label>

                <div id="type-group-list">
                    @foreach($wallet->walletAccounts as $keyAccount => $account)
                    <input type="hidden" name="accounts[{{ $keyAccount }}][id]" value="{{ $account->id }}" />
                    <div id="type-group-{{ $keyAccount }}" class="type-group border rounded p-2 mb-2">
                        <div class="mb-4">
                            <label for="type" class="form-label">Types</label>
                            <select id="type" name="accounts[{{ $keyAccount }}][type]" class="form-select account-type-select">
                                @foreach($accounts as $key => $type)
                                <option value="{{ $key }}" {{ $account->type === $key ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex">
                            <label for="type" class="form-label">Balance</label>
                            <input type="number" class="form-control" name="accounts[{{ $keyAccount }}][balance]" value="{{ $account->balance }}" />
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" id="add-account" class="btn btn-link" count="{{ $wallet->walletAccounts->count() }}">Add Account</button>
            </div>

            <button type="submit" class="btn btn-primary w-100">Create</button>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const btnAdd = document.getElementById('add-account');
    const accountOptions = @json($accounts);

    btnAdd.addEventListener('click', () => {
        const count = parseInt(btnAdd.getAttribute('count'));
        btnAdd.setAttribute('count', count + 1);

        const optionHtml = Object.keys(accountOptions).map((type) => {
            return `<option value="${type}">${accountOptions[type]['name']}</option>`;
        });

        document.getElementById('type-group-list').insertAdjacentHTML('beforeend', `
            <div id="type-group-${count}" class="type-group border rounded p-2 mb-2">
                <div class="mb-4">
                    <label for="type" class="form-label">Types</label>
                    <select id="type" name="accounts[${count}][type]" class="form-select">
                        ${optionHtml}
                    </select>
                </div>
                <div class="flex">
                    <label for="type" class="form-label">Balance</label>
                    <input type="number" class="form-control" name="accounts[${count}][balance]" value="0" />
                </div>
            </div>
        `);
    });
</script>
@endsection
