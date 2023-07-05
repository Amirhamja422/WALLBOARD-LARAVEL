<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ URL::asset('images/ihelp.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text"><a href="{{ route('dashboard') }}">iHelpBD</a></h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-house-heart"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li><a href="{{ route('dashboard.realtimeAgent') }}"><i class="bi bi-arrow-right-short"></i>Realtime
                        Agent</a></li>
                <li><a href="{{ route('dashboard.queueCall') }}"><i class="bi bi-arrow-right-short"></i>Queue Calls</a>
                </li>
                <li><a href="{{ route('dashboard.charts') }}"><i class="bi bi-arrow-right-short"></i>Charts</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-journal-check"></i></div>
                <div class="menu-title">CRM</div>
            </a>
            <ul>
                <li><a href="{{ route('crm.crm') }}"><i class="bi bi-arrow-right-short"></i>CRM
                        Report</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-people"></i></div>
                <div class="menu-title">Agents</div>
            </a>
            <ul>
                <li><a href="{{ route('agentData.daily') }}"><i class="bi bi-arrow-right-short"></i>Daily Report</a>
                </li>
                <li><a href="{{ route('agentData.aux') }}"><i class="bi bi-arrow-right-short"></i>Aux Report</a></li>
                <li><a href="{{ route('agentData.auth') }}"><i class="bi bi-arrow-right-short"></i>Auth Report</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-clipboard-data"></i></div>
                <div class="menu-title">Raw Data</div>
            </a>
            <ul>
                <li><a href="{{ route('rawData.inbound') }}"><i class="bi bi-arrow-right-short"></i>Inbound</a></li>
                <li><a href="{{ route('rawData.drop') }}"><i class="bi bi-arrow-right-short"></i>Drop Calls</a></li>
                <li><a href="{{ route('rawData.outbound') }}"><i class="bi bi-arrow-right-short"></i>Outbound</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-play-btn"></i></div>
                <div class="menu-title">Recordings</div>
            </a>
            <ul>
                <li><a href="{{ route('recording.recording') }}"><i class="bi bi-arrow-right-short"></i>Recording
                        Report</a></li>
                {{-- <li><a href="{{ route('recording.qc') }}"><i class="bi bi-arrow-right-short"></i>Recording QC</a></li> --}}
            </ul>
        </li>

        <li class="menu-label">Initial Settings</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-gear"></i></div>
                <div class="menu-title">System</div>
            </a>
            <ul>
                <li><a href="{{ route('system.phone') }}"><i class="bi bi-arrow-right-short"></i>Phones</a></li>
                <li><a href="{{ route('system.user') }}"><i class="bi bi-arrow-right-short"></i>Users</a></li>
                <li><a href="{{ route('system.block') }}"><i class="bi bi-arrow-right-short"></i>Blocking</a></li>
                <li><a href="{{ route('system.administrator') }}"><i
                            class="bi bi-arrow-right-short"></i>Administrator</a></li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>
