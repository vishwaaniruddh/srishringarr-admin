<?php
// pages/settings_site_info.php
require_once __DIR__ . '/../../config.php';

global $con;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = mysqli_real_escape_string($con, $_POST['site_name']);
    $contact_email = mysqli_real_escape_string($con, $_POST['contact_email']);
    $contact_phone = mysqli_real_escape_string($con, $_POST['contact_phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $facebook_link = mysqli_real_escape_string($con, $_POST['facebook_link']);
    $instagram_link = mysqli_real_escape_string($con, $_POST['instagram_link']);

    $settings = [
        'site_name' => $site_name,
        'contact_email' => $contact_email,
        'contact_phone' => $contact_phone,
        'address' => $address,
        'facebook_link' => $facebook_link,
        'instagram_link' => $instagram_link
    ];

    foreach ($settings as $key => $value) {
        $query = "INSERT INTO site_info_settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value = '$value'";
        mysqli_query($con, $query);
    }
    
    echo "<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof showToast === 'function') {
            showToast('Site Information Updated Successfully!', 'success');
        }
    });
    </script>";
}

// Fetch existing settings
$settings = [];
$result = mysqli_query($con, "SELECT setting_key, setting_value FROM site_info_settings");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
?>

<div class="page-sections">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; color:var(--on-surface-variant); font-size:12px; margin-bottom:8px;">
                <span style="color:inherit; text-decoration:none;">Settings</span>
                <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                <span style="color:var(--primary); font-weight:600;">Site Information</span>
            </div>
            <h1 style="font-size:28px; font-weight:800; letter-spacing:-0.5px;">Site Information</h1>
            <p style="color:var(--on-surface-variant); font-size:14px; margin-top:4px;">Configure general site information and contact details.</p>
        </div>
    </div>

    <div class="card" style="padding:24px; max-width:800px;">
        <form method="POST" action="">
            <div style="display:flex; flex-direction:column; gap:24px;">
                <div class="form-group">
                    <label class="field-label" for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'Sri Shringarr'); ?>" required>
                </div>
                
                <div class="grid-2" style="gap:24px;">
                    <div class="form-group">
                        <label class="field-label" for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="contact_phone">Contact Phone</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="facebook_link">Facebook URL</label>
                        <input type="url" id="facebook_link" name="facebook_link" value="<?php echo htmlspecialchars($settings['facebook_link'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="instagram_link">Instagram URL</label>
                        <input type="url" id="instagram_link" name="instagram_link" value="<?php echo htmlspecialchars($settings['instagram_link'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="field-label" for="address">Store Address</label>
                    <textarea id="address" name="address" rows="3" required style="width:100%; padding:10px 12px; border:1px solid var(--outline-variant); background:var(--surface-container-low); font-size:14px; outline:none; transition:border-color 0.2s; border-radius:4px; font-family:inherit; resize:vertical;"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div style="margin-top: 32px;">
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span> Save Site Info
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--outline);
    margin-bottom: 8px;
}
input[type="text"], input[type="number"], input[type="password"], input[type="email"], input[type="url"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--outline-variant);
    background: var(--surface-container-low);
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
    border-radius: 4px;
}
input:focus, textarea:focus {
    border-color: var(--primary);
}
</style>
