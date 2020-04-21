<div class="container">
    <div class="row">
        <div class="col-md-4">
            <form action="{{ route('group.store') }}" method="POST">
                @method('POST')
                @csrf

                <div class="form-group">
                    <div class="container">
                        <div class="row">
                            <label class="col-md-6" for="name">{{ __('Pavadinimas') }}</label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-sm col-md-6 @error('name') is-invalid @enderror"
                                   placeholder=""
                                   aria-describedby="helpId">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <small id="helpId" class="col-md-12 text-muted text-right">{{ _('Įveskite grupės pavadinimą') }}</small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-success">{{ __('Įvesti') }}</button>
                </div>
            </form>
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>{{ __('Pavadinimas') }}</th>
                    <th>{{ __('Veiksmai') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($groups as $group)

                    <tr>
                        <td scope="row">{{ $group->name }}</td>
                        <td>

                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
