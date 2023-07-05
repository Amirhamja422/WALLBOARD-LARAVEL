<x-master>
    <x-slot name="title">Phones</x-slot>

    <x-slot name="content_area">
        <x-widgets.card-component cardTitle='Phones List' cardHeaderVisible='1' btnVisible='1' btnName='Create'
            btnClass='info' btnIcon='telephone-plus-fill'>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Phone Extension</th>
                            <th>Phone Password</th>
                            <th>Server IP</th>
                            <th>Registration Password</th>
                            <th>Web RTC</th>
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
                        <h5 class="modal-title">Create Phone</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <x-widgets.input-component inputColClass="col-xl-6 mb-3" inputLabel="Phone Extension"
                                inputId="extension" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-3" inputLabel="Login Password"
                                inputId="pass" inputType="text" required='required' />

                            <x-widgets.input-component inputColClass="col-xl-6 mb-3" inputLabel="Registration Password"
                                inputId="conf_secret" inputType="text" required='required' />

                            <x-widgets.select-component inputColClass="col-xl-6 mb-3" inputLabel="Web RTC"
                                inputId='is_webphone' :optionList='$yesNoStatus' required='required' />
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

            function editData(extension) {
                alert("Show Edit Modal");
            }

            /**
             * delete data from database
             */
            function deleteData(extension) {
                $.ajax({
                    url: "phone-destroy/" + extension,
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
                const extension = $('#extension').val();
                const pass = $('#pass').val();
                const conf_secret = $('#conf_secret').val();
                const is_webphone = $('#is_webphone').val();

                $.ajax({
                    url: "{{ route('system.phone.store') }}",
                    method: 'POST',
                    data: {
                        'extension': extension,
                        'pass': pass,
                        'conf_secret': conf_secret,
                        'is_webphone': is_webphone,
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
                        url: "{{ route('system.phone.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'extension',
                            name: 'extension'
                        },
                        {
                            data: 'pass',
                            name: 'pass'
                        },
                        {
                            data: 'server_ip',
                            name: 'server_ip'
                        },
                        {
                            data: 'conf_secret',
                            name: 'conf_secret'
                        },
                        {
                            data: 'is_webphone',
                            name: 'is_webphone'
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
