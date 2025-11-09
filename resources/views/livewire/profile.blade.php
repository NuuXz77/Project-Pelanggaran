<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><a href="/profile"><x-icon name="o-user" /> Profile</a></li>
        </ul>
    </div>

    <!-- Header dengan search -->
    <x-header title="Profile" subtitle="Ubah Profile" separator progress-indicator />
    <livewire:auth.profile />
</div>
