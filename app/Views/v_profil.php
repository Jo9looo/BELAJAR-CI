<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Profile</h5>
        
        <h2>Profil Pengguna</h2>
        <ul>
            <li><strong>Username:</strong> <?= esc($username) ?></li>
            <li><strong>Role:</strong> <?= esc($role) ?></li>
            <li><strong>Email:</strong> <?= esc($email) ?></li>
            <li><strong>Waktu Login:</strong> <?= esc($waktu_login) ?></li>
            <li><strong>Status Login:</strong> <?= esc($status_login) ?></li>
        </ul>
        
    </div>
</div>

<?= $this->endSection() ?>
