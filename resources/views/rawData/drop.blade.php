<x-master>
    <x-slot name="title">Drop</x-slot>

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

        <x-widgets.card-component cardTitle='Drop Calls' cardHeaderVisible='1' btnVisible='0' btnName=''
            btnClass='' btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Call Date</th>
                            <th>Phone Number</th>
                            <th>Skill Name</th>
                            <th>Status</th>
                            <th>Queue Wait</th>
                            <th>Term Reason</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-widgets.card-component>
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {
                searchData();
            });

            function searchData() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();

                var url = "{{ URL::to('/drop-download') }}/" + start_date + "/" + end_date;
                $('#download').attr('href', url);

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    scrollX: true,
                    ajax: ({
                        url: "{{ route('rawData.drop.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        }
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'call_date',
                            name: 'call_date'
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number'
                        },
                        {
                            data: 'campaign_id',
                            name: 'campaign_id'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'queue_seconds',
                            name: 'queue_seconds'
                        },
                        {
                            data: 'term_reason',
                            name: 'term_reason'
                        },
                    ]
                });
            }


            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: true
            });
        </script>
    @endpush
</x-master>
