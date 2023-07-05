<x-master>
    <x-slot name="title">Recording Report</x-slot>

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
                </div>
            </div>
        </x-widgets.card-component>
        <!--end searchbox-->

        <!--start main card-->
        <x-widgets.card-component cardTitle='Recordings List' cardHeaderVisible='1' btnVisible='0' btnName=''
            btnClass='' btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date Time</th>
                            <th>Recording ID</th>
                            <th>Agent ID</th>
                            <th>Campaign ID</th>
                            <th>Phone Number</th>
                            <th>Player</th>
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

            function searchData() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('recording.recording.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        }
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'start_time',
                            name: 'start_time'
                        },
                        {
                            data: 'recording_id',
                            name: 'recording_id'
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'campaign_id',
                            name: 'campaign_id'
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number'
                        },
                        {
                            data: 'player',
                            name: 'player'
                        },
                    ]
                });
            }
        </script>
    @endpush
</x-master>
