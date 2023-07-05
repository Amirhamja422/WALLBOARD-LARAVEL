<x-master>
    <x-slot name="title">Aux Report</x-slot>

    <x-slot name="content_area">
        <!--start searchbox-->
        <x-widgets.card-component cardTitle='' cardHeaderVisible='0' btnVisible='0' btnName='' btnClass='' btnIcon=''>
            <div class="row">
                <x-widgets.input-component inputColClass="col-md-6 mb-3" inputLabel="Start Date" inputId="start_date"
                    inputType="text" required='' />

                <x-widgets.input-component inputColClass="col-md-6 mb-3" inputLabel="End Date" inputId="end_date"
                    inputType="text" required='' />

                <div class="col-md-6">
                    <label class="form-label" for="search_type">Search Type</label>
                    <select class="form-select mb-3" id="search_type">
                        <option value="single_day">Single Day</option>
                        <option value="sum_day">Sum Day</option>
                    </select>
                </div>

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

            <div class="row">
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

        <x-widgets.card-component cardTitle='Aux Report' cardHeaderVisible='1' btnVisible='0' btnName=''
            btnClass='' btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Login ID</th>
                            @foreach ($pauseCodes as $pauseCode)
                                <th>{{ $pauseCode }}</th>
                            @endforeach
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
                const search_type = $('#search_type').val();
                const agent = $('#agent').val();

                var url = "{{ URL::to('/aux-download') }}/" + start_date + "/" + end_date;
                $('#download').attr('href', url);

                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('agentData.aux.datatable') }}",
                        type: "GET",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                            search_type: search_type,
                            agent: agent,
                        },
                    }),
                    displayLength: 10,
                    columns: <?php echo $jsonEncode; ?>
                });
            }
        </script>
    @endpush
</x-master>
