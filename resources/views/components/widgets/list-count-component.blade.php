<ul class="list-group list-group-flush">
    @foreach ($list_count_widgets as $list_count_widget)
        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
            {{ $list_count_widget['listCountTitle'] }}<span
                class="badge bg-{{ $list_count_widget['badgeBg'] }} rounded-pill">{{ $list_count_widget['listCount'] }}</span>
        </li>
    @endforeach
</ul>
