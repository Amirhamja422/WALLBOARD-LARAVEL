<x-master>
    <x-slot name="title">Inbound</x-slot>

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
                    <button type="button" id="download" class="btn btn-sm btn-info px-3" onclick="download()"><i
                            class="bi bi-download mr-1"></i>
                        Download</button>
                </div>
            </div>
        </x-widgets.card-component>
        <!--end searchbox-->

        <x-widgets.card-component cardTitle='Inbound Calls' cardHeaderVisible='1' btnVisible='0' btnName=''
            btnClass='' btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Call Date</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>Agent</th>
                            <th>Skill Name</th>
                            <th>Talk Time</th>
                            <th>Dead Time</th>
                            <th>Dispo Time</th>
                            <th>Length</th>
                            <th>Queue Time</th>
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

            function download() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();

                $.ajax({
                    url: "inbound-download" + '/' + start_date + "/" + end_date,
                    method: 'GET',
                    success: function(response) {
                        if (response.status == '200') {
                            successAlert(response.msg);
                        }
                    },
                });
            }

            function searchData() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    scrollX: true,
                    ajax: ({
                        url: "{{ route('rawData.inbound.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        }
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'call_date',
                            name: 'vicidial_closer_log.call_date'
                        },
                        {
                            data: 'phone_number',
                            name: 'vicidial_closer_log.phone_number'
                        },
                        {
                            data: 'status',
                            name: 'vicidial_closer_log.status'
                        },
                        {
                            data: 'user',
                            name: 'vicidial_closer_log.user'
                        },
                        {
                            data: 'campaign_id',
                            name: 'vicidial_agent_log.campaign_id'
                        },
                        {
                            data: 'talk_sec',
                            name: 'vicidial_agent_log.talk_sec'
                        },
                        {
                            data: 'dead_sec',
                            name: 'vicidial_agent_log.dead_sec'
                        },
                        {
                            data: 'dispo_sec',
                            name: 'vicidial_agent_log.dispo_sec'
                        },
                        {
                            data: 'length_in_sec',
                            name: 'vicidial_agent_log.length_in_sec'
                        },
                        {
                            data: 'queue_seconds',
                            name: 'vicidial_agent_log.queue_seconds'
                        },
                        {
                            data: 'term_reason',
                            name: 'vicidial_closer_log.term_reason'
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
