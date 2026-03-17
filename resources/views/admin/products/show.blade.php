<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Details</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Product Details</h1>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:underline">
                Back to products
            </a>
        </div>

        <div id="error" class="mb-4 text-sm text-red-600 hidden"></div>

        <div id="product-container" class="space-y-6 hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 id="product-name" class="text-xl font-semibold mb-2"></h2>
                <p id="product-description" class="text-gray-700 mb-4"></p>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Category</dt>
                        <dd id="product-category" class="text-gray-900"></dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Status</dt>
                        <dd id="product-status" class="text-gray-900"></dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-gray-500">Tags</dt>
                        <dd id="product-tags" class="text-gray-900"></dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Variants</h3>

                <div id="variants-empty" class="text-sm text-gray-500 hidden">
                    No variants defined for this product.
                </div>

                <div id="variants-table-wrapper" class="overflow-x-auto hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            </tr>
                        </thead>
                        <tbody id="variants-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(async function () {
            const $error = $('#error');
            const $container = $('#product-container');
            const $variantsEmpty = $('#variants-empty');
            const $variantsTableWrapper = $('#variants-table-wrapper');
            const $variantsBody = $('#variants-body');

            $error.addClass('hidden').text('');
            $container.addClass('hidden');

            const token = localStorage.getItem('auth_token');

            if (!token) {
                $error.text('Missing auth token. Please log in again.').removeClass('hidden');
                return;
            }

            const productId = {{ (int) request()->route('id') }};

            try {
                const response = await $.ajax({
                    url: '{{ url('/api/products') }}?include=category,tags,variants',
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                    data: {
                        id: productId,
                    },
                });

                const items = response.data ?? response;
                const product = Array.isArray(items)
                    ? items.find(p => p.id === productId)
                    : (items.id === productId ? items : null);

                if (!product) {
                    $error.text('Product not found.').removeClass('hidden');
                    return;
                }

                $('#product-name').text(product.name);
                $('#product-description').text(product.description);
                $('#product-category').text(product.category?.name ?? '');
                $('#product-status').text(product.display_status);
                $('#product-tags').text((product.tags || []).map(t => t.name).join(', ') || '—');

                const variants = product.variants || [];
                $variantsBody.empty();

                if (variants.length === 0) {
                    $variantsEmpty.removeClass('hidden');
                    $variantsTableWrapper.addClass('hidden');
                } else {
                    $variantsEmpty.addClass('hidden');
                    $variantsTableWrapper.removeClass('hidden');

                    variants.forEach(v => {
                        $variantsBody.append(`
                            <tr>
                                <td class="px-4 py-2 text-gray-700">${v.size ?? ''}</td>
                                <td class="px-4 py-2 text-gray-700">${v.color ?? ''}</td>
                                <td class="px-4 py-2 text-gray-700">${v.price ?? ''}</td>
                            </tr>
                        `);
                    });
                }

                $container.removeClass('hidden');
            } catch (xhr) {
                let message = 'Failed to load product.';

                if (xhr.status === 401 || xhr.status === 403) {
                    message = 'You are not authorized to view this product.';
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                $error.text(message).removeClass('hidden');
            }
        });
    </script>
</body>
</html>

