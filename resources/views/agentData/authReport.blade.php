<x-master>
    <x-slot name="title">Auth Report</x-slot>

    <x-slot name="content_area">
        <!--start searchbox-->
        <x-widgets.card-component cardTitle='' cardHeaderVisible='0' btnVisible='0' btnName='' btnClass='' btnIcon=''>
            <div class="row">
                <x-widgets.input-component inputColClass="col-xl-6 mb-3" inputLabel="Start Date" inputId="start_date"
                    inputType="text" required='' />

                <x-widgets.input-component inputColClass="col-xl-6 mb-3" inputLabel="End Date" inputId="end_date"
                    inputType="text" required='' />

                <div class="col-md-6">
                    <label class="form-label" for="agent">Agents</label>
                    <select class="form-select mb-3" id="agent">
                        <option value="all">ALL Agents</option>
                        @foreach (usersList() as $user)
                            <option value="{{ $user }}">{{ $user }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="button" class="btn btn-sm btn-primary px-3" onclick="searchData()"><i
                            class="bi bi-search mr-1'"></i>Search</button>
                    <button type="button" id="download" class="btn btn-sm btn-info px-3" onclick="download()"><i
                            class="bi bi-download mr-1"></i>
                        Download</button>
                </div>
            </div>
        </x-widgets.card-component>
        <!--end searchbox-->

        <!--start main card-->
        <x-widgets.card-component cardTitle='Auth' cardHeaderVisible='1' btnVisible='0' btnName='' btnClass=''
            btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Agent ID</th>
                            <th>Full Name</th>
                            <th>First Login</th>
                            <th>Last Logout</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-widgets.card-component>
        <!--start main card-->
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {
                $('.datepicker').pickadate({
                    selectMonths: true,
                    selectYears: true
                });

                searchData();
            });

            /**
             * auth report download
             * 
             * @param start_date
             * @param end_date
             * @param agent
             */
            function download() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                const agent = $('#agent').val();

                $.ajax({
                    url: "auth-download",
                    method: 'GET',
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        agent: agent,
                    },
                    success: function(response) {
                        if (response.status == '200') {
                            console.log(response.msg);
                        }
                    },
                });
            }


            /**
             * auth report search
             * 
             * @param start_date
             * @param end_date
             * @param agent
             */
            function searchData() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                const agent = $('#agent').val();

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('agentData.auth.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                            agent: agent,
                        }
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'date_time',
                            name: 'date_time'
                        },
                        {
                            data: 'user_name',
                            name: 'user_name'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'first_login',
                            name: 'first_login'
                        },
                        {
                            data: 'last_logout',
                            name: 'last_logout'
                        },
                    ]
                });
            }
        </script>
    @endpush
</x-master>
