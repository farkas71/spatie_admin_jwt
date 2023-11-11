<x-app-layout>
    @section('title', 'Szerepkörök')

    <p class="text-center my-5 h1">Szerepkör felvétel</p>
    <div class="container d-flex justify-content-center mt-3">
        <div class="col-6">
            <form action="{{ route('roles.add') }}" method="POST">
                @csrf

                <label for="role_name">Szerepkőr neve:</label>
                <input type="text" class="form-control mb-2" name="role_name" id="role_name" required>

                @can('sync permissions')
                    <label>Engedélyek:</label><br>
                    <div class="form-control mb-2">
                        @foreach ($permissions as $permission)
                            <span class="badge text-bg-danger m-1">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    id="{{ $permission->id }}" class="form-check-input">
                                <label for="{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                            </span>
                        @endforeach
                        <br><br>
                        <label>
                            <input type="checkbox" id="selectAllPermissions"> Összes:
                        </label>
                    </div>
                @endcan
                <a href="{{ route('roles.list') }}" class="btn btn-sm btn-warning mt-2">Vissza</a>
                <input type="submit" name="mentes" value="MENTÉS" class="btn btn-sm btn-success mt-2">
            </form>
        </div>
    </div>

    <script src="{{ asset('js/role.js') }}"></script>
</x-app-layout>
