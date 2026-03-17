<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Categories</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-l-md"
                >
                    Categories
                </a>
                <a
                    href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 border-l-0 rounded-r-md hover:bg-indigo-50"
                >
                    Products
                </a>
            </div>
        </div>

        <div id="error" class="mb-4 text-sm text-red-600 hidden"></div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody id="category-body" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(async function () {
            const $body = $('#category-body');
            const $error = $('#error');

            $body.empty();
            $error.addClass('hidden').text('');

            const token = localStorage.getItem('auth_token');

            if (!token) {
                $error.text('Missing auth token. Please log in again.').removeClass('hidden');
                return;
            }

            try {
                const response = await $.ajax({
                    url: '{{ url('/api/categories') }}',
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                const items = response.data ?? response;

                if (!Array.isArray(items) || items.length === 0) {
                    $body.append(
                        '<tr><td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">No categories found.</td></tr>'
                    );

                    return;
                }

                items.forEach((item) => {
                    $body.append(`
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.id}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.name}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.display_status}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">${item.created_at ?? ''}</td>
                        </tr>
                    `);
                });
            } catch (xhr) {
                let message = 'Failed to load categories.';

                if (xhr.status === 401 || xhr.status === 403) {
                    message = 'You are not authorized to view categories.';
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                $error.text(message).removeClass('hidden');
            }
        });
    </script>
</body>
</html>

