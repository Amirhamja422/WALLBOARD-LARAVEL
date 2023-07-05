<x-master>
    <x-slot name="title">Realtime Agent</x-slot>

    <x-slot name="content_area">
        <!--start main card-->
        <x-widgets.card-component cardTitle='Realtime Agent' cardHeaderVisible='1' btnVisible='0' btnName='' btnClass=''
            btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Agent ID</th>
                            <th>Full Name</th>
                            <th>First Login Time</th>
                            <th>Status</th>
                            <th>Activity</th>
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
                const dataTable = $('#dataTable').DataTable({
                    processing: false,
                    serverSide: true,
                    bDestroy: true,
                    ajax: ({
                        url: "{{ route('dashboard.realtimeAgent.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'campaign_id',
                            name: 'vicidial_live_agents.campaign_id'
                        },
                        {
                            data: 'user',
                            name: 'vicidial_live_agents.user'
                        },
                        {
                            data: 'full_name',
                            name: 'vicidial_users.full_name'
                        },
                        {
                            data: 'first_login_time',
                            name: 'first_login_time'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'last_update_time',
                            name: 'last_update_time'
                        },
                    ]
                });

                setInterval(function() {
                    dataTable.ajax.reload();
                }, 5000);
            });
        </script>
    @endpush
</x-master>
