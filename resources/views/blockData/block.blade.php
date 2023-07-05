<x-master>
    <x-slot name="title">Block List</x-slot>

    <x-slot name="content_area">
        <x-widgets.card-component cardTitle='Block List' cardHeaderVisible='1' btnVisible='1' btnName='Create'
            btnClass='info' btnIcon='telephone-x-fill'>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Phone Number</th>
                            <th>DID</th>
                            <th>Reason</th>
                            <th>Create Date</th>
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
                        <h5 class="modal-title">Create Block List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="filter_phone_group_id">Block Group&nbsp;<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="filter_phone_group_id" required>
                                    <option value="">Select A Block Group</option>
                                    @foreach ($phoneGroupID as $data)
                                        <option value="{{ $data->filter_phone_group_id }}">
                                            {{ $data->filter_phone_group_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Phone Number"
                                inputId="phone_number" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-2" inputLabel="Reason"
                                inputId="reason" inputType="text" required='required' />
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
             * delete data from database
             */
            function deleteData(phone_number) {
                $.ajax({
                    url: "block-list-destroy/" + phone_number,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status == '200') {
                            successAlert(response.msg);
                            $('#dataTable').DataTable().draw(false);
                        }
                    },
                });
            }

            /**
             * save data to database
             */
            function submitData() {
                const filter_phone_group_id = $('#filter_phone_group_id').val();
                const phone_number = $('#phone_number').val();
                const reason = $('#reason').val();

                $.ajax({
                    url: "{{ route('system.block.store') }}",
                    method: 'POST',
                    data: {
                        'filter_phone_group_id': filter_phone_group_id,
                        'phone_number': phone_number,
                        'reason': reason,
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
                        url: "{{ route('system.block.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'phone_number',
                            name: 'phone_number'
                        },
                        {
                            data: 'filter_phone_group_id',
                            name: 'filter_phone_group_id'
                        },
                        {
                            data: 'reason',
                            name: 'reason'
                        },
                        {
                            data: 'date',
                            name: 'date'
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
