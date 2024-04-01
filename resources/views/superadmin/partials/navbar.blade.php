<div class="content-header" style="padding: 4px .5rem;">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="max-height: 50px;margin-top:-5px;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }} " method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>        
    </nav>
</div>
</div>
<script>
    document.getElementById('logout-form').addEventListener('submit', function(event) {
        this.submit();
    });
</script>

