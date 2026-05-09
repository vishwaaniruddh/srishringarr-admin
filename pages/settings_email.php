<?php
// pages/settings_email.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

global $con;

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $config_name = mysqli_real_escape_string($con, $_POST['config_name']);
        $smtp_host = mysqli_real_escape_string($con, $_POST['smtp_host']);
        $smtp_port = (int)$_POST['smtp_port'];
        $smtp_user = mysqli_real_escape_string($con, $_POST['smtp_user']);
        $smtp_pass = mysqli_real_escape_string($con, $_POST['smtp_pass']);
        $from_email = mysqli_real_escape_string($con, $_POST['from_email']);
        $from_name = mysqli_real_escape_string($con, $_POST['from_name']);
        
        // If it's the first config, make it active
        $check = mysqli_query($con, "SELECT count(*) as cnt FROM smtp_configs");
        $row = mysqli_fetch_assoc($check);
        $is_active = ($row['cnt'] == 0) ? 1 : 0;
        if(isset($_POST['is_active']) && $_POST['is_active'] == '1') {
             mysqli_query($con, "UPDATE smtp_configs SET is_active=0");
             $is_active = 1;
        }

        if ($id > 0) {
            $query = "UPDATE smtp_configs SET 
                config_name='$config_name', smtp_host='$smtp_host', smtp_port=$smtp_port, 
                smtp_user='$smtp_user', smtp_pass='$smtp_pass', from_email='$from_email', 
                from_name='$from_name', is_active=$is_active WHERE id=$id";
        } else {
            $query = "INSERT INTO smtp_configs (config_name, smtp_host, smtp_port, smtp_user, smtp_pass, from_email, from_name, is_active) 
                      VALUES ('$config_name', '$smtp_host', $smtp_port, '$smtp_user', '$smtp_pass', '$from_email', '$from_name', $is_active)";
        }
        
        if(mysqli_query($con, $query)) {
            $message = "Configuration saved successfully!";
            $message_type = "success";
        } else {
            $message = "Error saving configuration: " . mysqli_error($con);
            $message_type = "error";
        }
    } 
    elseif ($action === 'test_saved') {
        $id = (int)$_POST['id'];
        $test_email = $_POST['test_email'];
        
        $smtpResult = mysqli_query($con, "SELECT * FROM smtp_configs WHERE id=$id");
        if ($smtpResult && mysqli_num_rows($smtpResult) > 0) {
            $smtpConfig = mysqli_fetch_assoc($smtpResult);
            
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host       = $smtpConfig['smtp_host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $smtpConfig['smtp_user'];
                $mail->Password   = $smtpConfig['smtp_pass'];
                $mail->SMTPSecure = ($smtpConfig['smtp_port'] == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $smtpConfig['smtp_port'];

                $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
                $mail->addAddress($test_email);

                $mail->isHTML(true);
                $mail->Subject = 'SMTP Configuration Test';
                $mail->Body    = "If you are seeing this, your SMTP connection for '{$smtpConfig['config_name']}' is working correctly!";

                $mail->send();
                $message = "Test email sent successfully to $test_email using '{$smtpConfig['config_name']}'!";
                $message_type = "success";
            } catch (Exception $e) {
                $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $message_type = "error";
            }
        }
    }
    elseif ($action === 'test') {
        $smtp_host = $_POST['smtp_host'];
        $smtp_port = $_POST['smtp_port'];
        $smtp_user = $_POST['smtp_user'];
        $smtp_pass = $_POST['smtp_pass'];
        $from_email = $_POST['from_email'];
        $from_name = $_POST['from_name'];
        $test_email = $_POST['test_email'];

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_user;
            $mail->Password   = $smtp_pass;
            $mail->SMTPSecure = ($smtp_port == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtp_port;

            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($test_email);

            $mail->isHTML(true);
            $mail->Subject = 'SMTP Configuration Test';
            $mail->Body    = 'If you are seeing this, your SMTP connection is working correctly!';

            $mail->send();
            $message = "Test email sent successfully to $test_email!";
            $message_type = "success";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $message_type = "error";
        }
    }
    elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        mysqli_query($con, "DELETE FROM smtp_configs WHERE id=$id");
        $message = "Configuration deleted!";
        $message_type = "success";
    }
    elseif ($action === 'make_active') {
        $id = (int)$_POST['id'];
        mysqli_query($con, "UPDATE smtp_configs SET is_active=0");
        mysqli_query($con, "UPDATE smtp_configs SET is_active=1 WHERE id=$id");
        $message = "Active configuration updated!";
        $message_type = "success";
    }
}

// Fetch all configurations
$configs = [];
$result = mysqli_query($con, "SELECT * FROM smtp_configs ORDER BY is_active DESC, id ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $configs[] = $row;
    }
}

