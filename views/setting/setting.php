<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Target Coffee</title>
    <link rel="stylesheet" href="viwes/assets/css/settings.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Settings</h1>
            <div class="tabs">
                <button class="tab active">General</button>
                <button class="tab">Notification</button>
                <button class="tab">Role & Permission</button>
                <button class="tab">Security</button>
                <button class="tab">Backup & Restore</button>
            </div>
        </header>
        <div class="settings-form">
            <form>
                <div class="form-group">
                    <label for="site-name">Site Name</label>
                    <input type="text" id="site-name" value="My Website" required>
                </div>
                <div class="form-group">
                    <label for="logo-url">Logo URL</label>
                    <input type="text" id="logo-url" value="https://targetcoffee.com/logo.png" placeholder="Enter the URL of your logo image" required>
                </div>
                <div class="form-group">
                    <label for="contact-email">Contact Email</label>
                    <input type="email" id="contact-email" value="targetcoffee@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="contact-phone">Contact Phone</label>
                    <input type="tel" id="contact-phone" value="081 369 639" required>
                </div>
                <div class="form-group">
                    <label for="business-address">Business Address</label>
                    <input type="text" id="business-address" value="BP 511, Phum Tropeng Chhuk (Borey Sorla) Sangkat, Street 371, Phnom Penh" required>
                </div>
                <div class="form-group time-group">
                    <div>
                        <label for="opening-time">Opening Time</label>
                        <input type="time" id="opening-time" value="09:00" required>
                    </div>
                    <div>
                        <label for="close-time">Close Time</label>
                        <input type="time" id="close-time" value="20:00" required>
                    </div>
                </div>
                <button type="submit" class="save-btn">Save Change</button>
            </form>
        </div>
    </div>
</body>
</html>