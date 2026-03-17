<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Products</h1>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 rounded-l-md hover:bg-indigo-50"
                >
                    Categories
                </a>
                <a
                    href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 border-l-0 rounded-r-md"
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
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variants</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody id="product-body" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(async function () {
            const $body = $('#product-body');
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
                    url: '{{ url('/api/products') }}?include=category,tags,variants',
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
                        '<tr><td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">No products found.</td></tr>'
                    );

                    return;
                }

                items.forEach((item) => {
                    const tags = (item.tags || []).map(tag => tag.name).join(', ');
                    const variantSummary = (item.variants || []).map(v => `${v.size || ''} ${v.color || ''}`.trim()).join(' | ');

                    $body.append(`
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.id}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.name}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.category?.name ?? ''}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.display_status}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${tags}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${variantSummary}</td>
                            <td class="px-4 py-2 text-sm text-right">
                                <a href="{{ url('/admin/products') }}/${item.id}" class="text-indigo-600 hover:underline text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    `);
                });
            } catch (xhr) {
                let message = 'Failed to load products.';

                if (xhr.status === 401 || xhr.status === 403) {
                    message = 'You are not authorized to view products.';
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                $error.text(message).removeClass('hidden');
            }
        });
    </script>
</body>
</html>

