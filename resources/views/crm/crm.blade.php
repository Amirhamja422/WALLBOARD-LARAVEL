<x-master>
    <x-slot name="title">CRM</x-slot>

    <x-slot name="content_area">
        <!--start searchbox-->
        <x-widgets.card-component cardTitle='' cardHeaderVisible='0' btnVisible='0' btnName='' btnClass='' btnIcon=''>
            <div class="row">
                <x-widgets.input-component inputColClass="col-xl-6" inputLabel="Start Date" inputId="start_date"
                    inputType="text" required='' />

                <x-widgets.input-component inputColClass="col-xl-6" inputLabel="End Date" inputId="end_date"
                    inputType="text" required='' />
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="button" class="btn btn-sm btn-primary px-3" onclick="searchData()"><i
                            class="bi bi-search mr-1'"></i>Search</button>
                    <a href="" id="download" class="btn btn-sm btn-info px-3"><i
                            class="bi bi-download mr-1"></i>
                        Download</a>
                </div>
            </div>
        </x-widgets.card-component>
        <!--end searchbox-->

        <x-widgets.card-component cardTitle='CRM Data' cardHeaderVisible='1' btnVisible='0' btnName=''
            btnClass='' btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Call Type</th>
                            <th>Alt Mob</th>
                            <th>Nid</th>
                            <th>Sub call Type</th>
                            <th>Gender</th>
                            <th>Occupation</th>
                            <th>Category Name</th>
                            <th>Con. Address Division</th>
                            <th>Con. District</th>
                            <th>Con. Thana</th>
                            <th>Con. Write Address</th>
                            <th>Com. Address Division</th>
                            <th>Com. District</th>
                            <th>Com. Thana</th>
                            <th>Com. Write Address</th>
                            <th>Consumer Type</th>
                            <th>Organization Type</th>
                            <th>Organization Name</th>
                            <th>Call status</th>
                            <th>Agent Feedback</th>
                            <th>Consumer Query</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-widgets.card-component>
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

            function searchData() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();

                var url = "{{ URL::to('/crm-download') }}/" + start_date + "/" + end_date;
                $('#download').attr('href', url);

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('crm.crm.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        }
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'alt_phone',
                            name: 'alt_phone'
                        },
                        {
                            data: 'nid',
                            name: 'nid'
                        },
                        {
                            data: 'caller_type',
                            name: 'caller_type'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'occupation',
                            name: 'occupation'
                        },
                        {
                            data: 'cat_name',
                            name: 'cat_name'
                        },
                        {
                            data: 'con_ad',
                            name: 'con_ad'
                        },
                        {
                            data: 'con_dis',
                            name: 'con_dis'
                        },
                        {
                            data: 'con_thana',
                            name: 'con_thana'
                        },
                        {
                            data: 'con_write',
                            name: 'con_write'
                        },
                        {
                            data: 'com_ad',
                            name: 'com_ad'
                        },
                        {
                            data: 'com_dis',
                            name: 'com_dis'
                        },
                        {
                            data: 'com_thana',
                            name: 'com_thana'
                        },
                        {
                            data: 'com_write',
                            name: 'com_write'
                        },
                        {
                            data: 'consumer_type',
                            name: 'consumer_type'
                        },
                        {
                            data: 'organization_type',
                            name: 'organization_type'
                        },
                        {
                            data: 'organization_name',
                            name: 'organization_name'
                        },
                        {
                            data: 'call_status',
                            name: 'call_status'
                        },
                        {
                            data: 'con_query',
                            name: 'con_query'
                        },
                        {
                            data: 'remark',
                            name: 'remark'
                        },
                    ]
                });
            }
        </script>
    @endpush
</x-master>
