<x-master>
    <x-slot name="title">Administrator</x-slot>

    <x-slot name="content_area">
        <x-widgets.card-component cardTitle='Administrator List' cardHeaderVisible='1' btnVisible='1' btnName='Create'
            btnClass='info' btnIcon='person-fill-gear'>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
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
                        <h5 class="modal-title">Create Administrator</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Full Name"
                                inputId="name" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Email" inputId="email"
                                inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Password"
                                inputId="password" inputType="text" required='required' />

                            <x-widgets.select-component inputColClass="col-xl-6 mb-2" inputLabel="Status"
                                inputId='status' :optionList='$zeroOneStatus' required='required' />
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

            /**
             * change status
             */
            function changeStatus(user_id) {
                $.ajax({
                    url: "administrator-status-change" + '/' + user_id,
                    method: 'GET',
                    success: function(response) {
                        if (response.status == '200') {
                            $('#dataTable').DataTable().draw(false);
                            successAlert(response.msg);
                        }
                    },
                });
            }

            /**
             * save data to database
             */
            function submitData() {
                const name = $('#name').val();
                const email = $('#email').val();
                const password = $('#password').val();
                const status = $('#status').val();

                $.ajax({
                    url: "{{ route('system.administrator.store') }}",
                    method: 'POST',
                    data: {
                        'name': name,
                        'email': email,
                        'password': password,
                        'status': status,
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

            /**
             * search and view data
             */
            function searchData() {
                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    scrollX: true,
                    ajax: ({
                        url: "{{ route('system.administrator.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'status',
                            name: 'status'
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
