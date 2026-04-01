<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link {{ $activeTab == 'index' ? 'active':'' }}" href="{{ route('form-definitions.index') }}">Definitions</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ $activeTab == 'backup' ? 'active':'' }}" href="{{ route('form-definitions.backups') }}">Backup</a>
  </li>
<br>