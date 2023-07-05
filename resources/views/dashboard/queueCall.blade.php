<x-master>
    <x-slot name="title">Queue Calls</x-slot>

    <x-slot name="content_area">
        <!--start main card-->
        <x-widgets.card-component cardTitle='Queue Calls' cardHeaderVisible='1' btnVisible='0' btnName='' btnClass=''
            btnIcon=''>
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Skill ID</th>
                            <th>Phone Number</th>
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
                        url: "{{ route('dashboard.queue.datatable') }}",
                        type: "GET",
                    }),
                    displayLength: 10,
                    columns: [{
                            data: 'campaign_id',
                            name: 'campaign_id'
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number'
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
