<div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
    @foreach ($general_widgets as $general_widget)
        <div class="col">
            <div class="p-3">
                <h5 class="mb-0">{{ $general_widget['generalCounter'] }}</h5>
                <small class="mb-0">{{ $general_widget['generalPercentageTitle'] }}<span><i
                            class="bx bx-up-arrow-alt align-middle"></i>{{ $general_widget['generalPercentage'] }}</span></small>
            </div>
        </div>
    @endforeach
</div>
