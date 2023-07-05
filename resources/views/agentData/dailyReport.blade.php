<x-master>
    <x-slot name="title">Daily Report</x-slot>

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
        <x-widgets.card-component cardTitle='Daily' cardHeaderVisible='1' btnVisible='0' btnName='' btnClass=''
            btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Agent ID</th>
                            <th>Login Duration</th>
                            <th>Total Break</th>
                            <th>Working Hour</th>
                            <th>ACW</th>
                            <th>Call Answered(IN)</th>
                            <th>Call Answered Time(IN)</th>
                            <th>AVG Talk Time(IN)</th>
                            <th>Dialed Calls(OUT)</th>
                            <th>Call Answered(OUT)</th>
                            <th>Call Answered Time(OUT)</th>
                            <th>AVG Talk Time(OUT)</th>
                            <th>Call Answered Time(IN + OUT)</th>
                            <th>AVG Talk Time(IN + OUT)</th>
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
             * daily report download
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
                    url: "daily-download",
                    method: 'GET',
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        agent: agent,
                    },
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
                const agent = $('#agent').val();

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('agentData.daily.datatable') }}",
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
                            data: 'login_duration',
                            name: 'login_duration'
                        },
                        {
                            data: 'break_time',
                            name: 'break_time'
                        },
                        {
                            data: 'working_time',
                            name: 'working_time'
                        },
                        {
                            data: 'after_call_work',
                            name: 'after_call_work'
                        },
                        {
                            data: 'incoming_call_answer',
                            name: 'incoming_call_answer'
                        },
                        {
                            data: 'incoming_call_answer_time',
                            name: 'incoming_call_answer_time'
                        },
                        {
                            data: 'avg_talk_time_in',
                            name: 'avg_talk_time_in'
                        },
                        {
                            data: 'dialed_call',
                            name: 'dialed_call'
                        },
                        {
                            data: 'outgoing_call_answered',
                            name: 'outgoing_call_answered'
                        },
                        {
                            data: 'outgoing_call_answered_time',
                            name: 'outgoing_call_answered_time'
                        },
                        {
                            data: 'avg_talk_time_out',
                            name: 'avg_talk_time_out'
                        },
                        {
                            data: 'talk_time_in_out',
                            name: 'talk_time_in_out'
                        },
                        {
                            data: 'avg_talk_time_in_out',
                            name: 'avg_talk_time_in_out'
                        },
                    ]
                });
            }
        </script>
    @endpush
</x-master>