// Check if we are editing
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$edit_config = null;
if ($edit_id > 0) {
    foreach($configs as $c) {
        if ($c['id'] == $edit_id) {
            $edit_config = $c;
            break;
        }
    }
}
?>

<div class="page-sections" style="display:flex; flex-direction:column; gap:32px;">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:flex-end;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; color:var(--on-surface-variant); font-size:12px; margin-bottom:8px;">
                <span style="color:inherit; text-decoration:none;">Settings</span>
                <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                <span style="color:var(--primary); font-weight:600;">Email Settings</span>
            </div>
            <h1 style="font-size:28px; font-weight:800; letter-spacing:-0.5px;">Email Settings</h1>
            <p style="color:var(--on-surface-variant); font-size:14px; margin-top:4px;">Manage multiple SMTP configurations and test connections.</p>
        </div>
        <div>
            <a href="?page=settings_email#config-form" class="btn btn-primary" style="display:flex; align-items:center; gap:6px;">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span> Add New
            </a>
        </div>
    </div>

    <?php if ($message): ?>
    <div style="padding:16px; border-radius:4px; background: <?php echo $message_type == 'success' ? '#e6f4ea' : '#fce8e6'; ?>; color: <?php echo $message_type == 'success' ? '#137333' : '#c5221f'; ?>;">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof showToast === 'function') {
                showToast("<?php echo addslashes($message); ?>", "<?php echo $message_type; ?>");
            }
        });
    </script>
    <?php endif; ?>

    <!-- Saved Configs Table -->
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--outline-variant); background:var(--surface-container-low);">
            <h3 style="font-size:16px; font-weight:700; margin:0;">Saved Configurations</h3>
        </div>
        
        <?php if (empty($configs)): ?>
            <div style="padding:32px; text-align:center; color:var(--on-surface-variant); font-size:14px;">No configurations found. Add one below.</div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--outline-variant); background:var(--surface-container-lowest);">
                            <th style="padding:16px 24px; font-size:12px; color:var(--outline); text-transform:uppercase;">Config Name</th>
                            <th style="padding:16px 24px; font-size:12px; color:var(--outline); text-transform:uppercase;">Host Details</th>
                            <th style="padding:16px 24px; font-size:12px; color:var(--outline); text-transform:uppercase;">Sender</th>
                            <th style="padding:16px 24px; font-size:12px; color:var(--outline); text-transform:uppercase;">Status</th>
                            <th style="padding:16px 24px; font-size:12px; color:var(--outline); text-transform:uppercase; text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($configs as $c): ?>
                            <tr style="border-bottom:1px solid var(--outline-variant); <?php echo $c['id'] == $edit_id ? 'background:var(--surface-container-high);' : ''; ?>">
                                <td style="padding:16px 24px; font-size:14px; font-weight:600;">
                                    <?php echo htmlspecialchars($c['config_name']); ?>
                                </td>
                                <td style="padding:16px 24px; font-size:13px; color:var(--on-surface-variant);">
                                    <div><strong><?php echo htmlspecialchars($c['smtp_host']); ?></strong>:<?php echo $c['smtp_port']; ?></div>
                                </td>
                                <td style="padding:16px 24px; font-size:13px; color:var(--on-surface-variant);">
                                    <div><?php echo htmlspecialchars($c['from_name']); ?></div>
                                    <div style="font-size:11px;"><?php echo htmlspecialchars($c['from_email']); ?></div>
                                </td>
                                <td style="padding:16px 24px;">
                                    <?php if($c['is_active']): ?>
                                        <span style="font-size:11px; background:var(--primary); color:white; padding:4px 8px; border-radius:4px; font-weight:700;">ACTIVE</span>
                                    <?php else: ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="make_active">
                                            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                            <button type="submit" class="btn btn-ghost" style="padding:4px 8px; font-size:12px; height:auto; color:var(--on-surface-variant); font-weight:600;">Set as Active</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:16px 24px; text-align:right; white-space:nowrap;">
                                    <button onclick="testSavedConfig(<?php echo $c['id']; ?>, '<?php echo addslashes(htmlspecialchars($c['config_name'])); ?>')" class="btn btn-secondary" style="padding:6px 12px; font-size:12px; height:auto; margin-right:8px;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">send</span> Test
                                    </button>
                                    <a href="?page=settings_email&edit=<?php echo $c['id']; ?>#config-form" class="btn btn-ghost" style="padding:6px; color:var(--primary);" title="Edit">
                                        <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this configuration?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn btn-ghost" style="padding:6px; color:var(--error);" title="Delete">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add / Edit Form -->
    <div class="card" id="config-form" style="padding:32px; max-width:900px; margin-bottom: 40px;">
        <div style="margin-bottom:24px; border-bottom:1px solid var(--outline-variant); padding-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:20px; font-weight:800; margin:0;">
                <?php echo $edit_id ? 'Edit Configuration' : 'Add New Configuration'; ?>
            </h2>
            <?php if ($edit_id): ?>
                <a href="?page=settings_email" class="btn btn-ghost" style="font-size:12px; height:auto; padding:6px 12px;">Cancel Edit</a>
            <?php endif; ?>
        </div>
        
        <form method="POST" action="?page=settings_email" id="emailForm">
            <input type="hidden" name="action" id="formAction" value="save">
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            
            <div class="grid-2" style="gap:24px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="field-label" for="config_name">Configuration Name</label>
                    <input type="text" id="config_name" name="config_name" value="<?php echo htmlspecialchars($edit_config['config_name'] ?? ''); ?>" placeholder="e.g. Orders Email Server" required>
                </div>
                <div class="form-group">
                    <label class="field-label" for="smtp_host">SMTP Host</label>
                    <input type="text" id="smtp_host" name="smtp_host" value="<?php echo htmlspecialchars($edit_config['smtp_host'] ?? ''); ?>" placeholder="e.g. smtp.gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="field-label" for="smtp_port">SMTP Port</label>
                    <input type="number" id="smtp_port" name="smtp_port" value="<?php echo htmlspecialchars($edit_config['smtp_port'] ?? '587'); ?>" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="field-label" for="smtp_user">SMTP Username / Email</label>
                    <input type="text" id="smtp_user" name="smtp_user" value="<?php echo htmlspecialchars($edit_config['smtp_user'] ?? ''); ?>" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="field-label" for="smtp_pass">SMTP Password / App Password</label>
                    <input type="password" id="smtp_pass" name="smtp_pass" value="<?php echo htmlspecialchars($edit_config['smtp_pass'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="field-label" for="from_email">Sender Email Address</label>
                    <input type="email" id="from_email" name="from_email" value="<?php echo htmlspecialchars($edit_config['from_email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="field-label" for="from_name">Sender Name</label>
                    <input type="text" id="from_name" name="from_name" value="<?php echo htmlspecialchars($edit_config['from_name'] ?? 'Sri Shringarr'); ?>" required>
                </div>
                
                <div class="form-group" style="grid-column: span 2; margin-top:8px;">
                    <label style="display:flex; align-items:center; gap:12px; cursor:pointer; padding:16px; background:var(--surface-container-low); border-radius:8px; border:1px solid var(--outline-variant);">
                        <input type="checkbox" name="is_active" value="1" <?php echo (!empty($edit_config['is_active']) || empty($configs)) ? 'checked' : ''; ?> style="width:18px; height:18px;">
                        <div>
                            <span style="font-size:14px; font-weight:700; display:block;">Set as Active Configuration</span>
                            <span style="font-size:12px; color:var(--on-surface-variant); display:block; margin-top:2px;">This will automatically disable the current active configuration.</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <div style="margin-top: 32px; display:flex; gap:16px; align-items:center; border-top:1px solid var(--outline-variant); padding-top:24px;">
                <button type="submit" class="btn btn-primary" onclick="document.getElementById('formAction').value='save';">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Configuration
                </button>
                
                <div style="display:flex; align-items:center; gap:12px; border-left:1px solid var(--outline-variant); padding-left:16px;">
                    <input type="email" id="test_email" name="test_email" placeholder="Recipient email for test..." style="width:250px; padding:10px 12px; border:1px solid var(--outline-variant); border-radius:4px; font-size:14px;">
                    <button type="submit" class="btn btn-secondary" onclick="return runUnsavedTest();">
                        <span class="material-symbols-outlined" style="font-size:18px;">bug_report</span> Test Before Saving
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="testSavedForm" method="POST" style="display:none;">
    <input type="hidden" name="action" value="test_saved">
    <input type="hidden" name="id" id="test_saved_id" value="">
    <input type="hidden" name="test_email" id="test_saved_email" value="">
</form>

<style>
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--outline);
    margin-bottom: 8px;
}
input[type="text"], input[type="number"], input[type="password"], input[type="email"] {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid var(--outline-variant);
    background: var(--surface-container-low);
    font-size: 14px;
    outline: none;
    transition: all 0.2s;
    border-radius: 4px;
}
input:focus {
    border-color: var(--primary);
    background: var(--surface);
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}
</style>

<script>
function testSavedConfig(id, configName) {
    const email = prompt("Enter an email address to send the test email to via '" + configName + "':");
    if (email && email.includes('@')) {
        document.getElementById('test_saved_id').value = id;
        document.getElementById('test_saved_email').value = email;
        document.getElementById('testSavedForm').submit();
    } else if (email) {
        alert("Please enter a valid email address.");
    }
}

function runUnsavedTest() {
    const testEmail = document.getElementById('test_email').value;
    if (!testEmail || !testEmail.includes('@')) {
        alert("Please enter a valid recipient email address to send the test email.");
        document.getElementById('test_email').focus();
        return false;
    }
    
    // Set action to test
    document.getElementById('formAction').value = 'test';
    return true;
}
</script>
