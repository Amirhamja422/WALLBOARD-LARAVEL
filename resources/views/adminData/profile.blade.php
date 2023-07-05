<x-master>
    <x-slot name="title">Users</x-slot>

    <x-slot name="content_area">
        <div class="row">
            <div class="col-12 col-lg-4">
                <x-widgets.card-component cardTitle='Profile' cardHeaderVisible='1' btnVisible='' btnName=''
                    btnClass='' btnIcon=''>
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="{{ asset('images/avatar-1.png') }}" alt="Admin"
                            class="rounded-circle p-1 bg-primary" width="110">
                        <div class="mt-3">
                            <h4>{{ $profile->name }}</h4>
                            <p class="text-secondary mb-0">Full Stack Developer</p>
                        </div>
                    </div>
                </x-widgets.card-component>
            </div>

            <div class="col-12 col-lg-8">
                <section class="update_profile">
                    <x-widgets.card-component cardTitle='Update Profile' cardHeaderVisible='1' btnVisible=''
                        btnName='' btnClass='' btnIcon=''>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Full Name&nbsp;<span class="text-danger">*</span></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" id="name"
                                    value="{{ $profile->name }}" />
                                <span class="text-danger error_txt name"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email&nbsp;<span class="text-danger">*</span></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" id="email"
                                    value="{{ $profile->email }}" />
                                <span class="text-danger error_txt email"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                @php echo status_01($profile->status) @endphp
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="button" onclick="submitData()" class="btn btn-primary px-4"
                                    value="Update" />
                            </div>
                        </div>
                    </x-widgets.card-component>
                </section>

                <section class="update_password">
                    <x-widgets.card-component cardTitle='Update Password' cardHeaderVisible='1' btnVisible=''
                        btnName='' btnClass='' btnIcon=''>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0" for="old_password">Old Password&nbsp;<span
                                        class="text-danger">*</span>
                                </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="password" class="form-control" id="old_password" />
                                <span class="text-danger error_txt old_password"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0" for="new_password">New Password&nbsp;<span
                                        class="text-danger">*</span>
                                </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="password" class="form-control" id="new_password" />
                                <span class="text-danger error_txt new_password"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="button" onclick="updatePassword()" class="btn btn-primary px-4"
                                    value="Update" />
                            </div>
                        </div>
                    </x-widgets.card-component>
                </section>

            </div>
        </div>
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {

            });

            function submitData() {
                const name = $('#name').val();
                const email = $('#email').val();

                $.ajax({
                    url: "{{ route('profile.update') }}",
                    method: 'POST',
                    data: {
                        'name': name,
                        'email': email,
                    },
                    success: function(response) {
                        rmvErrorClass('update_profile');
                        successMsg(response);
                    },

                    error: function(error) {
                        rmvErrorClass('update_profile');
                        errorMsg(error);
                    },
                });
            }

            /**
             * update password
             */
            function updatePassword() {
                const old_password = $('#old_password').val();
                const new_password = $('#new_password').val();

                $.ajax({
                    url: "{{ route('profile.password.update') }}",
                    method: 'POST',
                    data: {
                        'old_password': old_password,
                        'new_password': new_password,
                    },
                    success: function(response) {
                        rmvErrorClass('update_password');
                        blankValue('update_password');
                        successMsg(response);
                    },

                    error: function(error) {
                        rmvErrorClass('update_password');
                        errorMsg(error);
                    },
                });
            }
        </script>
    @endpush
</x-master>
