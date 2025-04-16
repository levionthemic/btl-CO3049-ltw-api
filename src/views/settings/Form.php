
<?php
// This file is not used, just for demonstrating MVC model
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <form action="settings" method="post" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="space-y-6">
            <div>
                <label for="hotel_name" class="block text-sm font-medium text-gray-700 mb-1">Hotel Name:</label>
                <input type="text" id="hotel_name" name="hotel_name" required 
                    value="<?php echo htmlspecialchars($currentSettings['hotel_name'] ?? ''); ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required 
                    value="<?php echo htmlspecialchars($currentSettings['phone_number'] ?? ''); ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address:</label>
                <textarea id="address" name="address" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent h-32"><?php echo htmlspecialchars($currentSettings['address'] ?? ''); ?></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    Submit
                </button>
            </div>
        </div>
    </form>
</body>
</html> 