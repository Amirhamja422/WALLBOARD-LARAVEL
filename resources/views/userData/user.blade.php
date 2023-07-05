<x-master>
    <x-slot name="title">Users</x-slot>

    <x-slot name="content_area">
        <x-widgets.card-component cardTitle='Users List' cardHeaderVisible='1' btnVisible='1' btnName='Create'
            btnClass='info' btnIcon='person-plus-fill'>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Phone ID</th>
                            <th>Email</th>
                            <th>User Group</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-widgets.card-component>

        <!-- Modal Start -->
        <div class="modal fade" id="createModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="User Name"
                                inputId="user" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Full Name"
                                inputId="full_name" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="User Password"
                                inputId="pass" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="User Email"
                                inputId="email" inputType="text" required='required' />

                            <x-widgets.select-component inputColClass="col-xl-6 mb-2" inputLabel="Agent Call Manual"
                                inputId='agentcall_manual' :optionList='$zeroOneStatus' required='required' />

                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="user_group">User Group&nbsp;<span
                                        class="text-danger">*</span></label><span
                                    class="text-danger error_txt user_group"></span>
                                <select class="form-select" id="user_group" required>
                                    <option value="">Select A User Group</option>
                                    @foreach ($userGroups as $userGroup)
                                        <option value="{{ $userGroup->user_group }}">{{ $userGroup->user_group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="phone_login">Phone ID&nbsp;<span
                                        class="text-danger">*</span></label><span
                                    class="text-danger error_txt phone_login"></span>
                                <select class="form-select" id="phone_login" required>
                                    <option value="">Select Phone ID</option>
                                    @foreach ($phones as $phone)
                                        <option value="{{ $phone->extension }}">{{ $phone->extension }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="submitData()" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {
                searchData();
            });
            function editData(user_id) {
                $('#editModal').modal('show');
            }

            function editData(user_id) {
                $('#editModal').modal('show');
            }

            function changeStatus(user_id) {
                $.ajax({
                    url: "user-status-change" + '/' + user_id,
                    method: 'GET',
                    success: function(response) {
                        if (response.status == '200') {
                            $('#dataTable').DataTable().draw(false);
                            successAlert(response.msg);
                        }
                    },
                });
            }

            function submitData() {
                const user = $('#user').val();
                const full_name = $('#full_name').val();
                const pass = $('#pass').val();
                const email = $('#email').val();
                const agentcall_manual = $('#agentcall_manual').val();
                const user_group = $('#user_group').val();
                const phone_login = $('#phone_login').val();

                $.ajax({
                    url: "{{ route('system.user.store') }}",
                    method: 'POST',
                    data: {
                        'user': user,
                        'full_name': full_name,
                        'pass': pass,
                        'email': email,
                        'agentcall_manual': agentcall_manual,
                        'user_group': user_group,
                        'phone_login': phone_login,
                    },
                    success: function(response) {
                        rmvErrorClass('modal-body');
                        blankValue('modal-body');
                        successMsg(response);
                    },

                    error: function(error) {
                        rmvErrorClass('modal-body');
                        errorMsg(error);
                    },
                });
            }

            function searchData() {
                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    scrollX: true,
                    ajax: ({
                        url: "{{ route('system.user.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'phone_login',
                            name: 'phone_login'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'user_group',
                            name: 'user_group'
                        },
                        {
                            data: 'active',
                            name: 'active'
                        },
                        {
                            data: 'actions',
                            name: 'actions'
                        },
                    ]
                });
            }
        </script>
    @endpush
</x-master>
