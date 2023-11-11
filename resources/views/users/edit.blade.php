<x-app-layout>
    @section('title', 'Felhasználók')

    <p class="text-center my-5 h1">Felhasználó szerkesztése</p>
    <div class="container d-flex justify-content-center mt-3">
        <div class="col-6">
            <form action="{{ route('users.editProces', ['userName' => $user->name]) }}" method="POST">
                @csrf

                <input type="hidden" name="id" value="{{ $user->id }}">

                <label for="name">Név:</label>
                <input type="text" class="form-control mb-2" name="name" id="name" value="{{ $user->name }}"
                    required>

                <label for="email">Email:</label>
                <input type="email" class="form-control mb-2" name="email" id="email"
                    value="{{ $user->email }}"required>

                <label for="password">Jelszó:</label>
                <input type="password" class="form-control mb-2" name="password" id="password">

                @can('sync roles')
                    <label>Szerepkörök:</label><br>
                    <div class="form-control mb-2">
                        @foreach ($roles as $role)
                            <span class="badge text-bg-danger m-1">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="{{ $role->id }}"
                                    class="form-check-input" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <label for="{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
                            </span>
                        @endforeach

                        <br><br>
                        <label>
                            <input type="checkbox" id="selectAllRoles"> Összes:
                        </label>
                    </div>
                @endcan
                <a href="{{ route('users.list') }}" class="btn btn-sm btn-warning mt-2">Vissza</a>
                <input type="submit" name="mentes" value="MENTÉS" class="btn btn-sm btn-success mt-2">
            </form>
        </div>
    </div>

    <script src="{{ asset('js/user.js') }}"></script>
</x-app-layout>
