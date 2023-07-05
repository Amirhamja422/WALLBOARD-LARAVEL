@foreach ($counter_widgets as $counter_widget)
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-{{ $counter_widget['counterColorClass'] }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">{{ $counter_widget['counterTitle'] }}</p>
                        <h4 class="my-1 text-{{ $counter_widget['counterColorClass'] }}">
                            {{ $counter_widget['counter'] }}</h4>
                        <p class="mb-0 font-13">{{ $counter_widget['counterSummery'] }}</p>
                    </div>
                    <div
                        class="widgets-icons-2 rounded-circle bg-gradient-{{ $counter_widget['bgGradient'] }} text-white ms-auto">
                        <i class="bi {{ $counter_widget['counterIcon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
